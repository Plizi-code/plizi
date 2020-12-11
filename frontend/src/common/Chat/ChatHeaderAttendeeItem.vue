<template>
    <div class="">
        <router-link :to="`/user-`+attendee.id" tag="div"
                     :title="attendee.fullName"
                     class="mr-3 cursor-pointer position-relative">
            <span v-if="attendee.isAdmin" class="plz-chat-attendee-admin " title="администратор чата">
                <i class="fas fa-star"></i>
            </span>
            <div class="media-pic border rounded-circle">
                <img :src="attendee.userPic" :alt="attendee.fullName" />
            </div>

            <template v-if="isTyper">
                <div class="writing"><span></span><span></span><span></span></div>
            </template>
            <template v-else>
                <span v-if="attendee.isOnline" class="user-friend-isonline" :title="attendee.fullName + ' онлайн'"></span>
                <span v-else class="user-friend-isoffline"></span>
            </template>
        </router-link>
    </div>
</template>

<script>
import PliziAttendee from '../../classes/PliziAttendee.js';

export default {
name : 'ChatHeaderAttendeeItem',
props : {
    attendee : PliziAttendee,
    keyUpdater : Number
},

data(){
    return {
        typingTimeout: null,
        isTyper: false
    }
},

methods : {

    /**
     * @param {Object} evData
     */
    onCompanionTyping(evData) {
        if (this.attendee.id !== evData.user.id)
            return;

        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        this.isTyper = true;

        this.typingTimeout = setTimeout(() => {
            this.isTyper = false;
        }, 3000);
    }
},

mounted(){
    this.$root.$on('userIsTyping', this.onCompanionTyping);
}
}
</script>
