<template>
    <div class="modal" id="createGroupChatModal" tabindex="-1"
         role="dialog" aria-labelledby="createGroupChatModal" @click.stop="hideCreateGroupChatModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);">

        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">

                <div id="createGroupChatBody" class="modal-body d-flex flex-column p-4">
                    <h5 class="resend-message-title text-left mb-4">Создание группового чата</h5>

                    <form id="createGroupChatForm" novalidate="novalidate" class="mb-4">

                        <div class="form-group"
                             :class="{ 'has-error': $v.model.chatName.$error, 'has-success': !$v.model.chatName.$invalid }">
                            <label for="chatName" class="">Название чата</label>
                            <input v-model="model.chatName" ref="chatName"
                                   :class="{ 'is-invalid': $v.model.chatName.$error, 'is-valid': !$v.model.chatName.$invalid }"
                                   @blur="$v.model.chatName.$touch()"
                                   type="text" class="form-control" id="chatName"
                                   placeholder="" />

                            <div v-show="$v.model.chatName.$error" class="invalid-feedback">
                                <p v-if="!$v.model.chatName.required" class="text-danger">Укажите название чата</p>
                                <p v-if="!$v.model.chatName.minLength" class="text-danger">
                                    Название чата не может быть короче {{ $v.model.chatName.$params.minLength.min }} символов</p>
                                <p v-if="!$v.model.chatName.maxLength" class="text-danger">
                                    Название чата не может быть длиннее {{ $v.model.chatName.$params.maxLength.max }} символов</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="">Участники чата</label>
                            <multiselect v-model="model.selectedRecipients"
                                         :options="getFriendsCombo"
                                         label="fullName"
                                         track-by="fullName"
                                         :searchable="true"
                                         :close-on-select="true"
                                         :show-labels="false"
                                         :multiple="true"
                                         placeholder="Выберите новых собеседников">

                                <template slot="option" slot-scope="props">
                                    <div class="plz-receivers-item d-flex align-items-center py-2 px-3">

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

                            <div v-show="hasNoAttendees" class="">
                                <p class="text-danger">Выберите собеседников</p>
                            </div>
                        </div>
                    </form>

                    <button type="button" class="btn plz-btn btn-block plz-btn-primary mt-auto w-100"
                            @click.prevent="addAttendeesAndClose()">
                        Создать чат с этими собеседниками
                    </button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import { required, minLength, maxLength } from 'vuelidate/lib/validators';

import PliziDialog from '../../classes/PliziDialog.js';
import PliziRecipientsCollection from '../../classes/Collection/PliziRecipientsCollection.js';

export default {
name: 'CreateGroupChatModal',
components: {  },
props: {
    currentDialog: PliziDialog | Object
},

data() {
    return {
        recipients : (new PliziRecipientsCollection()),
        model : {
            chatName : '',
            selectedRecipients: null
        },
        hasNoAttendees: false
    }
},

validations() {
    return {
        model: {
            chatName: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(20),
            },
        }
    };
},

methods: {
    addAttendeesAndClose(){
        if (!this.checkChatForm())
            return;

        this.addToChatDialog();
    },

    hideCreateGroupChatModal() {
        this.$emit('HideCreateGroupChatModal', {});
    },

    checkChatForm(){
        this.$v.$touch();

        this.hasNoAttendees = false;

        if (! this.model.selectedRecipients) {
            this.hasNoAttendees = true;
            return false;
        }

        if (this.model.selectedRecipients  &&  this.model.selectedRecipients.length===0) {
            this.hasNoAttendees = true;
            return false;
        }

        return (!this.$v.$invalid);
    },

    async addToChatDialog(){
        let apiResponse = null;

        const newAttendees = this.model.selectedRecipients.map( rItem => rItem.id );

        try {
            apiResponse = await this.$root.$api.$chat.dialogOpen(this.model.chatName, newAttendees);
        } catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }

        if ( apiResponse ) {
            apiResponse.lastMessageDT = ((new Date()).valueOf()/1000 ) >>> 0;
            apiResponse.lastMessageText = '<i class="text-black-50">новый групповой чат</ic>';

            this.$root.$emit('NewChatDialog', apiResponse);

            window.localStorage.setItem('pliziActiveDialog', apiResponse.id);

            this.hideCreateGroupChatModal();

            apiResponse.chatId = apiResponse.id;
            this.$emit('SwitchToChat', apiResponse);
        }
    }
},

computed: {
    getFriendsCombo(){
        /** @TGA сначала диалоги - это важно **/
        this.$root.$auth.dm.asArray().map( (dItem) => {
            this.recipients.add(dItem.companion, dItem.id);
        });

        this.$root.$auth.frm.asArray().map( (frItem) => {
            this.recipients.add(frItem, null);
        });

        return this.recipients.asArray();
    }
},

mounted(){
    setTimeout(()=>{
        if (this.$refs.chatName) {
            this.$refs.chatName.focus();
        }
    }, 100);
}


}
</script>
