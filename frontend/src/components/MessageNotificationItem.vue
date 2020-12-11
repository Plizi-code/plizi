<template>
    <li class="chat-list-user media m-0 px-4 py-2">

        <div class="user-friend d-flex col-12" @click="switchToChat()">
            <div class="user-friend-pic mr-3 ">
                <img class="user-friend-img rounded-circle overflow-hidden" :src="companionPic" alt="" />
                <span v-if="message.isOnline" class="user-friend-isonline" title="онлайн"></span>
                <span v-else class="user-friend-isoffline"></span>
            </div>

            <div class=" user-friend-body m-0 col-12 pr-5 ">
                <div class="user-friend-body-top d-flex align-items-end justify-content-between">
                    <h6 class="user-friend-name my-0">{{ companionName }}</h6>
                    <time :datetime="message.lastMessageDT" class="">
                        {{ message.lastMessageDT | lastMessageTime }}
                    </time>
                </div>

                <div class="user-friend-body-bottom d-flex pr-5">
                    <p class="user-friend-desc p-0 my-0  d-inline ">
                        {{message.lastMessageText}}
                    </p>
                </div>
            </div>
        </div>

    </li>
</template>

<script>
import IconCheckedDouble from '../icons/IconCheckedDouble.vue';

export default {
name: 'MessageNotificationItem',
components: { IconCheckedDouble},
props: {
    message: Object
},
data() {
    return {}
},

methods: {
    switchToChat() {
        this.$root.$emit('switchToChat', {messageID: this.message.id});
    }
},

computed : {
    companionPic(){
        let res = this.$defaultAvatarPath;

        if (this.message  &&  this.message.attendees  &&  this.message.attendees[0]  &&  this.message.attendees[0].userPic  &&  !!this.message.attendees[0].userPic) {
            res = this.message.attendees[0].userPic;
        }

        // TODO: @TGA выпилить когда перестанут сеять в БД lorempixel.com
        const url = document.createElement('a');
        url.href = res;

        if (`lorempixel.com` === url.hostname.toLowerCase()) {
            res = this.$defaultAvatarPath;
        }

        return res;
    },

    companionName(){
        let res = `Кто-то неизвестный`;

        if (this.message  &&  this.message.attendees  &&  this.message.attendees[0]  &&  this.message.attendees[0].firstName  &&  !!this.message.attendees[0].firstName) {
            res = this.message.attendees[0].firstName;
        }

        return res;
    }
}

}
</script>
