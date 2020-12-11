
<!--  TODO @tga  Мы можем этот блок кода удалить? -->
<template>
    <div id="communityUserActions" class="bg-white-br20 mb-4 py-3 px-4 d-none d-lg-block">
        <button @click="onSendMessagetoAdminClick" type="button"
                class="btn btn-primary btn-block mx-auto px-3 py-2 text-uppercase text-center rounded-pill">
            Написать сообщение
        </button>

<!--        <button v-if="false" type="button"-->
<!--                @click="onNotificationEnableClick"-->
<!--                class="btn btn-link btn-block w-100 mb-2 text-black-50 text-left">-->
<!--            <i class="far fa-bell mr-2"></i>Включить уведомления</button>-->

<!--        <button v-if="false" type="button"-->
<!--                @click="onFriendInformationClick"-->
<!--                class="d-none btn btn-link btn-block w-100 text-black-50 text-left">-->
<!--            <i class="fas fa-share mr-2"></i>Рассказать друзьям</button>-->

        <MessageForAdminsModal v-if="isShowAdminMessageDialog"
                                 @HideAdminMsgModal="onHideAdminMsgModal"
                                 @SendAdminMessage="handleAdminMessage"
                                 v-bind:community="community"></MessageForAdminsModal>
    </div>
</template>

<script>
import MessageForAdminsModal from './MessageForAdminsModal.vue';

import DialogMixin from '../../mixins/DialogMixin.js';

import PliziCommunity from '../../classes/PliziCommunity.js';

export default {
name : 'CommunityUserActionBlock',
components : {
    MessageForAdminsModal
},
mixins: [DialogMixin ],
props: {
    community: PliziCommunity,
},

data(){
    return {
        isShowAdminMessageDialog: false,
    }
},

methods: {

    onSendMessagetoAdminClick(){
        this.isShowAdminMessageDialog = true;
    },

    async handleAdminMessage(evData){
        window.console.log(evData, `handleAdminMessage`);
        this.onHideAdminMsgModal();

        this.$root.$once('NewChatDialog', (dlgData) => {
            this.sendMessageToAdminChat(dlgData, evData.message);
        });

        let chatName = (this.community.name.length < 16) ? this.community.name : this.community.name.substr(0, 12);
        chatName += ': '+ this.$root.$auth.user.fullName;

        await this.openDialogWithAdmins(chatName,this.community.adminsIds, this.$root.$auth.user.id);

        setTimeout(()=>{
            this.$root.$router.push({ path: '/chats' });
        }, 200);
    },


    async sendMessageToAdminChat( chatData, msgData ){
        const sendData = {
            chatId : chatData.id,
            body : msgData.postText,
            attachments : msgData.attachments,
            event : 'new.message'
        };
        this.$root.$api.sendToChannel(sendData);
    },

    onHideAdminMsgModal(){
        this.isShowAdminMessageDialog = false;
    },

    /**
     * TODO: @TGA позже нижезакоменченое выкинем
     */
    //onMentionClick(){
    //    this.$root.$alert(`Упоминание`, 'bg-info', 3);
    //},
    //
    //onNotificationEnableClick(){
    //    this.$root.$alert(`Включение нотификаций`, 'bg-info', 3);
    //},
    //
    //onFriendInformationClick(){
    //    this.$root.$alert(`Рассказываю друзьям`, 'bg-info', 3);
    //},
}
}
</script>
