<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthService $authService;

   
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        
        $this->middleware('guest')->except('logout');
    }

    /**
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $credentials = [
                'email' => $request->validated('email'),
                'password' => $request->validated('password'),
                'remember' => $request->boolean('remember'),
            ];

            $instalador = $this->authService->login($credentials);

            return redirect()->intended(route('dashboard'))
                ->with('success', "¡Bienvenido de vuelta, {$instalador->nombre}!");

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('email'));
        }
    }

    /**
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}