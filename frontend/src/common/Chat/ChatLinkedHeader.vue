<template>
    <div id="chatHeader" class="bg-white w-100 border-bottom position-relative" >
        <div class="row  align-items-center justify-content-between  mx-0 py-2">
            <div class="col-auto position-relative pr-0">
                <label class="sr-only d-none" for="txtFindInChat">Поиск</label>
                <input v-model="chatFilterText" id="txtFindInChat" ref="txtFindInChat" type="text"
                       data-change-focus="onFocusSearch"
                       @keydown.stop="chatSearchKeyDownCheck($event)"
                       class="chat-search-input form-control rounded-pill px-4"
                       placeholder="Поиск в чате"/>
                <button class="btn btn-search h-100 shadow-none"
                        type="submit"
                        @click="onClickStartChatFilter()">
                    <IconSearch style="width: 15px; height: 15px;" />
                </button>
            </div>
            <div class="plz-linked-chat-btns d-flex align-items-center justify-content-between pr-3">
<!--                <button class="btn" @click="unPinChat()">-->
<!--                    <IconPin />-->
<!--                </button>-->

                <button class="btn" @click="closeLinkedChat()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import IconSearch from '../../icons/IconSearch.vue';
import IconPin from '../../icons/IconPin.vue';

import PliziDialog from '../../classes/PliziDialog.js';

export default {
name: 'ChatLinkedHeader',
components: {
    IconPin,
    IconSearch,
},
props: {
    currentDialog: {
        type: PliziDialog | null,
        required : true
    }
},

data() {
    return {
        chatFilterText : ``,
        showDatePicker: false,
        dateRange: null,
        pickAttendeesDialogModalShow: false,
        removeDialogModalShow : false
    }
},

computed: {
    /**
     * хак, чтобы не падало когда ещё нет данных
     * @returns {object|PliziAttendee}
     */
    companion(){
        if (this.currentDialog  &&  this.currentDialog.companion) {
            return this.currentDialog.companion;
        }

        return {
            userPic : this.$defaultAvatarPath,
            firstName : `пользователь`,
            fullName : `пользователь`,
            lastActivity: (new Date()).getTime() / 1000
        }
    }
},

methods: {
    unPinChat(){
        window.console.log(this.currentDialog.id, `Unpin chat!`);
    },

    closeLinkedChat(){
        this.$emit('CloseLinkedChat', {
            chatId: this.currentDialog.id
        });
    },

    onShowRemoveCurrentChatModal(){
        this.removeDialogModalShow = true;
    },

    onHideRemoveDialogModal(){
        this.removeDialogModalShow = false;
    },

    onShowAddAttendeeToDialogModal(){
        this.pickAttendeesDialogModalShow = true;
    },

    onHidePickAttendeesDialogModal(){
        this.pickAttendeesDialogModalShow = false;
    },

    chatSearchKeyDownCheck(ev){
        const sText = this.chatFilterText.trim();

        if (8===ev.keyCode  ||  13===ev.keyCode  ||  46===ev.keyCode){
            return this.startChatFilter(sText);
        }
    },

    onClickStartChatFilter(){
        const sText = this.chatFilterText.trim();
        return this.startChatFilter(sText);
    },

    startChatFilter(filterText){
        this.$emit('ChatMessagesFilter', {
            text : filterText,
            range: this.dateRange,
        });
    },

    onFocusSearch() {
        this.showDatePicker = true;
    },

    dateSelected(range) {
        this.dateRange = range;
        this.startChatFilter(null);
    },

    clearFilters() {
        this.chatFilterText = ``;
        this.showDatePicker = false;
        this.$refs.chatDatePicker.clearDateSelected();
    },

},


}
</script>


