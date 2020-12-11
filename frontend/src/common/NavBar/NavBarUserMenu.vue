<template>
    <div class="col-auto col-md-1 col-lg-3 col-xl-2 px-0 profile-menu">
        <router-link to="/profile" tag="a" class="profile-menu-link d-none d-lg-block ">
            <span v-if="isShowName" ref="navbarUserName">{{userData().firstName}}</span>
        </router-link>

        <div class="profile-menu-item p-0 m-0 d-flex align-items-center position-relative">

            <router-link to="/profile" tag="a"
                         class="profile-menu-link profile-menu-pic procreateCommunityBlockfile-menu-pic d-block ">
                <img v-if="isShowAvatar"
                     :key="'navbarAvatar-'+avaUpdater"
                     ref="navbarAvatar" :src="userData().userPic" :alt="userData().firstName"/>
            </router-link>

            <button class="btn dropdown-menu-btn"
                    id="dropdownMenuUser"
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                <i class="profile-menu-opener fa fas fa-chevron-down py-2 px-1"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right py-3 px-3" aria-labelledby="dropdownMenuUser">

                <div class="nav-item border-bottom">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/profile">Моя страница</router-link>
                </div>

                <div class="nav-item">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/friends">Мои друзья</router-link>
                </div>

                <div class="nav-item">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/communities">Мои сообщества</router-link>
                </div>

                <div class="nav-item border-bottom">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/follow-list">Мои подписки</router-link>
                </div>

                <div class="nav-item">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/black-list">Чёрный список</router-link>
                </div>

                <div class="nav-item">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/account">Настройки</router-link>
                </div>

                <div class="nav-item border-top">
                    <router-link tag="a" class="dropdown-item px-0 py-1" to="/logout">Выйти</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
name : 'NavBarUserMenu',
props : {},

data() {
    return {
        isShowName: true,
        isShowAvatar: true,
        avaUpdater: 0,
    }
},

methods: {
    userData() {
        const usrData = this.$root.$auth.user;

        if (! (!!usrData.profile)){
            window.console.warn(usrData, 'profile is null');
        }

        return usrData;
    },

    updateUserName(evData){
        this.isShowName = false;
        setTimeout(()=>{ this.isShowName = true; }, 10);
    },

    updateAvatar(evData){
        this.isShowAvatar = false;
        this.avaUpdater++;
        setTimeout(()=>{ this.isShowAvatar = true; }, 10);
    }
},

mounted() {
    this.$root.$on('updateUserAvatar', this.updateAvatar);
    this.$root.$on('updateUserName', this.updateUserName);
}
}
</script>
