<template>
    <div id="blackList" class="plz-account-settings bg-white-br20 plz-mb20 container-fluid">
        <form class="plz-account-settings-form pb-0 px-3 mb-0">
            <div class="plz-account-settings-header row ">
                <div class="d-flex"><h6>Чёрный список</h6></div>
            </div>

            <div class="plz-account-settings-body">
                <div v-if="isBlacklistDataReady && blockedUsers.length" class="form-group row border-bottom border-top flex-column">
                    <BlackListItem v-for="blItem in blockedUsers.asArray()"
                                   @RemoveFromBlackList="onRemoveFromBlackList"
                                   v-bind:userItem="blItem"
                                   v-bind:key="'blockedUserItem-' + blItem.id">
                    </BlackListItem>
                </div>
                <div v-else class="row">
                    <div class="alert alert-info w-100">
                        Ваш чёрный список пуст
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import BlackListItem from './BlackListItem.vue';
import PliziCollection from '../../classes/PliziCollection.js';

export default {
name: 'BlackListUsers',
components: {
    BlackListItem
},

props: {
    blockedUsers: PliziCollection | null,
    isBlacklistDataReady: Boolean
},

methods: {
    onRemoveFromBlackList(evData){
        this.$emit('RemoveFromBlackList', evData);
    }
},
    mounted() {
        console.log(this.blockedUsers.length);
    },
}
</script>
