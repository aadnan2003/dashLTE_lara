<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Symfony\Component\HttpFoundation\Response;
use Str;

class ResetPasswordController extends Controller
{
    //
    public function requestPasswordRest()
    {
        return response()->view('cms.auth.forgot-password');
    }

    public function sendResetEmail(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email'
        ]);

        if (!$validator->fails()) {
            $status = Password::sendResetLink($request->all());
            return $status == Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)])
                :  response()->json(['message' => __($status)], Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        return response()->view('cms.auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email',
            'password' => [
                'required', 'string', 'confirmed',
                RulesPassword::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);

        if (!$validator->fails()) {
            $status = Password::reset($request->all(), function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);
                //->setRememberToken(Str::random(60))
                $user->save();
                event(new PasswordReset($user));
            });

            return $status == Password::PASSWORD_RESET
                ? response()->json(['message' => __($status)])
                : response()->json(['message' => __($status)], Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);
        }
    }
}
