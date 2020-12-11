<template>
    <div class="modal show" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true"
         style="display: block; background-color: rgba(0, 0, 0, .7);" @click.stop="hidePersonalMsgModal">
        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">

                <div class="modal-header">
                    <h4 class="modal-title p-2">Новое сообщение</h4>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <button @click.prevent="goToDialogWithFriend()" type="button" class="btn btn-link btn-block text-left p-3 bg-light text-black-50">
                            <IconSpinner v-if="isInRedirecting" /> Перейти к диалогу с {{user.firstName}}
                        </button>
                    </div>

                    <div class="user-friend d-flex">
                        <div class="user-friend-pic mr-3 ">
                            <img class="user-friend-img rounded-circle overflow-hidden" v-bind:src="user.userPic" v-bind:alt="user.firstName" />
                            <span v-if="user.isOnline" class="user-friend-isonline" title="онлайн"></span>
                            <span v-else class="user-friend-isoffline"></span>
                        </div>

                        <div class=" user-friend-body m-0 col-12 pr-5 ">
                            <div class="user-friend-body-top d-flex align-items-end justify-content-between">
                                <h6 class="user-friend-name my-0">{{ user.fullName }}</h6>
                            </div>

                            <div class="user-friend-body-bottom d-flex pr-5">
                                <p class="user-friend-desc p-0 my-0  d-inline">{{user.lastActivity | lastEventTime}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form mt-3">
                        <TextEditor :id="`messageToUserFromHisPage`"
                                    ref="messageToUserFromHisPage"
                                    workMode="chat"
                                    :showAvatar="false"
                                    :dropToDown="true"
                                    :clazz="`row plz-text-editor mb-4 pl-2 h-auto  align-items-start`"
                                    :inModal="true"
                                    @editorPost="startPersonalMessage">
                        </TextEditor>
                    </div>

                    <div class="form-group row mb-0 pt-3 border-top">
                        <div class="col-12">
                            <button type="button" class="btn plz-btn plz-btn-primary" @click.prevent="startPersonalMessage()">Отправить</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import IconSpinner from '../icons/IconSpinner.vue';
import TextEditor from '../common/TextEditor.vue';

import DialogMixin from '../mixins/DialogMixin.js';

import PliziUser from '../classes/PliziUser.js';

export default {
name: 'NewPersonalMessageModal',
props: {
    user: PliziUser
},
components: { IconSpinner, TextEditor },
mixins: [DialogMixin],
data() {
    return {
        isInRedirecting: false
    }
},

methods: {
    hidePersonalMsgModal() {
        this.$emit('HidePersonalMsgModal', {});
    },

    startPersonalMessage(evData){
        if (!evData) {
            evData = this.$refs.messageToUserFromHisPage.getContent();
        }

        if (evData.postText!=='<p></p>' ||  evData.attachments.length>=1){
            this.$emit( 'SendPersonalMessage', {
                message : evData,
                receiverId : this.user.id,
            } );
        }
    },

    async goToDialogWithFriend(){
        this.isInRedirecting = true;
        await this.openDialogWithFriend( this.user );
        await this.$root.$router.push('/chats');
    },
},

mounted(){
    setTimeout(()=>{
        this.$refs.messageToUserFromHisPage.focus();
    }, 100);
}

}
</script>
