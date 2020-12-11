<template>
    <div id="profileHeader" class="row plz-profile-header mb-4">
        <div
            class="d-flex align-items-stretch offset-1 col-10 offset-sm-3 col-sm-6 offset-md-4 col-md-4 offset-lg-0 col-lg-4 col-xl-3 pl-lg-0 mb-4 mb-lg-0">
            <div class="plz-profile-userpic-container d-flex flex-column  bg-white-br20 overflow-hidden">
                <div
                    class="plz-profile-userpic-wrapper overflow-hidden position-relative d-flex align-items-center justify-content-center mx-auto m-lg-0"
                    :class="{'flex-grow-1': userData.privacySettings.pageType === 3}">
                    <img ref="userAvatar" :src="userAvatar" :alt="userData.fullName" :class="{'plz-profile-userpic-img': userData.privacySettings.pageType === 3}"/>
                    <label v-if="isOwner===true" for="userAvatarFile"
                           class="user-avatar-file-label m-0 cursor-pointer"></label>
                    <input id="userAvatarFile" ref="userAvatarFile" type="file" @change="uploadUserAvatar()"
                           class="d-none"/>
                </div>

                <div v-if="isOwner" class="plz-profile-userpic-footer mt-auto">
                    <div class="plz-profile-userpic-edit file-label d-flex align-items-center justify-content-between">

                        <router-link tag="a"
                                 class="btn align-items-center justify-content-center d-flex w-75 border-right m-0"
                                     to="/account">Редактировать</router-link>

                        <button class="btn dropdown-menu-btn align-items-center justify-content-center d-flex w-25"
                                :id="configurationMenuID"
                                type="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                title="опции">
                            <span class="ps-dot"></span>
                            <span class="ps-dot"></span>
                            <span class="ps-dot"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right py-3" :aria-labelledby="configurationMenuID">
                            <div class="nav-item ">
                                <router-link tag="a" class="dropdown-item px-0 py-1 px-3" to="/account">Настройки</router-link>
                            </div>
                            <div class="nav-item">
                                <router-link tag="a" class="dropdown-item px-0 py-1 px-3" to="/black-list">Чёрный список</router-link>
                            </div>
                            <div class="nav-item">
                                <router-link tag="a" class="dropdown-item px-0 py-1 px-3" to="/friends">Друзья</router-link>
                            </div>
                            <div class="nav-item">
                                <router-link tag="a" class="dropdown-item px-0 py-1 px-3" to="/follow-list">Подписки</router-link>
                            </div>
                            <div class="nav-item ">
                                <router-link tag="a" class="dropdown-item px-0 py-1 px-3" to="/communities">Сообщества</router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <template v-else>
                    <div v-if="userData.privacySettings.pageType !== 3"
                         class="plz-profile-userpic-footer mt-auto">
                        <div class="plz-profile-userpic-edit file-label d-flex align-items-center justify-content-between">
                            <button v-bind:style="{ width: fullWidth }"
                                    class="btn align-items-center justify-content-center d-flex w-75 border-right m-0"
                                    @click="showPersonalMsgDialog()">Написать</button>

                            <button class="btn dropdown-menu-btn align-items-center justify-content-center d-flex w-25"
                                    :id="configurationMenuID"
                                    type="button"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    title="меню">
                                <span class="ps-dot"></span>
                                <span class="ps-dot"></span>
                                <span class="ps-dot"></span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right py-3 " :aria-labelledby="configurationMenuID"
                                 :key="`userActionBlock-`+$root.$friendsKeyUpdater">

                                <div class="nav-item ">
                                    <p v-if="isCanAddToFriends()"
                                       class="dropdown-item px-0 py-1 m-0 px-3"
                                       @click="sendFriendshipInvitation(userData.id, userData.fullName)"
                                       title="Добавить в друзья" >Добавить в друзья</p>
                                    <p v-else
                                       class="dropdown-item px-0 py-1 m-0 px-3"
                                       @click="stopFriendship(userData.id)"
                                       title="Удалить из друзей" >Удалить из друзей</p>
                                </div>
                                <div v-if="!userData.stats.isFriend" class="nav-item">
                                    <p v-if="userData.stats.isFollow" class="dropdown-item px-0 py-1 m-0 px-3"
                                       @click="unFollow" title="Отписаться">Отписаться</p>
                                    <p v-else class="dropdown-item px-0 py-1 m-0 px-3"
                                       @click="follow" title="Подписаться">Подписаться</p>
                                </div>
                                <div v-if="!userData.isOwner" class="nav-item">
                                    <p v-if="isAddedToBlacklist" class="dropdown-item px-0 py-1 m-0 px-3"
                                       @click="deleteFromBlacklist(userData.id)"  title="Удалить с чёрного списка">Удалить с чёрного списка</p>
                                    <p v-else class="dropdown-item px-0 py-1 m-0 px-3"
                                       @click="addToBlacklist"  title="Добавить в чёрный список">Добавить в чёрный список</p>
                                </div>
                                <div v-if="!userData.isOwner" class="nav-item">
                                    <router-link tag="a" class="dropdown-item px-0 py-1 m-0 px-3"
                                                 :to="{path: `/user-${userData.id}/followers`, params: {id: userData.id}}">
                                        На кого подписан
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="col-12  col-lg-8 col-xl-9 px-0 pt-4 plz-profile-userdetails">
            <div class="w-100 bg-white-br20 px-3 px-md-5 pb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 v-if="isOwner" class="plz-user-name mb-0 pr-4">{{userData.fullName}}</h2>
                    <router-link v-else :to="{ name: 'PersonalPage', params: { id: userData.id }}" tag="h2"
                                 class="plz-user-name mb-0 pr-4">{{userData.fullName}}</router-link>

                    <span v-if="userData.isOnline" class="online text-nowrap">В сети</span>
                </div>

                <table class="plz-user-profile-details table table-borderless mt-2">
                    <tbody>
                    <tr v-if="!!userData.profile.birthday">
                        <td class="">Дата рождения:</td>
                        <td class="">{{ userData.profile.birthday | toLongDate }}</td>
                    </tr>
                    <tr>
                        <td class="">Город:</td>
                        <td class="">
                            <template v-if="userData.country && userData.city.title">
                                <IconLocation/> {{userData.locationText}}
                            </template>
                            <template v-else>Не указано</template>
                        </td>
                    </tr>
                    <tr v-if="!!userData.relationshipId">
                        <td class="">Семейное положение:</td>
                        <td class="">
                            {{userData.family}}
                            <template v-if="!!userData.profile.relationshipUser">
                                {{ userData.profile.relationshipUserText }}
                                <router-link :to="{ name: 'PersonalPage', params: { id: userData.profile.relationshipUser.id } }">
                                    {{ userData.profile.partnerFullname }}
                                </router-link>
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <ProfileStats
                ref="profileStats"
                v-bind:userData="userData"
                v-bind:isOwner="isOwner"
                v-bind:keyUpdater="profileStatsKeyUpdater"
                v-bind:key="'ProfileStats-'+profileStatsKeyUpdater"></ProfileStats>
        </div>
    </div>
</template>

<script>
import IconLocation from '../icons/IconLocation.vue';
import ProfileStats from './ProfileStats.vue';

import FriendshipInvitationMixin from '../mixins/FriendshipInvitationMixin.js';
import BlackListMixin from '../mixins/BlackListMixin.js';

import PliziUser from '../classes/PliziUser.js';
import PliziAvatar from '../classes/User/PliziAvatar.js';

export default {
name: 'ProfileHeader',
components: { ProfileStats, IconLocation},
mixins: [FriendshipInvitationMixin, BlackListMixin],
props: {
    userData: PliziUser,
    isOwner: Boolean,
    isInBlacklist: Boolean,
},

data(){
    return {
        configurationMenuID : 'configurationMenuUser',
        profileStatsKeyUpdater: 0
    }
},

computed: {
    fullWidth: function () {
        return this.isCanAddToFriends ? 'full-width' : '100%';
    },

    userAvatar() {
        return this.userData.avatar?.image?.medium.path || this.userData.userPic;
    },

    isLockedProfile() {
        if (this.userData && this.userData.privacySettings && this.userData.stats) {
            if ((this.userData.privacySettings.pageType === 2 && !this.userData.stats.isFriend) ||
                this.userData.privacySettings.pageType === 3) {
                return true;
            }
        }

        return false;
    },
},

methods: {
    isCanAddToFriends() {
        return !(!!this.$root.$auth.frm.get(this.userData.id));
    },

    showPersonalMsgDialog() {
        this.$emit('ShowPersonalMsgModal', {user: this.userData, src: this.$route.name});
    },

    async uploadUserAvatar() {
        if (this.isOwner !== true)
            return;

        const formData = this.getFormData();

        if (!formData) {
            return;
        }

        const {size} = formData.get('image');

        if (size > 2000000) {
            this.showErrorOnLargeFile();
            return;
        }

        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.userProfileImage(formData);
        }
        catch (e) {
            if (e.status === 422) {
                this.showErrorOnLargeFile();
                return;
            }
            console.warn(e.detailMessage);
        }

        if (apiResponse !== null) {
            this.$root.$auth.user.avatar = new PliziAvatar(apiResponse.data);
            this.$root.$auth.user.userPic = this.$root.$auth.user.avatar?.image?.thumb.path;
            this.$refs.userAvatar.src = this.$root.$auth.user.avatar?.image?.medium.path || this.$root.$auth.user.userPic;
            this.$root.$auth.storeUserData();
            this.$root.$emit('updateUserAvatar', {});
        }
    },

    /**
     * @returns {boolean|FormData}
     */
    getFormData() {
        const fName = this.$refs.userAvatarFile.value;
        const fExt = fName.split('.').pop().toLowerCase();
        const allowExts = ['png', 'jpg', 'jpeg', 'bmp', 'webp', 'gif'];

        if (!allowExts.includes(fExt)) {
            this.$alert(`<h4 class="text-white">Ошибка</h4><div class="alert alert-danger">
Недопустимое расширение у файла <b>${fName}</b><br />
Допустимы только: <b class="text-success">${allowExts.join(', ')}</b>
</div>`, `bg-danger`, 30);
            return false;
        }

        const formData = new FormData();
        formData.append('image', this.$refs.userAvatarFile.files[0]);
        formData.append('tag', 'primary');
        this.$refs.userAvatarFile.value = '';

        return formData;
    },

    showErrorOnLargeFile() {
        this.$alert(`<h4 class="text-white">Ошибка</h4>
        <div class="alert alert-danger">
            Превышен максимальный размер файла.
            <br />
            Максимальный размер файла:
            <b class="text-success">2 MB</b>
        </div>`,
            `bg-danger`,
            30
        );
    },

    async follow() {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$users.follow(this.userData.id);
        } catch (e) {
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (apiResponse) {
            if (apiResponse.status && apiResponse.status === 422) {
                this.$root.$alert(apiResponse.message, 'bg-info', 3);
            }
            else {
                this.userData.stats.isFollow = true;
                this.userData.stats.followCount = this.userData.stats.followCount + 1;
                this.$root.$notify(apiResponse.message);
            }
        }
        else {
            this.$root.$alert(`Не получилось подписаться`, 'bg-warning', 3);
        }

        return true;

    },

    async unFollow() {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$users.unFollow(this.userData.id);
        } catch (e) {
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (apiResponse) {
            if (apiResponse.status && apiResponse.status === 422) {
                this.$root.$alert(apiResponse.message, 'bg-info', 3);
            } else {
                this.userData.stats.isFollow = false;
                this.userData.stats.followCount = this.userData.stats.followCount - 1;
                this.$root.$notify(apiResponse.message);
            }
        }
        else {
            this.$root.$alert(`Не получилось отписаться`, 'bg-warning', 3);
        }

        return true;
    },
},

created(){
    this.$root.$on( 'UserIsUpdated', ()=>{
        if (this.$refs  &&  this.$refs.profileStats){
            this.profileStatsKeyUpdater++;
            this.$refs.profileStats.$forceUpdate();
        }
    });
},

async mounted() {
    this.isAddedToBlacklist = this.isInBlacklist;
}

}
</script>
