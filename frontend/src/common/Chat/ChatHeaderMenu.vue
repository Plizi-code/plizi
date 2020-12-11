<template>
    <div class="chat-header-action-menu">
        <button class="btn btn-link --plizi-friend-item-settings"
                id="chatHeaderMenu"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <i class="dots-vertical"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-right py-3" aria-labelledby="chatHeaderMenu">
            <div class="nav-item">
                <span class="dropdown-item px-3 py-1 cursor-pointer" @click.prevent="onCreateGroupChatClick">
                    Создать групповой чат
                </span>
            </div>

            <div class="dropdown-divider"></div>

            <div v-if="meIsChatAdmin" class="nav-item">
                <span class="dropdown-item px-3 py-1 cursor-pointer" @click.prevent="onAddAttendeeClick">
                    Добавить собеседника в чат
                </span>
            </div>

            <div v-if="meIsChatAdmin" class="nav-item">
                <span class="dropdown-item px-3 py-1 cursor-pointer" @click.prevent="onRemoveAttendeeClick">
                    Удалить собеседника из чата
                </span>
            </div>

            <div v-if="!meIsChatAdmin" class="nav-item">
                <span class="dropdown-item px-3 py-1 cursor-pointer" @click.prevent="onAttendeeListClick">
                    Собеседники в чате
                </span>
            </div>

            <div v-if="currentDialog.isPrivate  ||  (currentDialog.isGroup  &&  meIsChatAdmin)" class="dropdown-divider"></div>

            <div class="nav-item">
                <span v-if="currentDialog.isPrivate  ||  (currentDialog.isGroup  &&  meIsChatAdmin)" class="dropdown-item px-3 py-1 cursor-pointer" @click.prevent="onRemoveCurrentChatClick">
                    Удалить этот чат
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import ChatAdminMixin from '../../mixins/ChatAdminMixin.js';
import PliziDialog from '../../classes/PliziDialog.js';

export default {
name : 'ChatHeaderMenu',
mixins: [ChatAdminMixin],
props : {
    currentDialog : PliziDialog,
},

methods: {
    onCreateGroupChatClick(){
        this.$emit(`ShowCreateGroupChatModal`, {});
    },

    onRemoveCurrentChatClick(){
        this.$emit(`ShowRemoveCurrentChatModal`, {});
    },

    onAttendeeListClick(){
        this.$emit(`ShowAttendeeListModal`, {});
    },

    onAddAttendeeClick(){
        this.$emit(`ShowAddAttendeeModal`, {});
    },

    onRemoveAttendeeClick(){
        this.$emit(`ShowRemoveAttendeeModal`, {});
    }
}

}
</script>
