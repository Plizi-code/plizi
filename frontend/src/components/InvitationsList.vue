<template>
    <div class="plizi-search-results-list">
        <ul v-if="invitationsNumber>0" class="list-unstyled mb-0">
            <InvitationItem v-for="invItem in invitations"
                            @InvitationDecline="onInvitationDecline"
                            @InvitationAccept="onInvitationAccept"
                            v-bind:invitation="invItem"
                            v-bind:key="invItem.id">
            </InvitationItem>
        </ul>
        <div class="p-3" v-else>
            <div class="alert alert-info text-center mb-0">
                Нет приглашений подружиться.
            </div>
        </div>
    </div>
</template>

<script>
import InvitationItem from './InvitationItem.vue';

export default {
name : 'InvitationsList',
components : { InvitationItem },
props : {
    invitations : Array,
    invitationsNumber : Number,
},
data(){
    return {
        keyUpdater: 1,
    }
},

methods: {
    onInvitationDecline(evData){
        this.$emit('InvitationDecline', evData);
    },
    onInvitationAccept(evData){
        this.$emit('InvitationAccept', evData);
    }
}
}
</script>
