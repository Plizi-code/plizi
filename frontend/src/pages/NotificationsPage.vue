<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-lg-9 col-xl-10 ">
                <div class="row">
                    <div class="offset-2 col-8 bg-white-br20 p-4">
                        <div v-if="isNotificationsReady" class="plizi-notifications-list">
                            <ul v-if="notificationsList  &&  notificationsList.length>0" class="list-unstyled mb-0">
                                <NotificationItem v-for="notifItem in notificationsList"
                                                  v-bind:notification="notifItem"
                                                  v-bind:key="notifItem.id">
                                </NotificationItem>
                            </ul>
                            <div v-else class="">
                                <div class="alert alert-info">
                                    Пока нет никаких нотификаций
                                </div>
                            </div>
                        </div>
                        <Spinner v-else v-bind:clazz="`d-flex flex-row`"></Spinner>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import FavoriteFriends from '../common/FavoriteFriends.vue';
import Spinner from '../common/Spinner.vue';
import NotificationItem from '../common/NotificationItem.vue';

export default {
name: 'NotificationsPage',
components: {
    AccountToolbarLeft, FavoriteFriends,
    Spinner, NotificationItem
},
data() {
    return {
        isNotificationsReady : true
    }
},

computed: {
    notificationsList(){
        return this.$root.$auth.nm.asArray();
    }
},

methods : {
    async markNotificationsAsRead(){
        window.console.log(`markNotificationsAsRead`);
        const idsList = this.$root.$auth.nm.idsList;

        if (!idsList)
            return;

        if (idsList.length <= 0)
            return;

        window.console.log(`markNotificationsAsRead 22`);
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$notifications.markAsRead(idsList);
        }
        catch (e){
            window.console.warn(e.detailMessage  || e);
        }
    }
},

async mounted(){
    await this.$root.$auth.nm.load();
    this.markNotificationsAsRead();
}
}
</script>
