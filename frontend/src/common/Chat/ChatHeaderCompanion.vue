<template>
    <div class="d-flex align-items-center --h-100">
        <router-link :to="`/user-`+companion.id" tag="div"
                     class="mr-3 cursor-pointer position-relative">
            <div class="media-pic border rounded-circle ">
                <img :src="companion.userPic" v-bind:alt="companion.fullName" />
            </div>

            <template v-if="isTyper">
                <div class="writing"><span></span><span></span><span></span></div>
            </template>
            <template v-else>
                <span v-if="companion.isOnline" class="user-friend-isonline" :title="companion.fullName + ' онлайн'"></span>
                <span v-else class="user-friend-isoffline"></span>
            </template>
        </router-link>

        <div class="media-body">
            <router-link :to="`/user-`+companion.id" tag="h6"
                         class="chatHeader-title align-self-start mt-2 pb-0 mb-0 pull-left text-body cursor-pointer">
                {{companion.fullName}}
            </router-link>

            <p v-if="companion.lastActivity" class="chatHeader-subtitle p-0 mb-0 mt-1 w-100 d-block">
                {{ companion.lastActivity | lastEventTime }}
            </p>
            <p v-else class="chatHeader-subtitle p-0 mb-0 mt-1 w-100 d-block">
                давно
            </p>
        </div>
    </div>
</template>

<script>
import PliziAttendee from '../../classes/PliziAttendee.js';

export default {
name : 'ChatHeaderCompanion',
props : {
    companion : PliziAttendee,
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
