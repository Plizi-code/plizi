<template>
    <div class="modal" id="resendMessageModal" tabindex="-1" role="dialog" aria-labelledby="resendMessageModal"
          aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
          @click.stop="hideMessageResendModal">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">

                <div id="resendMessageModalBody" class="modal-body p-4">
                    <h5 class="resend-message-title text-left mb-3">Переслать собщение</h5>

                    <div class="form" id="resendMessageModalForm">
                        <div class="form-group mb-4">
                            <ResendMessageItem v-bind:message="pickedMessage"></ResendMessageItem>
                        </div>

                        <div class="form-group">
                            <multiselect v-model="selectedFriend"
                                         :options="getFriendsCombo"
                                         label="fullName"
                                         track-by="fullName"
                                         :searchable="true"
                                         :close-on-select="true"
                                         :show-labels="false"
                                         :multiple="false"
                                         placeholder="Выберите получателя">
                                <template slot="option" slot-scope="props">
                                    <div class="plz-receivers-item d-flex align-items-center py-1 px-3">

                                        <span class="plz-receiver-userpic text-body">
                                            <img class="plz-short-userpic rounded-circle" :src="props.option.userPic" :alt="props.option.firstName"/>

                                            <span v-if="props.option.isOnline" class="plz-short-isonline" title="в сети"></span>
                                            <span v-else class="plz-short-isoffline" title="не в сети"></span>
                                        </span>

                                        <div class="plz-receiver-title flex align-items-center mr-auto ">
                                            <span class="plz-receiver-name">
                                                {{props.option.fullName}}
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </multiselect>
                        </div>

                        <div class="form-group">
                            <TextEditor id="forwardMessageEditor"
                                        ref="forwardMessageEditor"
                                        workMode="chat"
                                        :showAvatar="false"
                                        :dropToDown="false"
                                        :clazz="`row plz-text-editor mx-0 mb-4 px-0 py-4 align-items-start`"
                                        @editorPost="onTextPost">
                            </TextEditor>
                        </div>
                    </div>

                    <button type="button" :disabled="isBtnSendDisabled" class="btn plz-btn plz-btn-primary mt-4" @click.prevent="startForwardMessage()">
                        Отправить
                    </button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import TextEditor from '../TextEditor.vue';

import ChatMixin from '../../mixins/ChatMixin.js';

import ResendMessageItem from './ResendMessageItem.vue';

import PliziMessage from '../../classes/PliziMessage.js';
import PliziRecipientsCollection from '../../classes/Collection/PliziRecipientsCollection.js';

export default {
name: 'ResendMessageModal',
components: { ResendMessageItem, TextEditor },
mixins : [ChatMixin],
props: {
    pickedMessage: PliziMessage,
    messageID: Number,
    currentDialog: Object
},

data() {
    return {
        /** @var PliziRecipientsCollection **/
        recipients :null,
        msgData : null,
        /** @var PliziRecipient */
        selectedFriend: null,
        isStartSend: false
    }
},

computed: {
    isBtnSendDisabled(){
        if (this.isStartSend)
            return true;

        return this.selectedFriend === null;
    },

    getFriendsCombo(){
        this.recipients = new PliziRecipientsCollection();

        /**
         * TODO Друзья выключены потомучто бэк не создает под них диалоги
         **/
        this.$root.$auth.frm.asArray().map( (frItem) => {
            this.recipients.add(frItem, null);
        });
        this.$root.$auth.dm.asArray().map( (dItem) => {
            this.recipients.add(dItem.companion, dItem.id);
        });

        return this.recipients.asArray();
    }
},

methods: {
    onTextPost(evData){
        /** @type {string} **/
        let msg = evData.postText.trim();

        if (msg !== '') {
            const brExample = `<br/>`;
            msg = msg.replace(/<p><\/p>/g, brExample);
            msg = this.killBrTrail(msg);

            if (msg!==''  &&  msg!=='<p></p>') {
                this.startForwardMessage(evData);
            }
        }
    },

    startForwardMessage(evData){
        this.isStartSend = true;

        const config = {
            chatId : this.selectedFriend.chatId,
            userId : this.selectedFriend.id,
        }

        let fwdData = {};

        if (evData){
            fwdData = {
                toUserId: this.selectedFriend.id,
                chatId : this.selectedFriend.chatId,
                body : evData.postText,
                replyOnMessageId : this.msgData.id,
                forwardFromChatId : this.currentDialog.id,
                attachments : evData.attachments,
                isForward: true
            };
        }
        else {
            const msgData = this.$refs.forwardMessageEditor.getContent();

            fwdData = {
                toUserId: this.selectedFriend.id,
                chatId : this.selectedFriend.chatId,
                body : msgData.postText,
                replyOnMessageId : this.msgData.id,
                forwardFromChatId : this.currentDialog.id,
                attachments : msgData.attachments,
                isForward: true
            };
        }

        this.forwardMessageToChat(config, fwdData);
    },

    hideMessageResendModal() {
        this.$emit('HideMessageResendModal', {});
    },

    async forwardMessageToChat(config, msgData){
        msgData.event = 'new.message';

        //window.console.dir( JSON.parse( JSON.stringify(msgData) ),  `forwardMessageToChat`);

        this.$root.$api.sendToChannel(msgData);
        this.hideMessageResendModal();
    },

    /**
     * @deprecated
     * @param config
     * @param msgData
     * @returns {Promise<void>}
     */
    async forwardChatMessage( config, msgData ){
        window.console.warn(`forwardChatMessage DEPRECATED`);
        return;

        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$chat.messageForward( config, msgData );
        } catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }

        if ( apiResponse ) {
            const eventData = {
                dialogId : apiResponse.data.chatId,
                message : apiResponse.data
            }

            this.$root.$emit( 'newMessageInDialog', eventData );
            this.hideMessageResendModal();
        }
        else {
            window.console.info( apiResponse );
        }
    }
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
