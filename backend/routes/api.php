<?php

use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserSubscribeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API router for your application. These
| router are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::post('/auth/verify', 'Auth\RegisterController@verify')->firstName('verify_registration');
Auth::routes();

Route::get('user/search/{search}', [UserController::class, 'search']);
Route::get('communities/search/{search}', [CommunityController::class, 'index']);

Route::group(['middleware' => ['auth.jwt', 'track.activity']], function () {

    Route::prefix('chat')->group(function(){
        Route::get('dialogs', 'Api\ChatController@dialogs');
        Route::get('messages/{chat_id}', 'Api\ChatController@messages');
        Route::post('dialogs/attendees/append', 'Api\ChatController@appendUserToChat');
        Route::post('dialogs/attendees/remove', 'Api\ChatController@removeUserFromChat');
        Route::post('open', 'Api\ChatController@open');
        Route::post('send', 'Api\ChatController@send');
        Route::post('message/user', 'Api\ChatController@sendToUser');
        Route::post('message/attachments', 'Api\ChatController@uploadAttachments');
        Route::post('open/community', 'Api\ChatController@openWithCommunityAdmins');
        Route::delete('message/{id}', 'Api\ChatController@destroyMessage');
        Route::delete('{id}', 'Api\ChatController@destroyChat');
    });

    Route::get('posts', 'Api\PostController@index');
    Route::get('posts/{post}/likes/users', 'Api\LikeController@getPostUsersLikes');
    Route::get('user/posts', 'Api\PostController@myPosts');
    Route::get('user/news', 'Api\PostController@getNews');
    Route::get('user/{id}/posts', [
        'middleware' => ['privacy.role:view_wall_permissions'],
        'uses' => 'Api\PostController@userPosts'
    ]);
    Route::post('posts/share/wall', 'Api\PostController@addToMyPosts');

    Route::get('posts/{id}', 'Api\PostController@get');
    Route::post('posts', 'Api\PostController@storeByUser');
    Route::post('communities/{community_id}/posts', 'Api\PostController@storeByCommunity');
    Route::post('posts/attachments', 'Api\PostController@uploadAttachments');

    Route::get('user/friendship', 'Api\UserController@getMyFriendsList');
    Route::get('user/friendship/pending', 'Api\UserController@getMyPendingFriendsList');
    Route::get('user/{id}/friendship', ['middleware' => ['privacy.role:view_friends_permissions'], 'uses' => 'Api\UserController@getUserFriendsList']);
    Route::post('user/friendship', 'Api\UserController@sendFriendshipRequest');
    Route::post('user/friendship/accept', 'Api\UserController@acceptFriendshipRequest');
    Route::post('user/friendship/decline', 'Api\UserController@declineFriendshipRequest');
    Route::post('user/friendship/block', 'Api\UserController@blockFriendshipRequest');
    Route::delete('user/friendship/{id}', 'Api\UserController@removeFromFriends');
    Route::get('user/friendship/possible', 'Api\UserController@getPossibleFriends');
    Route::get('user/friendship/recommended', 'Api\UserController@getRecommendedFriends');
    Route::post('user/friendship/group', 'Api\UserController@addFriendToGroup');
    Route::delete('user/friendship/group/{group}/{userId}', 'Api\UserController@deleteFriendFromGroup');
    Route::get('user/friendship/group/{group}', 'Api\UserController@getFriendsFromGroup');

    /**
     * User Resource
     */
    Route::get('user/blacklist', 'Api\UserBlacklistController@index');
    Route::post('user/blacklist', 'Api\UserBlacklistController@store');
    Route::delete('user/blacklist', 'Api\UserBlacklistController@delete');

    Route::get('user/notifications', 'Api\UserController@notifications');
    Route::get('user/{id}/communities', 'Api\ProfileController@userCommunities');
    Route::patch('user', 'Api\ProfileController@patch');
    Route::get('user/videos', 'Api\VideoController@getUserVideo');
    Route::resource('user', 'Api\ProfileController', ['only' => ['index', 'show']]);
    Route::post('user/profile/image', 'Api\ImageUploadController@upload');
    Route::patch('user/privacy', 'Api\UserPrivacySettingController@patch');
    Route::get('user/privacy/roles', 'Api\UserPrivacySettingController@roles');

    Route::post('/user/password/change', 'Auth\ChangePasswordController@changePassword');
    Route::post('/user/email/change', 'Auth\ChangeEmailController@changeEmail');
    Route::patch('user/notifications/mark/read', 'Api\UserController@markNotificationsAsRead');
    Route::get('/user/sessions/active', 'Api\SessionController@index');
    Route::post('/user/sessions/close', 'Api\SessionController@close');
    Route::get('/user/{userId}/photo_albums', 'Api\PhotoAlbumController@indexByUser');

    Route::get('user/follow/list', [UserSubscribeController::class, 'list']);
    Route::middleware(['user.get'])->group(static function() {
        Route::get('user/{userId}/follow/list', [UserSubscribeController::class, 'list']);
        Route::get('user/{userId}/follow', [UserSubscribeController::class, 'exists']);
        Route::post('user/{userId}/follow', [UserSubscribeController::class, 'follow']);
        Route::delete('user/{userId}/follow', [UserSubscribeController::class, 'unfollow']);

        Route::get('user/{userId}/videos', 'Api\VideoController@getUserVideo');
    });

    Route::post('user/images/{imageUpload}', 'Api\LikeController@likeUserImage');
    Route::get('user/images/{imageUpload}/comment', 'Api\CommentController@getCommentUserImage');
    Route::post('user/images/{imageUpload}/comment', 'Api\CommentController@commentUserImage');
    Route::get('/user/{user}/photos', 'Api\ImageUploadController@getUserImages');

    Route::get('/user/{user}/photo-albums', 'Api\PhotoAlbumController@getByUserId');

    /**
     * Communities Resource
     */
    Route::prefix('communities')->group(function(){
        Route::middleware(['community.get', 'community.isHasAccess'])->group(static function() {
            Route::get('{groupId}/videos', [CommunityController::class, 'videos']);

            Route::post('{groupId}/notify', [CommunityController::class, 'subscribeNotify']);
            Route::delete('{groupId}/notify', [CommunityController::class, 'unsubscribeNotify']);

            Route::get('{groupId}/posts', [PostController::class, 'communityPosts']);
        });

        Route::patch('{id}', 'Api\CommunityController@update');
        Route::post('', 'Api\CommunityController@store');
        Route::get('', 'Api\CommunityController@index');
        Route::get('{id}', 'Api\CommunityController@get');
        Route::get('{id}/subscribe', 'Api\CommunityController@subscribe');
        Route::get('{id}/unsubscribe', 'Api\CommunityController@unsubscribe');
        Route::get('{id}/members', 'Api\CommunityController@members');
        Route::post('avatar', [CommunityController::class, 'uploadAvatar']);
        Route::post('header-image', [CommunityController::class, 'uploadHeaderImage']);
        Route::get('themes/list', 'Api\CommunityController@themeList');
        Route::get('recommended/list', [CommunityController::class, 'recommended']);

        Route::get('favorite/list', [CommunityController::class, 'listFavorite']);
        Route::post('favorite/subscribe', [CommunityController::class, 'addFavorite']);
        Route::delete('favorite/unsubscribe/{groupId}', [CommunityController::class, 'deleteFavorite']);

        Route::middleware(['community.get', 'community.getMember'])->group(static function() {
            Route::post('admin/{groupId}/{userId}', [CommunityController::class, 'adminCreate']);
            Route::delete('admin/{groupId}/{userId}', [CommunityController::class, 'adminDelete']);
        });

        Route::middleware(['community.get'])->prefix('requests')->group(static function() {
            Route::post('create/{groupId}', [CommunityController::class, 'requestCreate']);
            Route::middleware(['community.isOwner'])->group(static function() {
                Route::get('list/{groupId}', [CommunityController::class, 'requestList']);
                Route::patch('accept/{groupId}/{id}', [CommunityController::class, 'requestAccept']);
                Route::patch('reject/{groupId}/{id}', [CommunityController::class, 'requestReject']);
            });
        });
    });

    Route::prefix('posts')->group(function () {
        Route::delete('{post}', 'Api\PostController@delete');
        Route::get('{post}/restore', 'Api\PostController@restore');
        Route::post('rate', 'Api\LikeController@likePost');
        Route::post('{post}/update', 'Api\PostController@update');
        Route::delete('{post}/attachment/{postAttachment}', 'Api\PostController@deleteImage');
        Route::post('{post}/image/like', 'Api\LikeController@likePostImage');
        Route::post('/view', 'Api\PostController@markViewed');
        Route::get('{id}/viewed', 'Api\PostController@getViewedUsers');
        Route::get('attachments/{postAttachment}/comment', 'Api\CommentController@getCommentPostImage');
        Route::post('attachments/{postAttachment}/comment', 'Api\CommentController@commentPostImage');
    });

    Route::resource('/videos', 'Api\VideoController');
    Route::prefix('videos')->group(function () {

    });

    Route::prefix('comment')->group(function () {
        Route::post('post', 'Api\CommentController@commentPost');
        Route::get('post/{id}', 'Api\CommentController@getPostComments');
        Route::delete('{id}', 'Api\CommentController@destroyComment');
        Route::patch('{comment}', 'Api\CommentController@update');
        Route::post('attachments', 'Api\CommentController@uploadAttachments');
        Route::post('{comment}/like', 'Api\LikeController@likeComment');
    });

    Route::prefix('photo-albums')->group(function () {
        Route::get('/', 'Api\PhotoAlbumController@index');
        Route::get('/community/{community}', 'Api\PhotoAlbumController@indexByCommunity');
        Route::post('/', 'Api\PhotoAlbumController@store');
        Route::post('{id}', 'Api\PhotoAlbumController@update');
        Route::delete('{id}', 'Api\PhotoAlbumController@destroy');

        Route::get('{photoAlbum}', 'Api\PhotoAlbumController@show');
        Route::post('{id}/photos', 'Api\PhotoAlbumController@storePhotoInAlbum');
    });

    Route::prefix('photos')->group(function () {
        Route::delete('{imageUpload}', 'Api\ImageUploadController@delete');
    });

    /**
     * Geo data Resource
     */
    Route::get('city/search', 'Api\GeoController@search');

    Route::post('/neo/user', 'Api\NeoController@create');
});

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::post('sociallogin/{provider}', 'Auth\LoginController@socialLogin');

