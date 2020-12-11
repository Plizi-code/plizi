<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blacklist\BlacklistDelete;
use App\Http\Requests\Blacklist\BlacklistStore;
use App\Http\Resources\User\SimpleUsers;
use App\Models\User\Blacklisted;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserBlacklistController extends Controller
{
    public function index(Request $request)
    {
        $blacklistUsers = Blacklisted::where('user_id', auth()->user()->id)
            ->with('user.profile')
            ->limit($request->query('limit', 50))
            ->offset($request->query('offset', 0))
            ->get()
            ->pluck('user');

        return new SimpleUsers($blacklistUsers);
    }

    /**
     * Add user to blacklist api method.
     * @param BlacklistStore $request
     * @return JsonResponse
     */
    public function store(BlacklistStore $request)
    {
        $res = Blacklisted::create([
            'user_id' => \Auth::user()->id,
            'blacklisted_id' => $request->userId,
        ]);

        if ($res) {
            return response()->json([
                'message' => 'Пользователь успешно добавлен в черный список.'
            ]);
        }
        return response()->json([
            'message' => 'Ошибка добавления пользователя.'
        ], 422);
    }

    /**
     * Delete user from blacklist api method.
     * @param BlacklistDelete $request
     * @return JsonResponse
     */
    public function delete(BlacklistDelete $request)
    {
        $res = Blacklisted::where('blacklisted_id', $request->userId)
            ->where('user_id', auth()->user()->id)
            ->delete();

        if ($res) {
            return response()->json([
                'message' => 'Пользователь успешно удален из черного списка.',
            ]);
        }
        return response()->json([
            'message' => 'Ошибка удаления пользователя',
        ], 422);
    }
}
