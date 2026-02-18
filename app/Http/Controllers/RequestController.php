<?php

namespace App\Http\Controllers;

use App\Services\RequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct(
        private RequestService $requestService
    ) {}

    public function create(): View
    {
        return view('requests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'problem_text' => ['required', 'string', 'max:2000'],
        ], [
            'client_name.required' => 'Укажите имя клиента.',
            'client_name.max' => 'Имя клиента не должно превышать 255 символов.',
            'phone.required' => 'Укажите телефон.',
            'phone.max' => 'Телефон не должен превышать 50 символов.',
            'address.required' => 'Укажите адрес.',
            'address.max' => 'Адрес не должен превышать 255 символов.',
            'problem_text.required' => 'Опишите проблему.',
            'problem_text.max' => 'Описание не должно превышать 2000 символов.',
        ]);

        $this->requestService->create($data);
        return redirect()->route('requests.create')->with('success', 'Заявка успешно создана.');
    }
}
