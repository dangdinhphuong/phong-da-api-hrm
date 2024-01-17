<?php

namespace App\Http\Services\v1\User;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordService extends Controller
{
    public function setModel()
    {
        $this->model = new User();
    }

    public function sendMail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => __('message.email_not_found'),
            ], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            $user->notify(new ResetPasswordRequest($passwordReset->token));
        }

        return response()->json([
            'message' => __('message.send_mail_success'),
        ], 200);
    }

    public function reset(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => __('message.token_invalid'),
            ], 422);
        }

        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $user->update(['password' => bcrypt($request->newPassword)]);
        $passwordReset->delete();

        return response()->json([
            'message' =>  __('message.reset_pass_success'),
        ], 200);
    }
}
