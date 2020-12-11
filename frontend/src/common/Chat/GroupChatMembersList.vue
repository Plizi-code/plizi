<template>
    <div class="form-group" id="groupChatAttendeesModalMembers">
        <vue-custom-scrollbar
                class="chat-members-scroll py-4" :settings="customScrollbarSettings">
            <div class="d-flex flex-column align-items-start"
                 :key="'attendeesList-'+keyUpdater">
                <ChatAttendeeItem v-for="attItem in attendees"
                                  @RemoveAttendeeFromChat="onRemoveAttendeeFromChat"
                                  v-bind:companion="attItem"
                                  v-bind:meIsChatAdmin="meIsChatAdmin"
                                  v-bind:isCanDelete="isCanDelete"
                                  v-bind:key="'attendeeItem-'+attItem.id+'-'+keyUpdater">
                </ChatAttendeeItem>
            </div>
        </vue-custom-scrollbar>
    </div>
</template>

<script>
    /** @link https://binaryify.github.io/vue-custom-scrollbar/en/#why-custom-scrollbar **/
import VueCustomScrollbar from 'vue-custom-scrollbar';

import ChatAttendeeItem from './ChatAttendeeItem.vue';

export default {
name : 'GroupChatMembersList',
components : { ChatAttendeeItem, VueCustomScrollbar },
props : {
    attendees : Array,
    isCanDelete : Boolean,
    meIsChatAdmin : Boolean,
    keyUpdater : Number,
},

data(){
    return {
        customScrollbarSettings: {
            suppressScrollX: true, // rm scroll x
            wheelPropagation: false
        },
    }
},

methods: {
    onRemoveAttendeeFromChat(evData){
        this.$emit('RemoveAttendeeFromChat', evData);
    }
}
}
</script>
