<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Log::notice('Authentication succeeded.', [
                'user_id' => Auth::id(),
                'ip_hash' => hash('sha256', (string) $request->ip()),
            ]);

            /** @var User $user */
            $user = Auth::user();

            // Role-based redirect
            return match ($user->role?->name) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'cms_editor' => redirect()->intended(route('admin.cms.posts.index')),
                'email_manager' => redirect()->intended(route('admin.email.campaigns.index')),
                'hr_manager' => redirect()->intended(route('admin.hrm.employees.index')),
                'employee' => redirect()->intended(route('admin.hrm.employees.index')),
                default => redirect()->intended(route('admin.dashboard')),
            };
        }

        Log::warning('Authentication failed.', [
            'identity_hash' => hash('sha256', strtolower((string) $request->input('email'))),
            'ip_hash' => hash('sha256', (string) $request->ip()),
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
