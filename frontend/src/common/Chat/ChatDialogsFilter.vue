<template>
    <div class="find-in-dialogs-form d-flex align-items-center w-100 border-bottom py-3">
        <div class="d-flex w-100 align-items-center position-relative px-4">

            <button class="btn-group-chat btn btn-group-chat rounded-circle position-relative p-0 mr-3" @click.stop="onAddDialogClick">
                <IconFriends class="w-50 h-50 " />
            </button>

            <div class="find-in-chat-list  position-relative ">
                <label class="sr-only d-none" for="txtFindInChatList">Поиск</label>
                <input v-model="filterText" type="text"
                       id="txtFindInChatList" ref="txtFindInChatList"
                       class="chat-search-input form-control rounded-pill px-4"
                       @keydown.stop="dialogSearchKeyDownCheck"
                       placeholder="Поиск в собеседниках" />

                <button class="find-in-chat-list-btn btn btn-search h-100 shadow-none"
                        @click="startDialogFilter" type="submit">
                    <IconSearch />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import IconSearch from '../../icons/IconSearch.vue';
import IconFriends from '../../icons/IconFriends.vue';

export default {
name : 'ChatDialogsFilter',
components : { IconSearch, IconFriends },
props : {
},

data(){
    return {
        filterText: ``
    }
},

methods: {
    onAddDialogClick(){
        this.$root.$emit('ChatDialogsNew', {});
    },

    dialogSearchKeyDownCheck(ev){
        if (8===ev.keyCode  ||  13===ev.keyCode  ||  46===ev.keyCode){
            return this.startDialogFilter();
        }
    },

    startDialogFilter(){
        const sText = this.filterText.trim();

        this.$emit('ChatDialogsFilter', {
            text : sText,
        });
    },
}

}
</script>
