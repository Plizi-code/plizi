<template>
    <div id="chatMessagesBody" class="w-100 align-self-stretch position-relative h-100">
        <vue-custom-scrollbar class="chat-messages-scroll py-4" :settings="customScrollbarSettings"
                              @ps-y-reach-start="onScrollToTop"
                              ref="chatMessageScrollBar">
            <div v-if="messagesList  &&  messagesList.length>0" class="d-flex flex-column">

                <Spinner v-if="isMessagesLazyLoad"
                         v-bind:hideText="true">
                </Spinner>

                <div v-if="filteredMessages().length === 0" class="clear-chat-filter text-center">
                    <p v-if="filter.range &&  filter.range.isSameDate">
                        Ничего не найдено за <b>{{ rangeStart | toLongDate }}</b>
                    </p>
                    <p v-if="filter.range &&  !filter.range.isSameDate">
                        Ничего не найдено за период с
                        <b>{{rangeStart | toLongDate}}</b>  по <b>{{rangeEnd | toLongDate}}</b>
                    </p>
                    <p v-if="!filter.range &&  filter.text!==''">
                        Не найдено сообщений с текстом <b>{{ filter.text }}</b>
                    </p>

                    <button class="btn btn-link --btn-filter-clear" @click="clearFilters">
                        Очистить фильтр
                    </button>
                </div>

                <transition-group v-else name="slide-fade" :duration="700">
                    <ChatMessageItem v-for="message in filteredMessages()"
                                     v-if="message.id !== removeMessageID"
                                     @ChatMessagePick="onChatMessagePick"
                                     @ShowForwardMessageModal="onShowForwardMessageModal"
                                     @ShowReplyMessageModal="onShowReplyMessageModal"
                                     @RemoveMessage="onRemoveMessage"
                                     @openChatVideoModal="openChatVideoModal"
                                     v-bind:message="message"
                                     v-bind:isNextIsSamePerson="isNextIsSamePerson(message.id, message.userId)"
                                     v-bind:pickedID="pickedMessageID"
                                     v-bind:dialogID="currentDialog.id"
                                     v-bind:key="'chatMessageItem-'+ message.id+'-'+keyUpdater">
                    </ChatMessageItem>
                </transition-group>
            </div>
            <div v-else class="d-flex flex-column">
                <div v-if="currentDialog" class="alert alert-info mx-3">
                    <p v-if="currentDialog.isPrivate">Сейчас Вы ещё ничего не написали для <b>{{currentDialog.companion.fullName}}</b>.</p>
                    <p v-if="currentDialog.isGroup">Сейчас ещё никто ничего не написал в этом <b>групповом</b> чате.</p>
                </div>
            </div>
        </vue-custom-scrollbar>

        <ResendMessageModal v-if="resendMessageModalShow"
                @HideMessageResendModal="onHideMessageResendModal"
                v-bind:pickedMessage="pickedMessage()"
                v-bind:currentDialog="currentDialog"
                v-bind:pickedID="pickedMessageID">
        </ResendMessageModal>

        <ReplyMessageModal v-if="replyMessageModalShow"
                @HideReplyMessageModal="onHideReplyMessageModal"
                v-bind:pickedMessage="pickedMessage()"
                v-bind:currentDialog="currentDialog"
                v-bind:pickedID="pickedMessageID"
                v-bind:attendees="currentDialog.attendees">
        </ReplyMessageModal>

        <ChatVideoModal v-if="chatVideoModalShow"
                @HideChatVideoModal="onHideChatVideoModal"
                v-bind:videoLink="chatVideoModalContent.videoLink"></ChatVideoModal>
    </div>
</template>

<script>
import ChatMessageItem from './ChatMessageItem.vue';

/** @link https://binaryify.github.io/vue-custom-scrollbar/en/#why-custom-scrollbar **/
import vueCustomScrollbar from 'vue-custom-scrollbar';

/** TODO: переименовать в ForwardMessageModal **/
import ResendMessageModal from './ResendMessageModal.vue';
import ReplyMessageModal from './ReplyMessageModal.vue';
import ChatVideoModal from './ChatVideoModal.vue';

import Spinner from '../Spinner.vue';

import PliziMessage from '../../classes/PliziMessage.js';
import PliziMessagesCollection from '../../classes/Collection/PliziMessagesCollection.js';

export default {
name: 'ChatMessages',
components: {
    vueCustomScrollbar,
    Spinner,
    ChatMessageItem,
    ResendMessageModal, ReplyMessageModal,
    ChatVideoModal,
},

props: {
    messagesList: PliziMessagesCollection,
    currentDialog: Object,
    filter: Object,
    keyUpdater: Number,
    isMessagesLazyLoad: Boolean,
    isCanLoadMoreMessages: Boolean
},

data() {
    return {
        pickedMessageID: 'unknown',
        replyMessageID:  'unknown',
        removeMessageID: 'unknown',
        previousMsg: null,

        resendMessageModalShow: false,
        replyMessageModalShow: false,

        customScrollbarSettings: {
            maxScrollbarLength: 60,
            suppressScrollX: true, // rm scroll x
            wheelPropagation: false
        },

        chatVideoModalShow: false,
        chatVideoModalContent: {
            videoLink: null,
        },

        rangeStart : null,
        rangeEnd : null
    }
},


methods: {
    filteredMessages() {
        //window.console.log( this.messagesList.last.toJSON(), `this.messagesList.last` );

        // без фильтра
        if (''===this.filter.text  &&  this.filter.range===null)
            return this.messagesList.asArray();
            //return this.messagesList.asArray().slice();

        if (this.filter.range && this.filter.range.start && this.filter.range.end) {
            this.rangeStart = this.filter.range.start;
            this.rangeEnd = this.filter.range.end;
        }

        // с фильтром
        return this.messagesList.filter(this.filter).slice();
    },

    onScrollToTop(){
        this.$emit( 'ScrollToTop', {
            chatId: this.currentDialog.id,
            offset: this.messagesList.size,
            limit: 10
        });
    },

    pickedMessage() {
        let lMsg = this.messagesList.get(this.pickedMessageID);

        if (lMsg) {
            lMsg = new PliziMessage(lMsg);
        }
        else {
            window.console.warn(this.pickedMessageID + ` не найден`);
        }

        return lMsg;
    },

    onChatMessagePick(evData){
        this.pickedMessageID = evData.messageID;
    },

    openChatVideoModal(evData) {
        if (evData.videoLink) {
            this.chatVideoModalShow = true;
            this.chatVideoModalContent.videoLink = evData.videoLink;
        }
    },

    onRemoveMessage(evData){
        this.removeMessageID = evData.messageID;
        this.removeMessageById(this.removeMessageID);
    },

    async removeMessageById(msgID) {
        //this.messagesList.delete(msgID);
        this.messagesList.delete(msgID);

        this.$root.$emit('removeMessageInDialog', {
            chatId: this.currentDialog.id
        });

        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$chat.messageDelete(msgID);
        } catch (e) {
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (apiResponse) {

        }
    },

    isNextIsSamePerson(itemId, userId) {
        const node = this.messagesList.getNode(itemId);
        if (! node)
            return false;

        if (! node.next)
            return false;

        const nextItem = this.messagesList.get(node.next);
        if (! nextItem)
            return false;

        return (nextItem.userId === userId);
    },

    scrollToEnd() {
        return setTimeout(() => {
            const container = this.$el.querySelector('.ps-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 200);
    },

    clearFilters() {
        this.$emit('ClearFilters');
    },

    onShowForwardMessageModal() {
        this.resendMessageModalShow = true;
    },

    onShowReplyMessageModal() {
        this.replyMessageModalShow = true;
    },

    onHideMessageResendModal(){
        this.resendMessageModalShow = false;
    },

    onHideReplyMessageModal(){
        this.replyMessageModalShow = false;
    },

    onHideChatVideoModal(){
        this.chatVideoModalShow = false;
    }
},

mounted() {
    this.scrollToEnd();
}

}
</script>
