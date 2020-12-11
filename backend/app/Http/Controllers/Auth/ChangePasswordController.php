<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChangePasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::user()->id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->password = Hash::make($request->get('newPassword'));
        $user->save();

        return response()->json([
            'message' => 'Пароль успешно обновлён.',
        ]);
    }
}
