<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3">

                <FriendsListHeader></FriendsListHeader>

                <div class="d-flex flex-wrap align-items-start">
                    <div class="col-12 --order-1 --order-md-0 col-md-7 col-lg-8 col-xl-8 bg-white-br20">

                        <InvitationsList v-if="isDataReady"
                                         v-bind:key="'invitationsList-'+invitationsNumber+'-'+invitationsKeyUpdater"
                                         @InvitationDecline="onInvitationAction"
                                         @InvitationAccept="onInvitationAction"
                                         v-bind:invitations="getInvitations()"
                                         v-bind:invitationsNumber="invitationsNumber"></InvitationsList>
                        <Spinner v-else v-bind:clazz="`d-flex flex-row`"></Spinner>
                    </div>

                    <div class="col-12 col-md-5 col-lg-4 col-xl-4">
                        <PotentialFriends v-if="possibleFriends && possibleFriends.length"
                                          :blockName="`Возможные друзья`"
                                          :friends="shuffle(possibleFriends)"></PotentialFriends>
                        <PotentialFriends v-if="recommendedFriends && recommendedFriends.length"
                                          :blockName="`Рекомендуемые друзья`"
                                          :friends="shuffle(recommendedFriends)"></PotentialFriends>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-xl-1 d-none d-lg-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
import FriendsListMixin from '../mixins/FriendsListMixin.js';
import InvitationsList from '../components/InvitationsList.vue';

export default {
name: 'InvitationsPage',
components: { InvitationsList},
mixins : [FriendsListMixin],
data() {
    return {
        isDataReady : true,
        invitationsKeyUpdater: 1,
    }
},

computed: {
    invitationsNumber() {
        return this.$root.$auth.im.size;
    },
},

methods: {
    getInvitations() {
        return this.$root.$auth.im.asArray();
    },

    onInvitationAction(){
        this.invitationsKeyUpdater += 1;
    }
},

mounted() {
    this.$root.$on( this.$root.$auth.im.updateEventName, ()=>{
        this.invitationsKeyUpdater += 1;

        if (this.$root.$auth.im.size === 0){
            this.$router.push({ path: '/friends' });
        }
    });
},

}
</script>
