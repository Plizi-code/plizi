<template>
    <li class="plizi-invitation-item-user --media m-0 px-4 py-2 w-100" :class="calcClazz()">
        <div class="plizi-invitation-item d-flex w-100 ">
            <router-link :to="`/user-`+invitation.id" tag="div" class="plizi-invitation-item-pic mr-3">
                <img class="plizi-invitation-item-img rounded-circle overflow-hidden"
                     v-bind:src="invitation.userPic" v-bind:alt="invitation.fullName" />

                <span v-if="invitation.isOnline" class="plizi-invitation-item-isonline" title="онлайн"></span>
                <span v-else class="plizi-invitation-item-isoffline"></span>
            </router-link>

            <div class="plizi-invitation-item-body m-0  pr-3 pr-md-5">
                <div class="plizi-invitation-item-top d-flex align-items-start justify-content-between">
                    <router-link :to="`/user-`+invitation.id" tag="h6" class="plizi-invitation-item-name my-0"
                                 :title="invitation.fullName">
                        {{ invitation.fullName }}
                    </router-link>
                </div>

                <div class="plizi-invitation-item-top d-flex align-items-end justify-content-between">
                    <p class="plizi-invitation-item-desc mb-1">хочет к Вам в друзья</p>
                </div>

                <div class="plizi-invitation-item-body-bottom d-flex pr-3 pr-md-5">
                    <p class="plizi-invitation-item-subdesc p-0 my-0  d-inline-block ">
                        <time :datetime="invitation.lastActivity" class="">
                            {{ invitation.lastActivity | lastEventTime }}
                        </time>
                    </p>
                </div>
            </div>

            <div class="align-self-center ml-auto">
                <div class="btn-group">
                    <button class="btn btn-sm rounded" @click="acceptInvitation()">
                        <IconUserPlus style="height: 20px"/>
                    </button>
                    <button class="btn btn-sm ml-2 rounded" @click="declineInvitation()">
                        <IconUserX  style="height: 20px" />
                    </button>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
import IconUserPlus from '../icons/IconUserPlus.vue';
import IconUserX from '../icons/IconUserX.vue';

import PliziInvitation from '../classes/PliziInvitation.js';

export default {
name : 'InvitationItem',
    components: {IconUserX, IconUserPlus},
    props : {
    invitation : {
        type: PliziInvitation,
        required: true
    }
},
data(){
    return {
        isAccepted : false,
        isDeclined : false
    }
},

methods: {
    async acceptInvitation(){
        let response = null;

        try {
            response = await this.$root.$api.$friend.invitationAccept(this.invitation.id);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (response != null) {
            this.isAccepted = true;

            const newFriend = this.invitation.toJSON();
            this.$root.$auth.im.removeInvitation( this.invitation.id );
            this.$root.$auth.frm.onAddAcceptFriendsShip(newFriend);
            this.$root.$auth.friendsIncrease();
            this.$root.$friendsKeyUpdater++;
        }
        else {
            window.console.info(response);
        }
    },


    async declineInvitation(){
        let response = null;

        try {
            response = await this.$root.$api.$friend.invitationDecline(this.invitation.id);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (response != null) {
            this.isDeclined = true;
            this.$root.$auth.im.removeInvitation( this.invitation.id );

            this.$emit('InvitationDecline', {
                invitationId: this.invitation.id
            });
        }
        else {
            window.console.info(response);
        }
    },


    calcClazz(){
        return {
            'bg-success' : this.isAccepted,
            'bg-danger'  : this.isDeclined
        };
    }
}

}
</script>


