<?php

namespace App\Providers;

use App\Events\CommunityCreated;
use App\Events\CommunitySubscribe;
use App\Events\CommunityUnsubscribe;
use App\Events\Followers\AddFollower;
use App\Events\Followers\SubFollower;
use App\Events\ResetPassword;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Listeners\CommunityUsersNotification;
use App\Listeners\Friendships\FriendshipNotification;
use App\Listeners\Friendships\NotifyFriends;
use App\Listeners\PostAuthorsNotification;
use App\Listeners\SendPassword;
use Domain\Neo4j\Listeners\CommunityListener;
use Domain\Neo4j\Listeners\CommunitySubscribeListener;
use Domain\Neo4j\Listeners\CommunityUnsubscribeListener;
use Domain\Neo4j\Listeners\UserRelationsListener;
use Domain\Pusher\Events\ChatActionEvent;
use Domain\Pusher\Events\DestroyMessageEvent;
use Domain\Pusher\Events\NewMessageEvent;
use Domain\Pusher\Events\UserTypingEvent;
use Domain\Pusher\Listeners\ChatActionListener;
use Domain\Pusher\Listeners\NewNotification;
use Domain\Pusher\Listeners\UserUpdateListener;
use Domain\Pusher\Listeners\DestroyMessageNotification;
use Domain\Pusher\Listeners\NewMessageNotification;
use Domain\Pusher\Listeners\UserTypingNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Registered::class => [
            \App\Listeners\SendEmailVerificationNotification::class,
        ],
        ChatActionEvent::class => [
            ChatActionListener::class
        ],
        NewMessageEvent::class => [
            NewMessageNotification::class
        ],
        DestroyMessageEvent::class => [
            DestroyMessageNotification::class
        ],
        UserTypingEvent::class => [
            UserTypingNotification::class
        ],
        UserCreated::class => [
            UserUpdateListener::class,
            \Domain\Neo4j\Listeners\UserUpdateListener::class
        ],
        UserUpdated::class => [
            UserUpdateListener::class
        ],
        CommunityCreated::class => [
            CommunityListener::class,
        ],
        CommunitySubscribe::class => [
            CommunitySubscribeListener::class,
        ],
        CommunityUnsubscribe::class => [
            CommunityUnsubscribeListener::class,
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            NewNotification::class
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
            'SocialiteProviders\Twitter\TwitterExtendSocialite@handle',
            'SocialiteProviders\VKontakte\VKontakteExtendSocialite@handle',
        ],
        'friendships.*' => [
            FriendshipNotification::class,
            UserRelationsListener::class
        ],
        'user.*' => [
            NotifyFriends::class
        ],
        'community.*' => [
            CommunityUsersNotification::class
        ],
        'post.*' => [
            PostAuthorsNotification::class
        ],
        ResetPassword::class => [
            SendPassword::class,
        ],
        AddFollower::class => [
            \App\Listeners\Followers\AddFollower::class
        ],
        SubFollower::class => [
            \App\Listeners\Followers\SubFollower::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
