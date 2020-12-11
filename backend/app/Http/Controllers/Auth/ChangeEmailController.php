<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ChangeEmailController extends Controller
{
    public function changeEmail(ChangeEmailRequest $request)
    {
        $user = User::find(\Auth::user()->id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->email = $request->get('newEmail');
        $user->save();

        return response()->json([
            'message' => 'Email успешно обновлён.',
        ]);
    }
}
