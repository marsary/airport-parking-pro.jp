<?php

namespace App\Http\Controllers\Form\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('form.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'redirect_route' => ['nullable'],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('members')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if($status == Password::PASSWORD_RESET) {

            if($request->has('redirect_route') && $request->input('redirect_route')) {
                return redirect()->route($request->input('redirect_route'))->with('status', __($status));
            } else {
                return $this->redirectToReservePage($request, $status);
            }
        } else {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        // return $status == Password::PASSWORD_RESET
        //             ? $this->redirectToReservePage($status) //redirect()->route('login')->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                 ->withErrors(['email' => __($status)]);
    }

    protected function redirectToReservePage($request, $status)
    {
        Auth::guard('members')->attempt(['email' => $request->email, 'password' => $request->password]);
        if(Auth::guard('web')->check()) {
            return redirect()->route('form.reserves.entry_info')->with('status', __($status));
        }
        return redirect()->route('form.reserves.entry_date')->with('status', __($status));
    }
}


