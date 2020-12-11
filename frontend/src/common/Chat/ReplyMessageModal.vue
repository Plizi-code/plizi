<template>
    <div class="modal" id="resendMessageModal" tabindex="-1" role="dialog" aria-labelledby="resendMessageModal"
          aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
          @click.stop="hideReplyMessageModal">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">

                <div id="resendMessageModalBody" class="modal-body p-4">
                    <h5 class="resend-message-title text-left mb-3">Ответ на сообщение</h5>

                    <div class="form" id="resendMessageModalForm">
                        <div class="form-group mb-4">
                            <ResendMessageItem v-if="pickedMessage"
                                               v-bind:message="msgData"></ResendMessageItem>
                        </div>

                        <div class="form-group">
                            <TextEditor id="forwardMessageEditor"
                                        ref="forwardMessageEditor"
                                        workMode="chat"
                                        :showAvatar="false"
                                        :dropToDown="false"
                                        :clazz="`row plz-text-editor mb-4 px-1 py-4 align-items-start`"
                                        @editorPost="onReplyPost">
                            </TextEditor>
                        </div>
                    </div>

                    <button type="button" class="btn plz-btn plz-btn-primary mt-4" @click.prevent="startReplyMessage()">
                        Ответить
                    </button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import TextEditor from '../TextEditor.vue';

import ResendMessageItem from './ResendMessageItem.vue';

import ChatMixin from '../../mixins/ChatMixin.js';

import PliziMessage from '../../classes/PliziMessage.js';

export default {
name: 'ReplyMessageModal',
components: { ResendMessageItem, TextEditor },
mixins : [ChatMixin],
props: {
    pickedMessage: PliziMessage | null,
    messageID: Number,
    currentDialog: Object,
    attendees: {
        type: Array,
        default: null,
    },
},
data() {
    return {
        msgData : null,
    }
},
methods: {
    startReplyMessage(){
        const msgData = this.$refs.forwardMessageEditor.getContent();

        this.onReplyPost(msgData);
    },

    hideReplyMessageModal() {
        this.$emit('HideReplyMessageModal', {});
    },

    onReplyPost(evData){
        /** @type {string} **/
        let msg = evData.postText.trim();

        msg = (msg === '<p></p>') ? '' : msg;

        const config = {
            chatId : this.currentDialog.id,
            userId : this.pickedMessage.userId // чтобы Reply получился
        };

        const fwdData = {
            chatId: this.currentDialog.id,
            body : msg,
            replyOnMessageId : this.msgData.id,
            forwardFromChatId : this.currentDialog.id,
            attachments : evData.attachments,
            isForward: true
        };

        if (msg !== '') {
            const brExample = `<br/>`;
            msg = msg.replace(/<p><\/p>/g, brExample);
            msg = this.killBrTrail(msg);

            if (msg !== '') {
                this.replyMessageToChat( config, fwdData );
            }
        }
        else { // сообщение пустое - проверяем есть ли аттачи
            if (evData.attachments.length > 0) {
                this.replyMessageToChat( config, fwdData );
            }
        }
    },

    async replyMessageToChat(config, msgData){
        msgData.event = 'new.message';

        this.$root.$api.sendToChannel(msgData);
        this.hideReplyMessageModal();
    },

    /**
     * @deprecated
     * @param config
     * @param msgData
     * @returns {Promise<void>}
     */
    async replyOnMessage( config, msgData ){
        window.console.warn(`replyOnMessage DEPRECATED`);
        return;

        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$chat.messageForward( config, msgData );
        } catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }

        if ( apiResponse ){
            let ownerMessage = this.attendees.find((attendee) => {
                return attendee.id === msgData.userId;
            });

            const eventData = {
                id : apiResponse.data.id,
                userId: ownerMessage.id,
                chatId: msgData.forwardFromChatId,
                firstName: ownerMessage.firstName,
                lastName: ownerMessage.lastName,
                userPic: ownerMessage.userPic,
                sex: ownerMessage.sex,
                body : msgData.body,
                attachments: msgData.attachments,
                isMine: true,
                isRead: false,
                isEdited: false,
                createdAt: Math.floor(Date.now() / 1000),
                updatedAt: Math.floor(Date.now() / 1000),
                replyOn: this.pickedMessage,
                isForward: msgData.isForward,
            };

            this.$root.$emit( 'newMessageInDialog', eventData );
            this.hideReplyMessageModal();
            //this.$emit( 'ChatMessagePick', { messageID: -1 }); // чтобы убрать выпадашку
        }
        else{
            window.console.info( apiResponse );
        }
    },
},

created(){
    this.msgData = this.pickedMessage;
},

mounted(){
    setTimeout(()=>{
        if (this.$refs.forwardMessageEditor) {
            this.$refs.forwardMessageEditor.focus();
        }
    }, 100);
}

}
</script>
