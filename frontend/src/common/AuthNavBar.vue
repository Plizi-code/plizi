<template>
    <header id="authHeader" class="fixed-top"
            :class="{ 'page-has-offset-top' : isShowNavBarShadow }">
        <nav class="auth-navbar navbar navbar-expand-lg container container-wide mx-auto  pl-md-0 pl-lg-3">
            <div class="w-100 ">
                <div class="row ">
                    <div class="d-flex align-items-center col-3 col-sm-2 col-md-1 pl-3 pr-0 px-lg-0 ">
                        <router-link v-if="isGotoLogin" :to="`/logout`" tag="a"
                                     class="navbar-brand w-100 d-block text-center mx-auto h-auto my-0 py-0">
                            <IconPliziLogo />
                        </router-link>
                        <router-link v-else :to="{name: 'NewsPage'}" tag="a"
                                     class="navbar-brand w-100 d-block text-center mx-auto h-auto my-0 py-0">
                            <IconPliziLogo />
                        </router-link>
                    </div>

                    <div class="col-sm-4 col-md-6 col-lg-5 d-none d-md-flex align-items-center ">
                        <NavBarSearch></NavBarSearch>
                    </div>
                    <!--<div id="playerWrapper" class="plz-top-player col-lg-2 col-xl-2 d-sm-none d-md-none d-lg-block d-xl-block text-center ">
                        <NavBarPlayer></NavBarPlayer>
                    </div>-->
                    <div id="watcherWrapper" class="plz-top-watcher col-6 col-sm-4 col-lg-3 col-xl-2 w-auto m-auto text-center">
                        <div class="--btn-block mt-2 d-inline-block">
                            <NavBarNotifications></NavBarNotifications>

                            <NavBarMessages></NavBarMessages>

                            <NavBarFriends></NavBarFriends>
                        </div>
                    </div>

                    <NavBarUserMenu></NavBarUserMenu>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
import IconPliziLogo from '../icons/IconPliziLogo.vue';

import NavBarSearch from './NavBar/NavBarSearch.vue';
import NavBarPlayer from './NavBar/NavBarPlayer.vue';

import NavBarNotifications from './NavBar/NavBarNotifications.vue';
import NavBarMessages from './NavBar/NavBarMessages.vue';
import NavBarFriends from './NavBar/NavBarFriends.vue';

import NavBarUserMenu from './NavBar/NavBarUserMenu.vue';

export default {
name: 'AuthNavBar',
components : { IconPliziLogo, NavBarSearch, NavBarPlayer,
    NavBarNotifications, NavBarMessages, NavBarFriends,
    NavBarUserMenu
},
props: {
    showShadow: {
        type: Boolean,
        required: false,
        default: false
    }
},

data(){
    return {
        isShowNavBarShadow: false
    }
},

computed : {
    isGotoLogin(){
        return !!window.isTarga;
    },
},

created(){
    window.addEventListener('scroll', ()=>{
        this.isShowNavBarShadow = (window.scrollY > 80  &&  'ChatsListPage'!==this.$root.$router.currentRoute.name);
    });
}
}
</script>
