<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Services\v1\User\ResetPasswordService;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * @var ResetPasswordService
     */
    private $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function sendMail(Request $request)
    {
        return $this->resetPasswordService->sendMail($request);
    }

    public function reset(Request $request, $token)
    {
        return $this->resetPasswordService->reset($request, $token);
    }
}
