<template>
    <div class="plz-top-watcher-item position-relative d-inline-block">

        <router-link to="/friends" tag="a" class="btn btn-link my-auto text-body btn-sm ">
            <IconFriends />
        </router-link>

        <span v-if="getInvitationsNumber()>0" class="counter-info" id="dropdownMenuFriends"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{getInvitationsNumber()}}
        </span>

        <div  v-if="getInvitationsNumber()>0"
              class="invitations-dropdown dropdown-menu dropdown-menu-right pt-3 pb-0 dropdown-white w-auto"
              aria-labelledby="dropdownMenuFriends">
            <vue-custom-scrollbar class="notifications-likes-scroll"
                                  :settings="customScrollbarSettings">
                <ul class="list-unstyled mb-0">
                    <InvitationsList v-bind:key="'invitationsList-'+getInvitationsNumber()+'-'+invitationsKeyUpdater"
                                     @InvitationDecline="onInvitationAction"
                                     @InvitationAccept="onInvitationAction"
                                     v-bind:invitations="getInvitations()"
                                     v-bind:invitationsNumber="getInvitationsNumber()"></InvitationsList>
                </ul>
            </vue-custom-scrollbar>

            <div class="invitations-dropdown-footer border-top">
                <router-link to="/invitations" tag="a"
                             class="invitations-link d-block text-center pt-1 pb-3">Посмотреть все</router-link>
            </div>
        </div>

    </div>
</template>

<script>
import IconFriends from '../../icons/IconFriends.vue';
import InvitationItem from '../../components/InvitationItem.vue';
import InvitationsList from '../../components/InvitationsList.vue';
import vueCustomScrollbar from 'vue-custom-scrollbar';

export default {
name : 'NavBarFriends',
components : { IconFriends, InvitationItem, InvitationsList,
                vueCustomScrollbar
},
data(){
    return {
        invitationsKeyUpdater: 1,
        customScrollbarSettings: {
            maxScrollbarLength: 60,
            suppressScrollX: true, // rm scroll x
            wheelPropagation: false
        },
    }
},
methods : {
    onInvitationAction(){
        this.invitationsKeyUpdater += 1;
    },

    updateInvitations(){
        this.$forceUpdate();
    },

    getInvitations(){
        return this.$root.$auth.im.asArray();
    },

    getInvitationsNumber(){
        return this.$root.$auth.im.size;
    },

},

created(){
    this.$root.$on(this.$root.$auth.im.restoreEventName,  this.updateInvitations);
    this.$root.$on(this.$root.$auth.im.loadEventName,  this.updateInvitations);
    this.$root.$on(this.$root.$auth.im.updateEventName,  this.updateInvitations);
}

}
</script>
