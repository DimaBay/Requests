<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\User;
use App\Services\RequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DispatcherController extends Controller
{
    public function __construct(
        private RequestService $requestService
    ) {}

    public function index(Request $request): View
    {
        $query = RepairRequest::with('assignedTo')->orderByDesc('created_at');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $requests = $query->paginate(15);
        $masters = User::where('role', 'master')->orderBy('name')->get();
        return view('dispatcher.index', compact('requests', 'masters'));
    }

    public function assign(Request $request, RepairRequest $repairRequest): RedirectResponse
    {
        $validated = $request->validate([
            'master_id' => ['required', 'exists:users,id'],
        ]);
        try {
            $this->requestService->assign($repairRequest->id, (int) $validated['master_id'], auth()->id());
            return back()->with('success', 'Мастер назначен.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(RepairRequest $repairRequest): RedirectResponse
    {
        try {
            $this->requestService->cancel($repairRequest->id, auth()->id());
            return back()->with('success', 'Заявка отменена.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
