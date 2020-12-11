<template>
    <li class="plizi-notification-item-user m-0 px-4 py-2">

        <div class="plizi-notification-item d-flex">

            <router-link v-if="notification.isHumanNotification"
                         :to="{name: 'PersonalPage', params: {id: notification.senderId}}"
                         tag="div" class="plizi-notification-item-pic mr-3">
                <img class="plizi-notification-item-img rounded-circle overflow-hidden"
                     v-bind:src="notification.senderPic" v-bind:alt="notification.senderFullName" />
<!--                <div class="plizi-notification-item-pic-status status-like">-->
<!--                    <i class="fa fa-heart"></i>-->
<!--                </div>-->
            </router-link>
            <router-link v-else
                         :to="{name: 'CommunityPage', params: {id: notification.communityId}}"
                         tag="div" class="plizi-notification-item-pic mr-3">
                <img class="plizi-notification-item-img rounded-circle overflow-hidden"
                     v-bind:src="notification.communityPic" v-bind:alt="notification.communityName" />
<!--                <div class="plizi-notification-item-pic-status status-like">-->
<!--                    <i class="fa fa-heart"></i>-->
<!--                </div>-->
            </router-link>

            <div class="plizi-notification-item-body m-0 pr-5">
                <div class="plizi-notification-item-top d-flex align-items-start justify-content-between">
                    <router-link v-if="notification.isHumanNotification"
                                 :to="{name: 'PersonalPage', params: {id: notification.senderId}}"
                                 tag="h6" class="plizi-notification-item-name my-0"
                                 :title="notification.senderFullName">
                        {{ notification.senderFullName }}
                    </router-link>
                    <router-link v-else
                                 :to="{name: 'CommunityPage', params: {id: notification.communityId}}"
                                 tag="h6" class="plizi-notification-item-name my-0"
                                 :title="notification.communityName">
                        {{ notification.communityName }}
                    </router-link>
                </div>

                <div class="plizi-notification-item-top d-flex align-items-end justify-content-between">
                    <p class="plizi-notification-item-desc mb-1">
                         {{notification.notifMessage}}
<!--                        оценила ваше-->
<!--                        <a class="link" href="/">фото</a>-->
                    </p>
                </div>

                <div class="plizi-notification-item-body-bottom">
                    <p class="plizi-notification-item-subdesc p-0 my-0 d-inline-block">
                        <time :datetime="notification.createdAt" class="">
                            {{ notification.createdAt | lastMessageTime }}
                        </time>
                    </p>
                </div>
            </div>

<!--            <div class="align-self-start ml-auto plizi-notification-item-point">-->
<!--                <img src="../images/last-entries/alex.png" alt="picture">-->
<!--            </div>-->
        </div>
    </li>
</template>

<script>
import PliziNotification from '../classes/PliziNotification.js';

export default {
name : 'NotificationItem',
props : {
    notification : PliziNotification,
},
data(){
    return {
        isRead : false
    }
}
}
</script>

