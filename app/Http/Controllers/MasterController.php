<?php

namespace App\Http\Controllers;

use App\Exceptions\RequestConflictException;
use App\Models\RepairRequest;
use App\Services\RequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MasterController extends Controller
{
    public function __construct(
        private RequestService $requestService
    ) {}

    public function index(): View
    {
        $requests = RepairRequest::where('assigned_to', auth()->id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->orderByRaw("CASE status WHEN 'assigned' THEN 1 WHEN 'in_progress' THEN 2 END")
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('master.index', compact('requests'));
    }

    public function start(RepairRequest $repairRequest): RedirectResponse
    {
        try {
            $this->requestService->start($repairRequest->id, auth()->id());
            return back()->with('success', 'Заявка взята в работу.');
        } catch (RequestConflictException $e) {
            abort(409, $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function done(RepairRequest $repairRequest): RedirectResponse
    {
        try {
            $this->requestService->done($repairRequest->id, auth()->id());
            return back()->with('success', 'Заявка завершена.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
