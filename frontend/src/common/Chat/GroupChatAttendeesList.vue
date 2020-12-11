<template>
    <div class="d-flex align-items-center">
        <ChatHeaderAttendeeItem v-for="attItem in currentDialog.attendees"
                                v-bind:attendee="attItem"
                                v-bind:keyUpdater="keyUpdater"
                                v-bind:key="'chatHeaderAttendeeItem-'+attItem.id+'-'+keyUpdater">
        </ChatHeaderAttendeeItem>

    </div>
</template>
<script>
import ChatHeaderAttendeeItem from './ChatHeaderAttendeeItem.vue';


import PliziDialog from '../../classes/PliziDialog.js';

export default {
name : 'GroupChatAttendeesList',
components : { ChatHeaderAttendeeItem },
props : {
    currentDialog : PliziDialog,
    keyUpdater: Number
},
methods: {
    onShowAttendeesModal(){
        this.$emit('ShowAddAttendeeModal', {});
    }
}
}
</script>
