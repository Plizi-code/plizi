<?php

namespace App\Http\Controllers\Api;

use App\Events\Followers\SubFollower;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddToFriend;
use App\Http\Requests\User\MarkNotificationsAsRead;
use App\Http\Resources\Notification\NotificationCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserSearchCollection;
use App\Models\User;
use Domain\Neo4j\Service\UserService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class UserController extends Controller
{

    /**
     * @param string $search
     * @param Request $request
     * @return UserSearchCollection
     */
    public function search(string $search, Request $request)
    {
        if (mb_strlen($search) < 3) {
            return new UserSearchCollection([]);
        }

        if (\auth()->guest()) {
            try {
                JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {}
        }

        /** @var Builder $query */
        $query = User::where(static function (Builder $query) use ($search) {
            $query->whereHas('profile', static function (Builder $profile) use ($search) {
                $profile
                    ->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhereHas('city', static function (Builder $city) use ($search) {
                        $city
                            ->where('title_ru', 'LIKE', "%{$search}%")
                            ->orWhere('region_ru', 'LIKE', "%{$search}%");
                    })
                    ->orderBy('last_name');
            });
        })
            ->with('profile', 'profile.city');

        if (Auth::guest()) {
            $query->limit(10);
        } else {
            $query
                ->where('id', '<>', Auth::id())
                ->limit($request->query('limit', 10))
                ->offset($request->query('offset', 0));
        }

        $users = $query->get();
        return new UserSearchCollection($users);
    }

    /**
     * @param AddToFriend $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendFriendshipRequest(AddToFriend $request) {
        $recipient = User::find($request->userId);
        if($request->userId === Auth::user()->id) {
            return response()->json(['message' => 'Вы не можете добавить самого себя в друзья'], 422);
        }
        if($recipient->hasFriendRequestFrom(Auth::user())) {
            return response()->json(['message' => 'Вы уже отправляли запрос данному пользователю'], 422);
        }
        if($recipient->isFriendWith(Auth::user())) {
            return response()->json(['message' => 'Вы уже являетесь другом данному пользователю'], 422);
        }
        Auth::user()->befriend($recipient);
        return response()->json(['message' => 'Запрос на добавление в друзья отправлен'], 200);
    }

    /**
     * @param AddToFriend $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptFriendshipRequest(AddToFriend $request) {
        $sender = User::find($request->userId);
        if(Auth::user()->hasFriendRequestFrom($sender)) {
            Auth::user()->acceptFriendRequest($sender);
            /**
             * удаляются взаимные подписки
             */
            $userService = new UserService();
            if ($userService->unfollow(Auth::user()->id, $sender->id)) {
                event(new SubFollower($sender));
            }
            if ($userService->unfollow($sender->id, Auth::user()->id)) {
                event(new SubFollower(Auth::user()));
            }
            return response()->json(['message' => 'Вы приняли пользователя в друзья'], 200);
        }
        return response()->json(['message' => 'Данный пользователь не отправлял вам запрос в друзья'], 422);
    }

    /**
     * @param AddToFriend $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function declineFriendshipRequest(AddToFriend $request) {
        $sender = User::find($request->userId);
        if(Auth::user()->hasFriendRequestFrom($sender)) {
            Auth::user()->denyFriendRequest($sender);
            return response()->json(['message' => 'Вы отклонили запрос на добавление в друзья от пользователя'], 200);
        }
        return response()->json(['message' => 'Данный пользователь не отправлял вам запрос в друзья'], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromFriends($id) {
        $friend = User::find($id);
        if (Auth::user()->isFriendWith($friend)) {
            Auth::user()->unfriend($friend);
            return response()->json(['message' => 'Вы удалили пользователя из друзей'], 200);
        }
        return response()->json(['message' => 'Вы не являетесь другом данному пользователю'], 422);
    }

    /**
     * @param AddToFriend $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockFriendshipRequest(AddToFriend $request) {
        $sender = User::find($request->userId);
        if(Auth::user()->hasFriendRequestFrom($sender)) {
            Auth::user()->blockFriend($sender);
            return response()->json(['message' => 'Вы Заблокировали пользователя'], 200);
        }
        return response()->json(['message' => 'Данный пользователь не отправлял вам запрос в друзья'], 200);
    }

    /**
     * @param Request $request
     * @return UserCollection
     */
    public function getMyFriendsList(Request $request) {
        $friend_ids = Auth::user()->getFriends($request->query('limit') ?? 50, $request->query('offset') ?? 0);
        $friends = User::with('profile', 'profile.avatar')
            ->whereIn('id', array_keys($friend_ids))
            ->get();
        $total_count = array_key_first($friend_ids) ? $friend_ids[array_key_first($friend_ids)]['total_count'] : 0;
        foreach ($friends as $friend) {
            $friend->mutual_count = $friend_ids[$friend->id]['mutual_count'];
        }
        return new UserCollection($friends, $total_count);
    }

    /**
     * @return UserCollection
     */
    public function getMyPendingFriendsList() {
        $request_user_ids = Auth::user()->getFriendRequests()->pluck('sender_id');
        $requests = User::with('profile', 'profile.avatar')->whereIn('id', $request_user_ids)->get();
        return new UserCollection($requests);
    }

    /**
     * @param Request $request
     * @param $id
     * @return UserCollection
     */
    public function getUserFriendsList(Request $request, $id) {
        $user = User::find($id);
        $friend_ids = $user->getFriends($request->query('limit') ?? 50, $request->query('offset') ?? 0);
        $friends = User::with( 'profile', 'profile.avatar')->whereIn('id', array_keys($friend_ids))->get();
        $total_count = array_key_first($friend_ids) ? $friend_ids[array_key_first($friend_ids)]['total_count'] : 0;
        foreach ($friends as $friend) {
            $friend->mutual_count = $friend_ids[$friend->id]['mutual_count'];
        }
        return new UserCollection($friends, $total_count);
    }

    /**
     * @param Request $request
     * @return UserCollection
     */
    public function getPossibleFriends(Request $request) {
        $fof_ids = Auth::user()->getFriendsOfFriends($request->query('limit') ?? 50, $request->query('offset') ?? 0);
        $fofs = User::with( 'profile', 'profile.avatar')->whereIn('id', array_keys($fof_ids))->get();
        $total_count = array_key_first($fof_ids) ? $fof_ids[array_key_first($fof_ids)]['total_count'] : 0;
        foreach ($fofs as $fof) {
            $fof->mutual_count = $fof_ids[$fof->id]['mutual_count'];
        }
        return new UserCollection($fofs, $total_count);
    }

    /**
     * @param Request $request
     * @return UserCollection
     */
    public function getRecommendedFriends(Request $request) {
        $recommended_ids = Auth::user()->getRecommendedFriends($request->query('limit') ?? 50, $request->query('offset') ?? 0);
        $recommended = User::with( 'profile', 'profile.avatar')->whereIn('id', array_keys($recommended_ids))->get();
        $total_count = array_key_first($recommended_ids) ? $recommended_ids[array_key_first($recommended_ids)]['total_count'] : 0;
        foreach ($recommended as $user) {
            $user->mutual_count = $recommended_ids[$user->id]['mutual_count'];
        }
        return new UserCollection($recommended, $total_count);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFriendToGroup(Request $request) {
        $user = User::find($request->userId);
        if (Auth::user()->isFriendWith($user)) {
            Auth::user()->groupFriend($user, $request->group);
            return response()->json(['message' => 'Вы добавили пользователя в группу'], 200);
        } else {
            return response()->json(['message' => 'Данный пользователь не в ваших друзьях'], 422);
        }
    }

    /**
     * @param $group
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFriendFromGroup($group, $userId) {
        $user = User::where('id', $userId)->first();
        if (!Auth::user()->isFriendWith($user)) {
            return response()->json(['message' => 'Данный пользователь не в ваших друзьях'], 422);
        }

        Auth::user()->ungroupFriend($user, $group);
        return response()->json(['message' => 'Вы удалили пользователя из группы'], 200);
    }

    /**
     * @param $group
     * @return UserCollection
     */
    public function getFriendsFromGroup($group) {
        $friend_ids = Auth::user()->getAllFriendships($group)->pluck('friend_id');
        $friends = User::whereIn('id', $friend_ids)->get();
        return new UserCollection($friends);
    }

    /**
     * @param Request $request
     * @return NotificationCollection
     */
    public function notifications(Request $request) {
        $notifications = Auth::user()->unreadNotifications()->limit($request->query('limit') ?? 10)->offset($request->query('offset') ?? 0)->get();
        return new NotificationCollection($notifications);
    }

    /**
     * @param MarkNotificationsAsRead $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationsAsRead(MarkNotificationsAsRead $request)
    {
        $affected = Auth::user()->unreadNotifications()->whereIn('id', $request->get('ids'))->update(['read_at' => time()]);
        return response()->json([
            'data' => [
                'affected' => $affected
            ]
        ]);
    }
}
