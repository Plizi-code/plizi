<template>
    <div class="plz-profile-userdetails-footer d-flex justify-content-around px-2 px-md-4">
        <div v-if="usrFollowersNumber() > 0"
             class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4">
            <span class="numbers-top" v-html="sBeaty(usrFollowersNumber())"></span>
            <span class="numbers-bottom">Подписчиков</span>
        </div>
        <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4">
            <span class="numbers-bottom mt-auto">Нет подписчиков</span>
        </div>

        <template v-if="isOwner">
            <router-link tag="a" class="p-0 d-flex" to="/friends" v-bind:key="'profileFriendsCounter-'+$root.$friendsKeyUpdater">
                <div v-if="usrFriendsNumber() > 0"
                     class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-top" v-html="sBeaty(usrFriendsNumber())"></span>
                    <span class="numbers-bottom">Друзей</span>
                </div>
                <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-bottom">Нет друзей</span>
                </div>
            </router-link>
        </template>
        <template v-else-if="userData.stats.isFriend">
            <router-link tag="a" class="p-0 d-flex"
                         :to="{path: `/user-${userData.id}/friends`, params: {id: userData.id}}">
                <div v-if="usrFriendsNumber() > 0"
                     class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-top" v-html="sBeaty(userData.stats.totalFriendsCount)"></span>
                    <span class="numbers-bottom">Друзей</span>
                </div>
                <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-bottom">Нет друзей</span>
                </div>
            </router-link>
        </template>
        <template v-else>
            <div>
                <div v-if="usrFriendsNumber() > 0"
                     class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-top" v-html="sBeaty(userData.stats.totalFriendsCount)"></span>
                    <span class="numbers-bottom">Друзей</span>
                </div>
                <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-bottom">Нет друзей</span>
                </div>
            </div>

        </template>

        <template v-if="isOwner">
        <router-link tag="a" class="p-0 d-flex" to="/photoalbums-list">
            <div class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                <template v-if="userImageNumber()">
                    <span class="numbers-top" v-html="sBeaty(userImageNumber())"></span>
                    <span class="numbers-bottom">Фотографий</span>
                </template>
                <template v-else>
                    <span class="numbers-bottom">Нет фотографий</span>
                </template>
            </div>
        </router-link>
        </template>
        <template v-else-if="userData.stats.isFriend">
            <router-link tag="a" class="p-0 d-flex" :to="{path: `/user-${userData.id}/albums`, params: {id: userData.id}}">
            <div class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                <template v-if="userImageNumber()">
                    <span class="numbers-top" v-html="sBeaty(userImageNumber())"></span>
                    <span class="numbers-bottom">Фотографий</span>
                </template>
                <template v-else>
                    <span class="numbers-bottom">Нет фотографий</span>
                </template>
            </div>
            </router-link>
        </template>
        <template v-else>
            <div>
                <div class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <template v-if="userImageNumber()">
                        <span class="numbers-top" v-html="sBeaty(userImageNumber())"></span>
                        <span class="numbers-bottom">Фотографий</span>
                    </template>
                    <template v-else>
                        <span class="numbers-bottom">Нет фотографий</span>
                    </template>
                </div>
            </div>
        </template>

        <template v-if="isOwner">
        <router-link tag="a" class="p-0 d-flex" to="/videos">
            <div v-if="usrVideosNumber() > 0"
                 class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                <span class="numbers-top" v-html="sBeaty(usrVideosNumber())"></span>
                <span class="numbers-bottom">Видео</span>
            </div>
            <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                <span class="numbers-bottom">Нет видео</span>
            </div>
        </router-link>
        </template>

        <template v-else-if="userData.stats.isFriend">
            <router-link tag="a" class="p-0 d-flex" v-if="usrVideosNumber() > 0"
                         :to="{path: `/user-${userData.id}/videos`, params: {id: userData.id}}">
            <div class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                <span class="numbers-top" v-html="sBeaty(usrVideosNumber())"></span>
                <span class="numbers-bottom">Видео</span>
            </div>
            </router-link>
            <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                <span class="numbers-bottom">Нет видео</span>
            </div>
        </template>
        <template v-else>
            <div>
                <div v-if="usrVideosNumber() > 0">
                    <div class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                        <span class="numbers-top" v-html="sBeaty(usrVideosNumber())"></span>
                        <span class="numbers-bottom">Видео</span>
                    </div>
                </div>
                <div v-else class="plz-profile-userdetails-numbers text-center py-2 px-2 py-md-4 px-md-4 mt-auto">
                    <span class="numbers-bottom">Нет видео</span>
                </div>
            </div>

        </template>
        <!--
        <div class="plz-profile-userdetails-numbers text-center pt-4 px-4">
            <span class="numbers-top" v-html="sBeaty(userData.audiosNumber)"></span>
            <span class="numbers-bottom">Аудио</span>
        </div>
        -->
    </div>
</template>

<script>
import PliziUser from '../classes/PliziUser.js';

export default {
name : 'ProfileStats',
props : {
    userData: PliziUser,
    isOwner: Boolean,
    keyUpdater: Number
},

methods: {
    sBeaty(param) {
        return this.$options.filters.statsBeauty(param);
    },

    usrFriendsNumber() {
        return this.userData.stats.totalFriendsCount;
    },

    usrFollowersNumber() {
        return this.userData.stats.followCount;
    },

    usrVideosNumber() {
        return this.userData.stats.videosCount;
    },

    userImageNumber() {
        return this.userData.stats.imageCount;
    },
}

}
</script>
