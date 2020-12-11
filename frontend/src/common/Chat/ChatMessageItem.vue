<template>
    <div class="w-100 d-flex px-5" @click.prevent="onPickMessage()"
         :class="{ 'checked-message': isPicked }"
         :id="messageID">
        <div class="message-item d-flex w-100 justify-content-start"
                :class="calcMessageItemClass()">

            <label v-if="isPicked" class="radio">
                <input type="checkbox" name="dateTimeId" ref="dateTimeId" id="dateTimeId" checked />
                <span></span>
            </label>

            <div v-if="!isNextIsSamePerson" class="message-user-pic mt-auto">
                <img :src="message.userPic" :alt="message.firstName" class="message-user-img" />
            </div>

            <div class="message-body d-flex">
                <div class="message-text" @click.stop="">
                    <template v-if="typeof livePreview === 'object'">
                        <p v-if="livePreview.text"
                           class="message-text-inner mb-0"
                           v-html="livePreview.text">
                        </p>
                        <div v-if="livePreview.videoLinks"
                           class="message-text-inner mt-2 chat-video"
                           @click.stop="hasYoutubeLinks ? openChatVideoModal() : null">
                            <div v-html="livePreview.videoLinks"></div>
                            <button type="button"
                                    aria-label="Запустить видео"
                                    class="video__button">
                                <IconYoutube/>
                            </button>
                        </div>
                    </template>

                    <div v-else class="message-text-inner mb-0" v-html="livePreview"></div>

                    <ChatMessageItemAttachments
                        v-if="message.isAttachments"
                        v-bind:message="message"></ChatMessageItemAttachments>

                    <ChatMessageItemReplyContent v-if="message.isReply"
                                 v-bind:replyOn="message.replyOn"
                                 v-bind:isForward="message.isForward"></ChatMessageItemReplyContent>
                </div>

                <time v-if="!isNextIsSamePerson" class="message-time mx-2" :datetime="message.createdAt">
                    {{ message.createdAt | lastMessageTime }}
                </time>

                <div class="message-info">
                    <span v-if="message.isMine &&  message.isEdited" class="message-edited">
                        <IconPencilEdit />
                    </span>
                    <!--    :class="{ 'message-readed': message.isRead }"  -->
                    <span v-if="message.isMine" class="message-delivery ml-3" :class="{ 'message-readed': true }">
                        <IconCheckedDouble />
                    </span>
                </div>
            </div>

            <div v-if="isPicked" class="messages-edit-group btn-group bg-white-br20 d-flex overflow-hidden">
                <button class="btn btn-message-share d-flex align-items-center justify-content-center border-right"
                        @click.stop.prevent="onForwardBtnClick()">
                    <IconShare />
                    Переслать
                </button>

                <button v-if="!message.isMine" class="btn btn-message-reply d-flex align-items-center justify-content-center border-right"
                        @click.stop.prevent="onReplyBtnClick()">
                    <i class="far fa-comment-dots mr-2"></i>
                    Ответить
                </button>

                <button v-if="message.isMine" class="btn btn-message-basket d-flex align-items-center justify-content-center"
                        @click.stop.prevent="onRemoveBtnClick()">
                    <IconBasket />
                    Удалить
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import IconPencilEdit from '../../icons/IconPencilEdit.vue';
import IconCheckedDouble from '../../icons/IconCheckedDouble.vue';
import IconShare from '../../icons/IconShare.vue';
import IconBasket from '../../icons/IconBasket.vue';
import IconYoutube from "../../icons/IconYoutube.vue";

import TextEditor from '../TextEditor.vue';

import ChatMessageItemReplyContent from './ChatMessageItemReplyContent.vue';
import ChatMessageItemAttachments from './ChatMessageItemAttachments.vue';

import LinkMixin from '../../mixins/LinkMixin.js';
import PliziMessage from '../../classes/PliziMessage.js';

export default {
name: 'ChatMessageItem',
components: {
    ChatMessageItemAttachments, ChatMessageItemReplyContent,
    TextEditor,
    IconBasket, IconShare, IconPencilEdit, IconCheckedDouble,
    IconYoutube,
},
mixins : [LinkMixin],
props: {
    message : {
        type: PliziMessage,
        required : true
    },
    dialogID: String,
    pickedID: String,
    replyID: String,
    isNextIsSamePerson: Boolean
},

data() {
    return {

    }
},

computed: {
    isPicked(){
        return this.message.id === this.pickedID;
    },

    messageID(){
        return 'message-' + this.message.id;
    },

    currentDialog(){
        return this.$parent.currentDialog;
    },

    detectEmoji() {
        if (this.message.body)
            return this.message.body.includes('<p class="big-emoji">');

        return false;
    },

    hasYoutubeLinks() {
        let str = this.message.body.replace(/<\/?[^>]+>/g, '').trim();

        return this.detectYoutubeLinks(str);
    },

    livePreview() {
        let str = this.message.body.replace(/<\/?[^>]+>/g, '').trim();
        let returnedStr = this.transformStrWithLinks(str);

        return str === returnedStr ? this.message.body : this.transformStrWithLinks(str);
    },
},

methods: {
    calcMessageItemClass(){
        const isNextSame = this.isNextIsSamePerson;

        return {
            'my-message ml-auto flex-row-reverse': this.message.isMine,
            'companion-message ' : !this.message.isMine,
            'compact-message'    : isNextSame,
            'fullsize-message'   : !isNextSame,
            'has-only-one-emoji' : this.detectEmoji &&  this.message.attachments.length===0,
            'youtube-link'       : this.hasYoutubeLinks,
        }
    },

    onForwardBtnClick(){
        this.$emit( 'ShowForwardMessageModal', {
            messageID: this.message.id
        });
    },

    onRemoveBtnClick(){
        this.$emit( 'RemoveMessage', {
            messageID: this.message.id
        });
    },

    onPickMessage(){
        this.$emit( 'ChatMessagePick', {
            messageID: (this.pickedID !== this.message.id) ? this.message.id : 'none',
        });
    },

    onReplyBtnClick(){
        this.$emit( 'ShowReplyMessageModal', {
            messageID: this.message.id,
        });
    },

    openChatVideoModal(){
        this.$emit( 'openChatVideoModal', {
            videoLink: this.detectYoutubeLinks(this.message.body.replace(/<\/?[^>]+>/g, '').trim())[0],
        })
    },
}

}
</script>
