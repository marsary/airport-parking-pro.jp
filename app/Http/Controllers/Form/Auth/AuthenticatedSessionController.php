<?php

namespace App\Http\Controllers\Form\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $reserve = session()->get('reserve');
        return view('form.auth.login', [
            'reserve' => $reserve
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // セッションに予約情報がある場合は情報入力画面へ、ない場合は日付選択画面へ
        if($request->session()->has('reserve')) {
            return redirect()->route('form.reserves.entry_info');
        }
        return redirect()->route('form.reserves.entry_date');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('members')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('form.login');
    }
}
