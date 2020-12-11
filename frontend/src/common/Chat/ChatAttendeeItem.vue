<template>
    <div class="d-flex align-items-start mb-3 w-100 pr-3">
        <div class="mr-3 position-relative">
            <span v-if="companion.isAdmin" class="plz-chat-attendee-admin" title="администратор чата">
                <i class="fas fa-star"></i>
            </span>
            <div class="media-pic border rounded-circle">
                <img :src="companion.userPic" :alt="companion.fullName" />
            </div>

            <template v-if="isTyper">
                <div class="writing"><span></span><span></span><span></span></div>
            </template>
            <template v-else>
                <span v-if="companion.isOnline"
                      class="user-friend-isonline" :title="companion.fullName + ' онлайн'"></span>
                <span v-else class="user-friend-isoffline"></span>
            </template>
        </div>

        <div class="media-body mr-auto w-auto">
            <h6 class="chatHeader-title align-self-start mt-2 pb-0 mb-0 text-body">
                {{companion.fullName}}
            </h6>
            <p class="chatHeader-subtitle p-0 mb-0 mt-1 w-100 d-block">
                {{ companion.lastActivity | lastEventTime }}
            </p>
        </div>

        <div class="align-self-end ml-5 mt-0">
            <button v-if="isCanDelete &&  meIsChatAdmin" type="button" class="btn btn-link border-0" @click.prevent="onRemoveAttendeeClick">
                <IconUserX  style="height: 20px" />
            </button>
        </div>
    </div>
</template>

<script>
import IconUserX from '../../icons/IconUserX.vue';

import PliziAttendee from '../../classes/PliziAttendee.js';

export default {
name : 'ChatAttendeeItem',
components: {IconUserX},
props : {
    companion : PliziAttendee,
    meIsChatAdmin: Boolean,
    isCanDelete: Boolean,
},

data(){
    return {
        typingTimeout: null,
        isTyper: false
    }
},

methods : {
    onRemoveAttendeeClick(){
        this.$emit( 'RemoveAttendeeFromChat', {
            userId : this.companion.id,
            fullName: this.companion.fullName
        });
    },

    onCompanionTyping(evData) {
        if (this.companion.id !== evData.user.id)
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
