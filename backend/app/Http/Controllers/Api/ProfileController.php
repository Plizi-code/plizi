<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Community\CommunityCollection;
use App\Models\User;
use Domain\Pusher\WampServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Resources\User\User as UserResource;
use App\Http\Requests\Profile\Profile as ProfileRequest;
use App\Http\Resources\User\Profile as ProfileResource;

class ProfileController extends Controller
{

    /**
     * @return array
     */
    public function index()
    {
        $channel = $channel = WampServer::channelForUser(Auth::user()->id);
        return ['data' => new UserResource(Auth::user()), 'channel' => $channel];
    }

    /**
     * @param $user
     * @return UserResource
     */
    public function show($user)
    {
        $user = User::with('profile')->find($user);
        if(!$user) {
            throw new NotFoundHttpException();
        }
        return new UserResource($user);
    }

    /**
     * @param Request $request
     * @param $id
     * @return CommunityCollection
     */
    public function userCommunities(Request $request, $id)
    {
        $user = User::with(['communities' => static function($query) use ($request) {
                $query
                    ->limit($request->query('limit', 30))
                    ->offset($request->query('offset', 0));
            }])
            ->find($id);
        return new CommunityCollection($user->communities);
    }

    /**
     * @param ProfileRequest $request
     * @return ProfileResource
     */
    public function patch(ProfileRequest $request)
    {
        $user = User::find(Auth::user()->id);

        if ($request->relationshipUserId) {
            $relationship_id = $user->profile->relationship_id;

            if (!($relationship_id === 1 || $relationship_id === 4 || $relationship_id === 5)) {
                return response()->json([
                    'message' => 'Выберите сначала соответствующее семейное положение.'
                ], 422);
            }
        }

        $user->profile->update(array_filter([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'birthday' => $request->birthday,
            'sex' => $request->sex,
            'geo_city_id' => $request->geoCityId,
        ]));

        if(key_exists('relationshipId', $request->post())) {
            if ($request->relationshipId == 2 || $request->relationshipId == 3) {
                $user->profile->update([
                    'relationship_id' => $request->relationshipId,
                    'relationship_user_id' => null,
                ]);
            } else {
                $user->profile->update([
                    'relationship_id' => $request->relationshipId,
                ]);
            }
        }

        if (key_exists('relationshipUserId', $request->post())) {
            $user->profile->update([
                'relationship_user_id' => $request->relationshipUserId,
            ]);
        }

        $profile = $user->fresh()->profile;
        return new ProfileResource($profile);
    }
}
