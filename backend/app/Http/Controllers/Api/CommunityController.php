<?php

namespace App\Http\Controllers\Api;

use App\Events\CommunitySubscribe;
use App\Events\CommunityUnsubscribe;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Community as CommunityRequest;
use App\Http\Requests\Community\CreateCommunity;
use App\Http\Requests\Community\CreateCommunityRequest;
use App\Http\Requests\Community\UploadFileRequest;
use App\Http\Resources\Community\CommunityCollection;
use App\Http\Resources\Community\Community as CommunityResource;
use App\Http\Resources\Community\CommunityRequests;
use App\Http\Resources\Community\CommunityUserCollection;
use App\Http\Resources\User\Image;
use App\Http\Resources\Video\VideoCollection;
use App\Models\Community;
use App\Models\CommunityAttachment;
use App\Models\CommunityHeader;
use App\Models\CommunityMember;
use App\Models\CommunityRequest as CommunityRequestModel;
use App\Models\CommunityTheme;
use App\Models\Video;
use App\Services\CommunityService;
use App\Services\S3UploadService;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Post\AttachmentsCollection;
use JWTAuth;

class CommunityController extends Controller
{

    /**
     * @var Community
     */
    public $community;

    /**
     * @var CommunityService
     */
    public $communityService;

    /**
     * @var S3UploadService
     */
    public $uploadService;

    /**
     * CommunityController constructor.
     * @param Community $community
     * @param CommunityService $communityService
     * @param S3UploadService $uploadService
     */
    public function __construct(Community $community, CommunityService $communityService, S3UploadService $uploadService)
    {
        $this->community = $community;
        $this->communityService = $communityService;
        $this->uploadService = $uploadService;
    }

    /**
     * @return CommunityCollection
     */
    public function index(Request $request) {
        /**
         * TODO: Нужно будет что-то придумать с оптимизацией (дернормализовать таблицы или.... пока не ясно)
         */
        /** @var Community|Builder $query */
        $query = Community::with('role', 'members', 'avatar', 'city', 'theme', 'city.region', 'city.country')
            ->withCount('members');

        $search = $request->search;
        if (mb_strlen($search) >= 3) {
            $query->search($search);
        }

        $list = $request->list;
        $list = in_array($list, ['popular', 'my', 'owner'])
            ? $list
            : 'popular';

        switch ($list) {
            case 'my':
                $query->onlyMy();
                break;
            case 'owner':
                $query->owner();
                break;
            default:
                $query->showedForAll();
                break;
        }

        if (auth()->guest()) {
            try {
                JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
            }
        }

        if (Auth::guest()) {
            $query->limit(10);
        } else {
            $query
                ->limit($request->query('limit', 10))
                ->offset($request->query('offset', 0));
        }
        $communities = $query->get();

        $communities->each(static function($community) {
            $community->load('onlyFiveMembers');
        });

        return new CommunityCollection($communities);
    }

    /**
     * @param int $id
     * @return CommunityResource|JsonResponse
     */
    public function get(int $id) {
        $community = Community::with(['users' => static function($u) {
            $u->limit(5);
        }, 'users.profile', 'members', 'avatar', 'city', 'headerImage', 'supers'])
            ->withCount('members')
            ->find($id);
        if($community) {
            return new CommunityResource($community);
        }
        return response()->json(['message' => 'Сообщество не найдено'], 404);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return CommunityUserCollection|JsonResponse
     */
    public function members(Request $request, int $id)
    {
        $role = $request->query('role');
        $community = Community::with([
            'users' => static function ($query) use ($role, $request) {
                if ($role) {
                    $query->wherePivot('role', $role);
                }
                /**
                 * TODO show or not auth user in list?
                 */
                $query
                    ->where('id', '!=', auth()->user()->id)
                    ->limit($request->query('limit', 10))
                    ->offset($request->query('offset', 0));
            }
        ])
            ->showedForAll()
            ->find($id);
        if ($community) {
            return new CommunityUserCollection($community->users, $community->role);
        }
        return response()->json(['message' => 'Сообщество не найдено'], 404);
    }

    /**
     * @param CreateCommunity $request
     * @return CommunityResource
     */
    public function store(CreateCommunity $request) {
        return new CommunityResource($this->communityService->createCommunity($request));
    }

    /**
     * @param CommunityRequest $request
     * @param int $id
     * @return CommunityResource|JsonResponse
     */
    public function update(CommunityRequest $request, int $id) {
        $community = Community::find($id);
        if($community) {
            if($community->authors->contains(auth()->user()->id)) {
                return new CommunityResource($this->communityService->updateCommunity($request, $community));
            }
            return response()->json(['message' => 'Недостаточно прав для редактирования данного сообщества'], 403);
        }
        return response()->json(['message' => 'Сообщество не найдено'], 404);
    }

    /**
     * @param $community
     * @return JsonResponse
     */
    private function subscribeSuccessResponse($community)
    {
        event(new CommunitySubscribe($community->id, auth()->user()->id));
        return response()->json([
            'data' => [
                'message' => 'Вы были успешно добавлены в сообщество',
                'id' => $community->id
            ]
        ], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function subscribe(int $id) {
        $community = Community::find($id);
        if($community) {
            if (!$community->role) {
                $community->users()->attach(auth()->user()->id, ['role' => Community::ROLE_USER, 'created_at' => time(), 'updated_at' => time()]);
                return $this->subscribeSuccessResponse($community);
            }
            if ($community->role->role === Community::ROLE_GUEST) {
                CommunityMember::where([
                    'user_id' => auth()->id(),
                    'community_id' => $community->id,
                ])->update([
                        'role' => Community::ROLE_USER,
                    ]);
                return $this->subscribeSuccessResponse($community);
            }

            return response()->json(['message' => 'Вы уже являетесь участником данного сообщества'], 422);
        }
        return response()->json(['message' => 'Сообщество не найдено'], 404);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function unsubscribe(int $id) {
        $community = Community::find($id);
        if($community) {
            if($community->users->contains(auth()->user()->id)) {
                $community->users()->detach(auth()->user()->id);
                event(new CommunityUnsubscribe($community->id, auth()->user()->id));
                return response()->json([
                    'data' => [
                        'message' => 'Вы успешно отписались из данного сообщества',
                        'id' => $community->id
                    ]
                ], 200);
            }

            return response()->json(['message' => 'Вы не являетесь участником данного сообщества'], 422);
        }
        return response()->json(['message' => 'Сообщество не найдено'], 404);
    }

    /**
     * @param UploadFileRequest $request
     * @return AttachmentsCollection
     * @throws Exception
     */
    public function uploadAvatar(UploadFileRequest $request) {
        $uploaded = $this->uploadService->singleUpload('community/attachments', $request->file('file'), 'public', [
            'normal' => [
                'size' => 600,
            ],
            'medium' => [
                'size' => 250,
            ],
            'thumb' => [
                'size' => [80, 80],
            ],
        ]);

        $community_id = request()->input('id');
        $uploaded['community_id'] = $community_id;
        $attachment = CommunityAttachment::updateOrCreate(['community_id' => $community_id], $uploaded);
        return new Image($attachment);
    }

    public function uploadHeaderImage(UploadFileRequest $request)
    {
        $uploaded = $this->uploadService->singleUpload('community/headers', $request->file('file'), 'public', [
            'normal' => [
                'size' => [1145, 210],
            ],
            'medium' => [
                'size' => 100,
            ],
            'thumb' => [
                'size' => [228, 42],
            ],
        ]);

        $community_id = request()->input('id');
        $uploaded['community_id'] = $community_id;
        $attachment = CommunityHeader::updateOrCreate(['community_id' => $community_id], $uploaded);
        return new Image($attachment);
    }

    public function themeList()
    {
        return response()->json([
            'data' => CommunityTheme::getTree(),
        ]);
    }

    /**
     * @param Request $request
     * @return CommunityCollection
     */
    public function listFavorite(Request $request)
    {
        $community_ids = (new Community())->getFavariteIdList(null, $request->query('limit', 5), $request->query('offset', 0));
        $communities = Community::whereIn('id', $community_ids)
            ->with('role', 'members', 'avatar', 'city', 'theme')
            ->withCount('members')
            ->get();
        return new CommunityCollection($communities);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addFavorite(Request $request)
    {
        /** @var Community $community */
        $community = Community::find($request->id);
        if ($community && $community->addToFavotite()) {
            return response()->json(['message' => 'Вы добавили сообщество в избранные'], 200);
        }

        return response()->json(['message' => 'Вы уже добавили сообщество в избранные'], 422);
    }

    /**
     * @param $groupId
     * @return JsonResponse
     */
    public function deleteFavorite($groupId)
    {
        /** @var Community $community */
        $community = Community::find($groupId);
        if ($community && $community->deleteFromFavotite()) {
            return response()->json(['message' => 'Вы удалили сообщество из избранных'], 200);
        }

        return response()->json(['message' => 'Данного сообщества нет у вас в избранных'], 422);
    }

    public function requestCreate(CreateCommunityRequest $request)
    {
        /** @var Community $community */
        $community = $request->community;

        /**
         * @todo What about PRIVATE community
         */
        if ($community->privacy !== Community::PRIVACY_CLOSED) {
            return response()->json([
                'message' => 'Вы не можете создать запрос в это сообщество',
            ], 422);
        }

        if ($community->users()->where([
            'id' => auth()->user()->id
        ])->exists()) {
            return response()->json([
                'message' => 'Вы уже состоите в этом сообществе',
            ], 422);
        }

        if ($community->requests()->where([
            'user_id' => auth()->user()->id,
            'status' => CommunityRequestModel::STATUS_NEW,
        ])->exists()) {
            return response()->json([
                'message' => 'Вы уже подали запрос в это сообщество',
            ], 422);
        }

        if (CommunityRequestModel::create([
            'community_id' => $community->id,
            'description' => $request->description,
        ])) {
            return response()->json([
                'message' => 'Запрос создан',
            ]);
        }

        return response()->json([
            'message' => 'Ошибка создания запроса',
        ], 422);
    }

    public function requestList()
    {
        return new CommunityRequests($this->communityService->requestList());
    }

    public function requestAccept()
    {
        if ($this->communityService->requestAccept()) {
            return response()->json([
                'message' => 'Запрос принят',
            ]);
        }

        return response()->json([
            'message' => 'Ошибка принятия запроса',
        ], 422);
    }

    public function requestReject()
    {
        if ($this->communityService->requestReject()) {
            return response()->json([
                'message' => 'Запрос отклонен',
            ]);
        }

        return response()->json([
            'message' => 'Ошибка отклонения запроса',
        ], 422);
    }

    /**
     * @param Request $request
     * @return CommunityCollection
     */
    public function recommended(Request $request)
    {
        $list = (new \Domain\Neo4j\Service\CommunityService())
            ->recommended(
                auth()->user()->id,
                $request->query('limit', 5),
                $request->query('offset', 0)
            );
        $communities = Community::whereIn('id', $list->pluck('oid'))
            ->with('avatar')
            ->withCount('members')
            ->get();

        /**
         * @todo add community to full list
         */
        return new CommunityCollection($communities, false);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function adminCreate(Request $request)
    {
        /** @var Community $community */
        $community = $request->community;

        $updated = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $request->userId)
            ->update([
                'role' => Community::ROLE_ADMIN,
            ]);
        if ($updated) {
            return response()->json([
                'message' => 'Роль назначили',
            ]);
        }
        return response()->json([
            'message' => 'Ошибка назначения роли',
        ], 422);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function adminDelete(Request $request)
    {
        /** @var Community $community */
        $community = $request->community;

        $updated = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $request->userId)
            ->update([
                'role' => Community::ROLE_USER,
            ]);
        if ($updated) {
            return response()->json([
                'message' => 'Роль убрана',
            ]);
        }
        return response()->json([
            'message' => 'Ошибка убирания роли',
        ], 422);
    }

    /**
     * @param Request $request
     * @return VideoCollection
     */
    public function videos(Request $request)
    {
        /** @var Community $community */
        $community = $request->community;

        $videos = Video::specialForCommunity($community->id)
            ->limit($request->query('limit', 5))
            ->offset($request->query('offset', 0))
            ->orderBy('id', 'desc')
            ->get();

        return new VideoCollection($videos, true, $community->video_count);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribeNotify(Request $request)
    {
        /** @var Community $community */
        $community = $request->community;

        if ($community->role) {
            CommunityMember::where([
                'user_id' => auth()->id(),
                'community_id' => $community->id,
            ])->update([
                'subscribed' => true,
            ]);
            return response()->json([
                'message' => 'Вы успешно подписались на уведомления',
            ]);
        }

        if ($community->privacy === Community::PRIVACY_OPEN) {
            $community->users()->attach(auth()->user()->id, [
                'role' => Community::ROLE_GUEST,
                'subscribed' => true,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            return response()->json([
                'message' => 'Вы успешно подписались на уведомления',
            ]);
        }

        return response()->json([
            'message' => 'Ошибка подписки на уведомления',
        ], 422);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function unsubscribeNotify(Request $request)
    {
        /** @var Community $community */
        $community = $request->community;

        if (!$community->role) {
            return response()->json([
                'message' => 'Ошибка отписки от уведомлений',
            ], 422);
        }

        if ($community->role->role === Community::ROLE_GUEST) {
            CommunityMember::where([
                'user_id' => auth()->id(),
                'community_id' => $community->id,
            ])->delete();
            return response()->json([
                'message' => 'Вы успешно отписались от уведомлений',
            ]);
        }

        CommunityMember::where([
            'user_id' => auth()->id(),
            'community_id' => $community->id,
        ])->update([
            'subscribed' => false,
        ]);

        return response()->json([
            'message' => 'Вы успешно отписались от уведомлений',
        ]);
    }
}
