<template>
    <div class="message-resend pl-3 ml-3 mt-3">
        <div class="message-user-data  d-flex align-items-center mb-2 ">
            <div class="media-pic border rounded-circle mr-3">
                <img :src="replyOn.userPic" :alt="replyOn.firstName"/>
            </div>
            <div class="media-body">
                <h6 class="chatHeader-title w-75 align-self-start mt-2 pb-0 mb-0 pull-left"
                    style="line-height: 20px;">{{replyOn.firstName}}</h6>
                <p class="chatHeader-subtitle p-0 mb-0 mt-1 w-100 d-block">{{replyOn.createdAt | lastMessageTime}}</p>
            </div>
        </div>
        <p v-html="msgBody"></p>

        <ReplyMessageItemAttachments v-bind:message="replyOn"></ReplyMessageItemAttachments>
    </div>
</template>

<script>
import ReplyMessageItemAttachments from './ChatMessageItemAttachments.vue';
import PliziMessage from '../../classes/PliziMessage.js';

export default {
name : 'ChatMessageItemReplyContent',
props : {
    replyOn : PliziMessage,
},
components: {ReplyMessageItemAttachments},
    computed: {
        detectYoutubeLink() {
            let msg = this.replyOn.body.replace(/<\/?[^>]+>/g, '').trim();
            let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            let match = msg.match(regExp);

            return (match && match[7].length === 11) ? match[7] : false;
        },
        livePreview() {
            if (this.detectYoutubeLink) {
                return `<img src="//img.youtube.com/vi/${this.detectYoutubeLink}/mqdefault.jpg" alt="" />`;
            }
        },
        msgBody(){
            return this.livePreview ? this.livePreview : this.replyOn.body;
        },
    },
}
</script>
