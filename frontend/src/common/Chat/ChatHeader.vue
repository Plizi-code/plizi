<template>
    <div id="chatHeader" class="bg-white w-100 border-bottom d-flex justify-content-between">
            <div class="col-11 col-md-6 d-flex">
                <ChatHeaderAttendeePlus v-if="meIsChatAdmin"
                                        @ShowAddAttendeeModal="onShowAttendeesModal"></ChatHeaderAttendeePlus>
                <vue-custom-scrollbar
                        class="plz-latest-entries-list d-flex justify-content-between justify-content-sm-start pb-3"
                        :settings="customScrollbarSettings">

                    <ChatHeaderCompanion v-if="currentDialog.isPrivate"
                         v-bind:companion="companion"></ChatHeaderCompanion>

                    <GroupChatAttendeesList v-if="currentDialog.isGroup"
                                          v-bind:key="'groupChatAttendeesList'+attendeeKeyUpdater"
                                          v-bind:currentDialog="currentDialog"
                                          v-bind:keyUpdater="attendeeKeyUpdater"
                                          @ShowAddAttendeeModal="onShowAttendeesModal"
                                          ref="groupChatAttendeesList"></GroupChatAttendeesList>
                </vue-custom-scrollbar>
            </div>

            <div class="col-1 col-md-6 d-flex p-0 px-md-3">
                <div class="d-flex align-items-center justify-content-end w-100">
                    <div class="form-row align-items-center justify-content-end flex-nowrap">
                        <div class="col-auto d-none d-md-block position-relative">
                            <label class="sr-only d-none" for="txtFindInChat">Поиск</label>
                            <input v-model="chatFilterText" id="txtFindInChat" ref="txtFindInChat" type="text"
                                   @focus="onFocusSearch"
                                   @keydown.stop="chatSearchKeyDownCheck($event)"
                                   class="chat-search-input form-control rounded-pill px-4"
                                   placeholder="Поиск в чате"/>
                            <button class="btn btn-search h-100 shadow-none"
                                    type="submit"
                                    @click="onClickStartChatFilter()">
                                <IconSearch style="width: 15px; height: 15px;" />
                            </button>
                        </div>
                        <div v-if="showDatePicker" class="col-auto">
                            <ChatDatePicker @dateSelected="dateSelected" ref="chatDatePicker"></ChatDatePicker>
                            <button class="btn bg-transparent shadow-none" @click="clearFilters">
                                <i class="far fa-times-circle"></i>
                            </button>
                        </div>

                        <div class="col-md-auto">
                            <ChatHeaderMenu
                                v-bind:currentDialog="currentDialog"
                                @ShowRemoveCurrentChatModal="onShowRemoveCurrentChatModal"
                                @ShowCreateGroupChatModal="onShowCreateGroupChatModal"
                                @ShowAttendeeListModal="onShowAttendeesModal"
                                @ShowAddAttendeeModal="onShowAttendeesModal"
                                @ShowRemoveAttendeeModal="onShowAttendeesModal"></ChatHeaderMenu>
                        </div>
                    </div>
                </div>
            </div>

        <CreateGroupChatModal v-if="createGroupChatModalShow"
                              @SwitchToChat="onSwitchToChat"
                              @HideCreateGroupChatModal="onHideCreateGroupChatModal"
                              v-bind:currentDialog="currentDialog">
        </CreateGroupChatModal>

        <RemoveCurrentDialogModal v-if="removeDialogModalShow"
            @HideRemoveDialogModal="onHideRemoveDialogModal"
            v-bind:currentDialog="currentDialog">
        </RemoveCurrentDialogModal>

        <GroupChatAttendeesModal v-if="showAttendeesModal"
                    @HideGroupChatAttendeesModal="onHideAttendeesModal"
                    @AddAttendeeToDialog="onChangeAttendeeList"
                    @RemoveAttendeeFromDialog="onChangeAttendeeList"
                    v-bind:currentDialog="currentDialog"
                    v-bind:key="'groupChatAttendeesModal'+attendeeKeyUpdater"
                    ref="groupChatAttendeesModal">
        </GroupChatAttendeesModal>

    </div>
</template>

<script>
import IconSearch from '../../icons/IconSearch.vue';

import ChatDatePicker from './ChatDatePicker.vue';
import ChatHeaderMenu from './ChatHeaderMenu.vue';
import CreateGroupChatModal from './CreateGroupChatModal.vue';
import RemoveCurrentDialogModal from './RemoveCurrentDialogModal.vue';
import GroupChatAttendeesModal from './GroupChatAttendeesModal.vue';
import ChatHeaderAttendeePlus from './ChatHeaderAttendeePlus.vue';

import GroupChatAttendeesList from './GroupChatAttendeesList.vue';
import ChatHeaderCompanion from './ChatHeaderCompanion.vue';

/** @link https://binaryify.github.io/vue-custom-scrollbar/en/#why-custom-scrollbar **/
import vueCustomScrollbar from 'vue-custom-scrollbar';

import PliziDialog from '../../classes/PliziDialog.js';

import ChatAdminMixin from '../../mixins/ChatAdminMixin.js';

export default {
name: 'ChatHeader',
components: {
    GroupChatAttendeesList,
    IconSearch,
    ChatHeaderCompanion,
    ChatDatePicker,
    ChatHeaderMenu,
    CreateGroupChatModal,
    RemoveCurrentDialogModal,
    GroupChatAttendeesModal,
    vueCustomScrollbar,
    ChatHeaderAttendeePlus
},
mixins: [ChatAdminMixin],
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
        createGroupChatModalShow: false,
        removeDialogModalShow : false,
        showAttendeesModal: false,
        attendeeKeyUpdater: 0,
        customScrollbarSettings: {
            maxScrollbarLength: 60,
            suppressScrollY: true, // rm scroll x
            wheelPropagation: false
        },
    }
},

computed: {
    /**
     * хак, чтобы не падало когда ещё нет данных
     * @returns {PliziAttendee|object}
     */
    companion(){
        if (this.currentDialog  &&  this.currentDialog.companion) {
            return this.currentDialog.companion;
        }

        return {
            userPic : this.$defaultAvatarPath,
            firstName : `пользователь`,
            lastActivity: (new Date()).valueOf() / 1000
        }
    }
},

methods: {
    onSwitchToChat(evData){
        this.$emit('SwitchToChat', evData);
    },

    onShowRemoveCurrentChatModal(){
        this.removeDialogModalShow = true;
    },

    onHideRemoveDialogModal(){
        this.removeDialogModalShow = false;
    },

    onShowCreateGroupChatModal(){
        this.createGroupChatModalShow = true;
    },

    onHideCreateGroupChatModal(){
        this.createGroupChatModalShow = false;
    },

    onShowAttendeesModal(){
        this.showAttendeesModal = true;
    },

    onHideAttendeesModal(){
        this.showAttendeesModal = false;
    },

    onChangeAttendeeList(){
        this.attendeeKeyUpdater++;

        if (this.$refs.groupChatAttendeesModal){
            this.$refs.groupChatAttendeesModal.$forceUpdate();
        }
        if (this.$refs.groupChatAttendeesList){
            this.$refs.groupChatAttendeesList.$forceUpdate();
        }
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

created(){
    this.$root.$on('ChatDialogsNew', this.onShowCreateGroupChatModal);
},

beforeDestroy() {
    this.$root.$off('ChatDialogsNew', ()=>{});
}

}
</script>


