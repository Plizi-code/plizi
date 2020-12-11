<template>
    <div id="chatFooter" class="chat-footer border-top">
        <div class="companion-is-typing pl-2 d-flex w-100 align-items-center overflow-hidden" >
            <span v-show="isTyper">{{typerName}} печатает...</span>
        </div>

        <TextEditor :showAvatar="false"
                    :dropToDown="false"
                    :clazz="`d-flex bg-white w-100  position-relative mt-auto align-items-start px-3 pb-3 pt-1`"
                    workMode="chat"
                    :editorPlaceholder="placeholder"
                    :maximumCharacterLimit="500"
                    :errors="errors"
                    @editorNewHeight="onEditorChangeHeight"
                    @editorKeyDown="onEditorKeyDown"
                    @newAttach="onAddAttachToTextEditor"
                    @editorPost="onTextPost"
                    @onUpdateEditor="onUpdateEditor">
        </TextEditor>
    </div>
</template>

<script>
import TextEditor from '../TextEditor.vue';

import ChatMixin from '../../mixins/ChatMixin.js';
import LinkMixin from '../../mixins/LinkMixin.js';

import PliziDialog from '../../classes/PliziDialog.js';
import PliziUser from '../../classes/PliziUser.js';

export default {
name: 'ChatFooter',
props: {
    currentDialog: {
        type: PliziDialog | null,
        required: true
    }
},
components: { TextEditor },
mixins : [ChatMixin, LinkMixin],
data() {
    return {
        placeholder: 'Написать сообщение...',
        timeout: 0,
        errors: null,
        isTyper: false,
        currentTyper: null,
        lastTypeTime: null,
        typerDelay: 2500
    }
},

computed: {
    typerName(){
        if (this.currentTyper)
            return this.currentTyper.fullName;

        this.isTyper = false;
        return '';
    }
},

methods: {
    onEditorKeyDown(e){
        /** https://css-tricks.com/snippets/javascript/javascript-keycodes/ **/
        const disabledKeys = [8, 9, 13, 16, 17, 18, 20, 27, 32, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 91, 92,
        112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123];

        if (disabledKeys.includes(e.which))
            return;

        if ( ((new Date()).valueOf() - this.lastTypeTime) < this.typerDelay )
            return;

        /** через сокеты отправляем инфу о том, что печатаем **/
        const keyPressData = {
            channel: window.localStorage.getItem('pliziChatChannel'),
            userId: this.$root.$auth.user.id,
            chatId: this.currentDialog.id,
            event: 'user.typing'
        };

        this.$root.$api.sendToChannel(keyPressData);
        this.lastTypeTime = (new Date()).valueOf();
    },

    onAddAttachToTextEditor(evData){
        //window.console.log( evData.attach , `ChatFooter::onAddAttachToTextEditor`);
        //this.onEditorChangeHeight();
    },

    onEditorChangeHeight(evData) {
        this.$emit('chatFooterEditorChangedHeight', {
            changedHeight: evData.newHeight
        });
    },

    onTextPost(evData){
        let msg = evData.postText.trim();

        if (msg !== '' || evData.videoLink) {
            const brExample = `<br/>`;
            msg = msg.replace(/<p><\/p>/g, brExample);
            msg = this.killBrTrail(msg);

            if (msg !== '' || evData.videoLink) {
                this.addMessageToChat( msg, evData.attachments, evData.attachmentsData, evData.videoLink);
            }
            else {
                if (evData.attachments.length > 0) {
                    this.addMessageToChat( '', evData.attachments, evData.attachmentsData, evData.videoLink);
                }
            }
        }
        else { // сообщение пустое - проверяем есть ли аттачи
            if (evData.attachments.length > 0) {
                this.addMessageToChat( '', evData.attachments, evData.attachmentsData );
            }
        }
    },

    onUpdateEditor() {
        this.errors = null;
    },

    onCompanionTyping(evData) {
        this.currentTyper = new PliziUser(evData.user);

        if ( this.$root.$auth.user.id === this.currentTyper.id)
            return;

        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        this.isTyper = true;

        this.typingTimeout = setTimeout(() => {
            this.isTyper = false;
            this.currentTyper = null;
        }, 3000);
    },

    async addMessageToChat( msgText, attachmentsIds, attachmentsFiles, videoLink = null){
        const chatId = (this.currentDialog) ? this.currentDialog.id : 'unknown';

        let sendData = {
            chatId: chatId,
            body: msgText,
            attachments: attachmentsIds ? attachmentsIds : [],
            event: 'new.message',
        };

        if (videoLink) {
            sendData.body += ` ${videoLink}`;
        }

        this.$root.$api.sendToChannel(sendData);
    },
},

mounted(){
    this.$root.$on('userIsTyping', this.onCompanionTyping);

    this.lastTypeTime = (new Date()).valueOf();
}

}
</script>
