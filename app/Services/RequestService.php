<?php

namespace App\Services;

use App\Enums\RequestStatus;
use App\Exceptions\RequestConflictException;
use App\Models\RepairRequest;
use App\Models\RequestLog;
use Illuminate\Support\Facades\DB;

class RequestService
{
    public function create(array $data): RepairRequest
    {
        $data['status'] = RequestStatus::New;
        $request = RepairRequest::create($data);
        RequestLog::create([
            'request_id' => $request->id,
            'old_status' => null,
            'new_status' => RequestStatus::New->value,
            'changed_by' => null,
        ]);
        return $request;
    }

    public function assign(int $requestId, int $masterId, ?int $changedBy = null): RepairRequest
    {
        return DB::transaction(function () use ($requestId, $masterId, $changedBy) {
            $request = RepairRequest::findOrFail($requestId);
            $oldStatus = $request->status;
            if ($oldStatus !== RequestStatus::New) {
                throw new \InvalidArgumentException('Назначить мастера можно только для новой заявки.');
            }
            $request->update([
                'status' => RequestStatus::Assigned,
                'assigned_to' => $masterId,
            ]);
            RequestLog::create([
                'request_id' => $request->id,
                'old_status' => $oldStatus->value,
                'new_status' => RequestStatus::Assigned->value,
                'changed_by' => $changedBy,
            ]);
            return $request->fresh();
        });
    }

    public function cancel(int $requestId, ?int $changedBy = null): RepairRequest
    {
        return DB::transaction(function () use ($requestId, $changedBy) {
            $request = RepairRequest::findOrFail($requestId);
            $oldStatus = $request->status;
            if (in_array($oldStatus, [RequestStatus::Done, RequestStatus::Canceled])) {
                throw new \InvalidArgumentException('Невозможно отменить выполненную или уже отменённую заявку.');
            }
            $request->update([
                'status' => RequestStatus::Canceled,
                'assigned_to' => null,
            ]);
            RequestLog::create([
                'request_id' => $request->id,
                'old_status' => $oldStatus->value,
                'new_status' => RequestStatus::Canceled->value,
                'changed_by' => $changedBy,
            ]);
            return $request->fresh();
        });
    }

    public function start(int $requestId, int $masterId): RepairRequest
    {
        return DB::transaction(function () use ($requestId, $masterId) {
            $affected = RepairRequest::where('id', $requestId)
                ->where('status', RequestStatus::Assigned)
                ->where('assigned_to', $masterId)
                ->update(['status' => RequestStatus::InProgress]);

            if ($affected === 0) {
                throw new RequestConflictException('Заявка уже взята в работу. Обновите страницу.');
            }

            $request = RepairRequest::findOrFail($requestId);
            RequestLog::create([
                'request_id' => $request->id,
                'old_status' => RequestStatus::Assigned->value,
                'new_status' => RequestStatus::InProgress->value,
                'changed_by' => $masterId,
            ]);
            return $request->fresh();
        });
    }

    public function done(int $requestId, int $masterId, ?int $changedBy = null): RepairRequest
    {
        $changedBy = $changedBy ?? $masterId;
        return DB::transaction(function () use ($requestId, $masterId, $changedBy) {
            $request = RepairRequest::findOrFail($requestId);
            $oldStatus = $request->status;
            if ($request->assigned_to !== $masterId) {
                throw new \InvalidArgumentException('Завершить может только назначенный мастер.');
            }
            if (!in_array($oldStatus, [RequestStatus::Assigned, RequestStatus::InProgress])) {
                throw new \InvalidArgumentException('Невозможно завершить заявку в текущем статусе.');
            }
            $request->update(['status' => RequestStatus::Done]);
            RequestLog::create([
                'request_id' => $request->id,
                'old_status' => $oldStatus->value,
                'new_status' => RequestStatus::Done->value,
                'changed_by' => $changedBy,
            ]);
            return $request->fresh();
        });
    }
}
