<template>
    <div id="pageWrapper">
        <div v-if="!isAuthorized()" id="guestPageWrapper" class="d-flex flex-column justify-content-center">
            <div class="--container-fluid container px-md-0 my-0 pt-4">

                <GuestNavBar></GuestNavBar>
                <main :id="containerID" role="main"
                      class="container-fluid pb-sm-5 pb-md-5">
                    <transition>
                        <router-view></router-view>
                    </transition>
                </main>
                <GuestFooter></GuestFooter>
            </div>

            <AlertModal v-if="mainModalVisible"
                        v-bind:alertMessage="mainModalMessage"
                        v-bind:alertClass="mainModalClass"
                        v-bind:alertTimeout="mainModalTimeOut"
            ></AlertModal>
        </div>

        <div v-else id="authPageWrapper">
            <AuthNavBar></AuthNavBar>

            <div class="--container-fluid container my-0 container-wide mx-auto">
                <main :id="containerID" role="main"
                      class="container-fluid pb-sm-4 px-0">
                    <transition>
                        <router-view></router-view>
                    </transition>
                </main>
                <AuthFooter v-if=" 'ChatsListPage'!==this.$root.$router.currentRoute.name "></AuthFooter>
            </div>

            <AppNotifications :notifications="notifications"
                               @removeNotification="removeNotification"></AppNotifications>

            <AlertModal v-if="mainModalVisible"
                        v-bind:alertMessage="mainModalMessage"
                        v-bind:alertClass="mainModalClass"
                        v-bind:alertTimeout="mainModalTimeOut"
            ></AlertModal>
        </div>
    </div>

</template>

<script>
import GuestNavBar from './common/GuestNavBar.vue';
import AuthNavBar from './common/AuthNavBar.vue';
import AuthFooter from './common/AuthFooter.vue';
import GuestFooter from './common/GuestFooter.vue';
import AlertModal from './components/AlertModal.vue';
import AppNotifications from './common/AppNotifications.vue';
import NotificationMixin from "./mixins/NotificationMixin.js";

import {PliziAPI} from './classes/PliziAPI.js';
import {PliziAuth} from './classes/PliziAuth.js';
import PliziLastEntriesCollection from "./classes/Collection/PliziLastEntriesCollection.js";


export default {
name: 'App',
components: {
    GuestNavBar, AuthNavBar, AuthFooter, GuestFooter, AlertModal, AppNotifications
},
mixins: [NotificationMixin],
data () {
    return {
        containerID : `contentContainer`, /** @TGA - просто хак, чтобы phpStorm не ругался на одинаковый ID у элемента */
        lastSearchText: ``,

        notifyBlockIsVisible: false,

        mainModalVisible : false,
        mainModalTitle   : '',
        mainModalMessage : '',
        mainModalClass   : '',
        mainModalTimeOut : 0
    }
},

methods: {
    afterSuccessLogin(evData) {
        if (evData.token  &&  (evData.token+'').trim() !== ``) {
            this.$root.$isAuth = true;

            this.$root.$api.token = evData.token;
            this.$root.$api.channel = evData.chatChannel;

            if (evData.redirect) {
                const redirTo = window.localStorage.getItem('pliziRedirectTo');
                if (redirTo &&  redirTo!=='') {
                    window.localStorage.removeItem('pliziRedirectTo');
                    this.$router.push({ path: redirTo });
                }
                else {
                    this.$router.push({ path: '/profile' });
                }
            }
        }
        else {
            this.$router.push({ path: '/login' });
        }
    },


    afterSuccessLogout(evData) {
        this.$root.$isAuth = false;

        this.$root.$auth.cleanData();
        this.$root.$api.token = ``;
        this.$root.$api.channel = ``;

        window.localStorage.removeItem('pliziJWToken');
        window.localStorage.removeItem('pliziUser');
        window.localStorage.removeItem('pliziChatChannel');
        window.localStorage.removeItem('pliziLastSearch');
        window.localStorage.removeItem('pliziFriends');
        window.localStorage.removeItem('pliziFavorites');
        window.localStorage.removeItem('pliziDialogs');
        window.localStorage.removeItem('pliziInvitations');
        window.localStorage.removeItem('pliziNotifications');
        window.localStorage.removeItem('pliziCommunities');

        if (evData.redirect) {
            this.$router.push({path: '/login'});
        }
    },


    async afterUserLoad(evData) {
        if (evData.token !== ``  &&  evData.user) {
            this.restoreLastSearch();

            this.$root.$api.token = evData.token;
            this.$root.$api.channel = evData.user.channel;

            this.$root.$auth.updateAuthUserData( evData.user, evData.token );
            this.$root.$api.connectToChannel( evData.user.channel );
            this.$root.$isAuth = true;

            let userEntry = {
                id: evData.user.data.id,
                email: evData.user.data.email,
                firstName: evData.user.data.profile.firstName,
                lastName: evData.user.data.profile.lastName,
                userPic: evData.user.data.profile.userPic,
                lastLoginAt: new Date(),
            };
            (new PliziLastEntriesCollection(null)).addNewLastEntries(userEntry);

            await this.persistentCollectionsReload();
        }
    },


    async afterUserRestore(evData) {
        if (evData.token !== ``  &&  evData.user) {
            this.restoreLastSearch();

            this.$root.$api.token = evData.token;
            this.$root.$api.channel = evData.user.channel;

            this.$root.$auth.updateAuthUserData( evData.user, evData.token );
            this.$root.$api.connectToChannel( evData.user.channel );
            this.$root.$isAuth = true;

            await this.persistentCollectionsRestore();
        }
    },

    onNewAppNotification(evData){
        if (this.$root.$isXS()  || this.$root.$isSM() || this.$root.$isMD())
            return;

        if (`app.notification`===evData.type) {
            let appNotificationData = this.transformNotifyToNotification(evData);
            this.addNotification(appNotificationData);
        }

        if (`user.notification`===evData.type) {
            this.addNotification(evData.notification);
        }

        if (`chat.created`===evData.type) {
            let chatNotificationData = this.transformDialogToNotification(evData);
            this.addNotification(chatNotificationData);
        }

        if (`chat.removed`===evData.type) {
            const chatRemoved = this.$root.$auth.dm.get(evData.chatId);
            let chatNotificationData = this.transformForChatRemovedToNotification(chatRemoved, evData.type);
            this.addNotification(chatNotificationData);
        }

        if (`chat.attendee.removed`===evData.type) {
            const chatRemoved = this.$root.$auth.dm.get(evData.chatId);
            let chatNotificationData = this.transformForChatRemovedToNotification(chatRemoved, evData.type);
            this.addNotification(chatNotificationData);
        }

        if (`chat.attendee.appended`===evData.type) {
            let chatNotificationData = this.transformDialogToNotification(evData);
            this.addNotification(chatNotificationData);
        }

        if (`message.new`===evData.type) {
            const curDialog = window.localStorage.getItem('pliziActiveDialog');
            if (curDialog !== evData.message.chatId) {
                if (!evData.message.isMine) {
                    let chatNotificationData = this.transformMessageToNotification(evData);
                    this.addNotification(chatNotificationData);
                }
            }
        }
    },

    isAuthorized(){
        return this.$root.$isAuth;
    },

    restoreLastSearch(){
        const ls = localStorage.getItem('pliziLastSearch');
        this.$root.$lastSearch = (ls) ? ls : ``;
    },

    async persistentCollectionsReload(){
        await this.$root.$auth.frm.load();
        await this.$root.$auth.fm.load();
        await this.$root.$auth.dm.load();
        await this.$root.$auth.im.load();
        await this.$root.$auth.nm.load();
        await this.$root.$auth.cm.load();
    },

    async persistentCollectionsRestore(){
        this.$root.$auth.frm.restore();
        this.$root.$auth.fm.restore();
        this.$root.$auth.dm.restore();
        this.$root.$auth.im.restore();
        this.$root.$auth.cm.restore();
    },

    keysUpdatersInitiator(){
        this.$root.$on([this.$root.$auth.frm.loadEventName, this.$root.$auth.frm.restoreEventName,
                    this.$root.$auth.frm.updateEventName], ()=>{
            this.$root.$friendsKeyUpdater++;
        });

        this.$root.$on([this.$root.$auth.fm.loadEventName, this.$root.$auth.fm.restoreEventName,
                    this.$root.$auth.fm.updateEventName], ()=>{
            this.$root.$favoritesKeyUpdater++;
        });

        this.$root.$on([this.$root.$auth.dm.loadEventName, this.$root.$auth.dm.restoreEventName,
                    this.$root.$auth.dm.updateEventName], ()=>{
            this.$root.$dialogsKeyUpdater++;
        });

        this.$root.$on([this.$root.$auth.im.loadEventName, this.$root.$auth.im.restoreEventName,
                    this.$root.$auth.im.updateEventName], ()=>{
            this.$root.$invitationsKeyUpdater++;
        });

        this.$root.$on([this.$root.$auth.nm.loadEventName, this.$root.$auth.nm.restoreEventName,
                    this.$root.$auth.nm.updateEventName], ()=>{
            this.$root.$notificationsKeyUpdater++;
        });

        this.$root.$on([this.$root.$auth.cm.loadEventName, this.$root.$auth.cm.restoreEventName,
                    this.$root.$auth.cm.updateEventName], ()=>{
            this.$root.$communitiesKeyUpdater++;
        });
    }
},


created(){
    const style = ['padding: 0.5rem;',
        'background: rgb(0, 123, 255);',
        'font-size: 1.4/3 Verdana;',
        'font-weight: bold;',
        'border-radius: 5px 0px 5px 0px;',
        'color: white;'];

    this.$info( '%c%s', style.join(''), 'Plizi App created');

    this.$root.$api = PliziAPI;
    this.$root.$api.init(this.$root);

    this.$root.$auth = PliziAuth;
    this.$root.$auth.init(this.$root.$api);

    this.keysUpdatersInitiator();

    this.$root.$on('afterSuccessLogin',  this.afterSuccessLogin);
    this.$root.$on('afterSuccessLogout', this.afterSuccessLogout);

    this.$root.$on('AfterUserLoad', this.afterUserLoad);
    this.$root.$on('AfterUserRestore', this.afterUserRestore);

    this.$root.$on('NewAppNotification', this.onNewAppNotification);

    this.$root.$on('searchStart', (evData) => {
        this.lastSearchText = evData.searchText;
    });

    this.$root.$on('api:Unauthorized', (evData) => {
        this.$warn(evData, `api:Unauthorized!`);
        this.afterSuccessLogout( {redirect : true} );
    });

    this.$root.$on('alertModal', (evData) => {
        this.mainModalMessage = evData.message;
        this.mainModalClass = evData.clazz || ``;
        this.mainModalTimeOut = evData.timeOut ? (evData.timeOut >>> 0) : 0;
        this.mainModalVisible = true;
    });
    this.$root.$on('hideAlertModal', () => {
        this.mainModalVisible = false;
    });

    this.$root.$on('NewChatDialog', (evData)=>{
        this.$root.$auth.dm.onAddNewDialog(evData);
    });

    this.$root.$on('UserNotification', (evData)=>{
        this.$root.$auth.nm.onAddNewNotification(evData);

        if (evData.data.notificationType === 'friendships.sent') {
            this.$root.$auth.im.onAddNewInvitation(evData);
        }

        if (evData.data.notificationType === 'friendships.accepted') {
            this.$root.$auth.frm.onAddAcceptOurInvitation(evData);
        }
    });
},

beforeDestroy() {
    this.$root.$off('afterSuccessLogin', ()=>{});
    this.$root.$off('afterSuccessLogout', ()=>{});

    this.$root.$off('AfterUserLoad', ()=>{});
    this.$root.$off('AfterUserRestore', ()=>{});

    this.$root.$off('searchStart', ()=>{});

    this.$root.$off('api:Unauthorized', ()=>{});

    this.$root.$off('alertModal', ()=>{});
    this.$root.$off('hideAlertModal', ()=>{});

    this.$root.$off('NewChatDialog', ()=>{});
    this.$root.$off('UserNotification', ()=>{});
}


}
</script>

<style lang="scss" src="./styles/App.scss"></style>
