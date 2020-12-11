<template>
    <div class="modal" id="removeCurrentDialogModal" tabindex="-1" role="dialog" aria-labelledby="removeCurrentDialogModal"
          aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
          @click.stop="hideRemoveCurrentDialogModal">

        <div class="modal-dialog modal-dialog-centered justify-content-center" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">

                <div id="resendMessageModalBody" class="modal-body p-4">
                    <h5 class="resend-message-title text-left mb-3">
                        <span v-if="currentDialog.isGroup" class="">Удалить групповой чат с</span>
                        <span v-else class="">Удалить приватный чат с</span>
                    </h5>

                    <div class="form" id="resendMessageModalForm">
                        <div class="form-group">
                            <div class="d-flex flex-column align-items-start --h-100">
                                <ChatHeaderCompanion v-for="attItem in currentDialog.attendees"
                                                        v-bind:companion="attItem"
                                                        v-bind:key="attItem.id">
                                </ChatHeaderCompanion>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn plz-btn plz-btn-primary mt-4" @click.prevent="startRemoveDialog()">
                        Да, удалить этот чат
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import ChatHeaderCompanion from './ChatHeaderCompanion.vue';
import ChatHeaderAttendeeItem from './ChatHeaderAttendeeItem.vue';

export default {
name: 'RemoveCurrentDialogModal',
components: { ChatHeaderCompanion, ChatHeaderAttendeeItem },
props: {
    currentDialog: Object
},

methods: {
    startRemoveDialog(){
        this.removeChat();
    },

    hideRemoveCurrentDialogModal() {
        this.$emit('HideRemoveDialogModal', {});
    },

    async removeChat( ){
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$chat.dialogRemove( this.currentDialog.id );
        }
        catch (e) {
            window.console.warn( e.detailMessage );
            throw e;
        }

        if ( apiResponse ) {
            this.$root.$emit( 'RemoveChatDialog', { chatId : this.currentDialog.id } );
            this.hideRemoveCurrentDialogModal();
        }
    }
},

}
</script>
