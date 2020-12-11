<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1  px-0 px-md-3">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 col-lg-11 col-xl-8 pr-0 px-0 px-md-3"
                 :class="calcCentralBlockClass()"
                 v-bind:key="`CentralColumn-`+$root.$friendsKeyUpdater">

                <div class="container">
                    <ProfileHeader v-if="isDataReady" ref="personalProfileHeader"
                                   @ShowPersonalMsgModal="onShowPersonalMsgModal"
                                   :isInBlacklist="profileData.stats.isInBlacklist"
                                   :userData="profileData">
                    </ProfileHeader>
                    <Spinner v-else></Spinner>

                    <template v-if="isLockedProfile">
                        <div class="row">
                            <div class="card locked-profile-notify bg-white-br20 border-0 w-100">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <div class="mb-3">
                                        <IconUnlock/>
                                    </div>
                                    <div class="font-weight-bold text-uppercase">
                                        Это закрытый профиль
                                    </div>
                                    <div v-if="this.profileData.privacySettings.pageType !== 3"
                                         class="text-center text-secondary mt-2 w-50">
                                        Добавьте пользователя {{ this.profileData.profile.firstName }} в друзья,
                                        чтобы смотреть записи, фотографии и другие материалы
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <router-view name="userLastPhotos"
                                     :profileData="profileData"
                                     :isPhotosDataReady="isPhotosDataReady"
                                     :photos="userPhotos"
                                     :isOwner="false"></router-view>
                        <router-view name="userLastPost"
                                     :filter-mode="filterMode" :filtered-posts="filteredPosts"
                                     :on-share-post="onSharePost" :open-like-modal="openLikeModal"
                                     :isStarted="isStarted"
                                     :profile-data="profileData"
                                     :wall-posts-select-handler="wallPostsSelectHandler"></router-view>
                        <router-view name="userFriendsList"></router-view>
                        <router-view name="userCommunities"></router-view>
                        <router-view name="userPhotoalbums"></router-view>
                        <router-view name="userPhotoalbum"></router-view>
                        <router-view name="userVideoList"></router-view>
                        <router-view name="followersList"></router-view>

                    </template>
                </div>

                <NewPersonalMessageModal v-if="isShowMessageDialog"
                                         @HidePersonalMsgModal="onHidePersonalMsgModal"
                                         @SendPersonalMessage="handlePersonalMessage"
                                         v-bind:user="profileData"></NewPersonalMessageModal>

                <PostRepostModal v-if="postRepostModal.isVisible"
                                 v-bind:user="profileData"
                                 v-bind:post="postForRepost"
                                 @hidePostRepostModal="hidePostRepostModal"></PostRepostModal>

                <PostLikeModal v-if="postLikeModal.isVisible"
                               :postId="postLikeModal.content.postId"
                               @hideLikeModal="hideLikeModal"/>
            </div>

            <div v-if="(this.$root.$auth.fm.size > 0 || this.userCommunities || this.profileData.videos)"
                 class="col-sm-3 col-md-3 col-lg-3 col-xl-3 pr-0 d-xl-block"
                 v-bind:key="`RightColumn-`+$root.$favoritesKeyUpdater">

                <FavoriteFriends></FavoriteFriends>
                <CommunitiesSmallBlock v-if="userCommunities.length"
                                       :footer="footerLink"
                                       :title="`Сообщества пользователя`"
                                       :clazz="`bg-white-br20`"
                                       :communities="userCommunities.slice(0, 5)"></CommunitiesSmallBlock>
            </div>

        </div>
    </div>
</template>

<script>
import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
import FavoriteFriends from '../common/FavoriteFriends.vue';
import Spinner from '../common/Spinner.vue';

import ProfileHeader from '../components/ProfileHeader.vue';
import ProfilePhotos from '../components/ProfilePhotos.vue';

import NewPersonalMessageModal from '../components/NewPersonalMessageModal.vue';
import PostRepostModal from '../common/Post/PostRepostModal.vue';
import PostLikeModal from '../common/Post/PostLikeModal.vue';
import IconUnlock from "../icons/IconUnlock.vue";

import DialogMixin from '../mixins/DialogMixin.js';
import LazyLoadPosts from '../mixins/LazyLoadPosts.js';
import BlackListMixin from '../mixins/BlackListMixin.js';
import PhotosListMixin from '../mixins/PhotosListMixin.js';
import CommunitiesListMixin from '../mixins/CommunitiesListMixin.js';

import PliziUser from '../classes/PliziUser.js';
import PliziPost from '../classes/PliziPost.js';
import CommunitiesSmallBlock from "../common/Communities/CommunitiesSmallBlock";
import UserPosts from "../components/UserPosts";

export default {
    name: 'PersonalPage',
    props: {
        id: Number | String
    },
    components: {
        UserPosts,
        CommunitiesSmallBlock,
        Spinner,
        AccountToolbarLeft, FavoriteFriends, ProfileHeader, NewPersonalMessageModal,
        ProfilePhotos,
        PostRepostModal,
        PostLikeModal,
        IconUnlock,
    },
    mixins: [DialogMixin, LazyLoadPosts, BlackListMixin, PhotosListMixin, CommunitiesListMixin],
    data() {
        return {
            userId: null,
            profileData: {},
            isDataReady: false,
            isShowMessageDialog: false,
            posts: [],
            footerLink: null,
            // isPhotosDataReady: false,
            userPhotos: [],
            filterMode: 'all',
            postRepostModal: {
                isVisible: false,
            },
            postForRepost: null,
            postLikeModal: {
                isVisible: false,
                content: {
                    postId: null,
                },
            },
        }
    },

    watch: {
        $route: 'afterRouteUpdate' // при изменениях маршрута запрашиваем данные снова
    },

    computed: {
        filteredPosts() {
            if (this.filterMode === 'user') {
                return this.posts.filter(post => post.checkIsMinePost(this.profileData.id));
            }

            return this.posts;
        },
        isLockedProfile() {
            if (this.profileData && this.profileData.privacySettings && this.profileData.stats) {
                if ((this.profileData.privacySettings.pageType === 2 && !this.profileData.stats.isFriend) ||
                    this.profileData.privacySettings.pageType === 3) {
                    return true;
                }
            }

            return false;
        },
    },

    methods: {
        async afterRouteUpdate(ev) {
            this.userId = ev.params.id;
            this.posts = [];
            this.isStarted = true;

            if (!this.isLockedProfile) {
                await this.getUserInfo();

                if (this.$root.$router.currentRoute.name === "PersonalPage") {
                    await this.getPosts();
                    await this.getUserPhotos(this.id);
                }
            }

            window.scrollTo(0, 0);
        },

        calcCentralBlockClass() {
            const isCentralNarrow = (this.$root.$auth.fm.size > 0 || this.userCommunities || this.profileData.videos);

            return {
                'col-lg-8 col-xl-8': isCentralNarrow,
                'col-lg-11 col-xl-11': !isCentralNarrow,
            };
        },

        wallPostsSelectHandler(evData) {
            this.filterMode = evData.wMode;
        },

        onSharePost(post) {
            this.postRepostModal.isVisible = true;
            this.postForRepost = post;
        },

        hidePostRepostModal() {
            this.postRepostModal.isVisible = false;
            this.postForRepost = null;
        },

        onHidePersonalMsgModal() {
            this.isShowMessageDialog = false;
        },

        onShowPersonalMsgModal() {
            this.isShowMessageDialog = true;
        },

        hideLikeModal() {
            this.postLikeModal.isVisible = false;
            this.postLikeModal.content.postId = null;
        },

        openLikeModal(postId) {
            this.postLikeModal.isVisible = true;
            this.postLikeModal.content.postId = postId;
        },

        async handlePersonalMessage(evData) {
            this.onHidePersonalMsgModal();

            this.$root.$once('NewChatDialog', (dlgData) => {
                this.sendPrivateMessageToUser(dlgData, evData.message);
            });

            await this.openDialogWithFriend({
                id: this.profileData.id,
                fullName: this.profileData.fullName
            });
        },

        async sendPrivateMessageToUser(chatData, msgData) {
            const sendData = {
                chatId: chatData.id,
                body: msgData.postText,
                attachments: msgData.attachments,
                event: 'new.message'
            };
            this.$root.$api.sendToChannel(sendData);
        },

        async getUserInfo() {
            let apiResponse = null;

            try {
                apiResponse = await this.$root.$api.$users.getUser(this.userId);
            } catch (e) {
                this.isStarted = false;
                window.console.warn(e.detailMessage);
                throw e;
            }

            if (apiResponse) {
                this.profileData = new PliziUser(apiResponse.data);
                this.isDataReady = true;
            }
        },

        async getPosts(limit = 50, offset = 0) {
            if (!(this.profileData && this.profileData.id))
                return;

            let response = null;

            try {
                response = await this.$root.$api.$post.getPostsByUserId(this.profileData.id, limit, offset);
            } catch (e) {
                this.isStarted = false;
                console.warn(e.detailMessage);
            }

            if (response !== null) {
                this.isStarted = false;
                response.map((post) => {
                    this.posts.push(new PliziPost(post));
                });

                return response.length;
            }
        },
    },

    created() {
        this.userId = this.id;

        this.$root.$on(this.$root.$auth.frm.updateEventName, () => {
            if (this.$refs && this.$refs.personalProfileHeader) {
                this.$refs.personalProfileHeader.$forceUpdate();
            }
        });
    },


    async mounted() {
        this.isStarted = true;
        await this.getUserInfo();

        if (!this.isLockedProfile) {
            if (this.$root.$router.currentRoute.name === "PersonalPage") {
                await this.getUserPhotos(this.userId);
                await this.getPosts();
            }
            await this.getUserCommunitiesList(6);

            if (this.userCommunities.length > 5) {
                this.footerLink = {title: 'Все сообщества', path: `/user-${this.id}/communities`};
            }
        }

        window.scrollTo(0, 0);
    },

        /**
         * @TGA закоменченное ниже - ошибка но пусть пока будет
         */
// async beforeRouteUpdate( to, from, next ){
//    this.profileData = null;
//    this.posts = null;
//    this.userId = to.params.id;
//    next();
//     this.isStarted = true;
//     await this.getUserInfo();
//     await this.getPosts();
//    window.scrollTo( 0, 0 );
// },
    }
</script>

<style lang="scss">
    .locked-profile-notify {
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.05);

        .icon {
            width: 30px;
        }
    }
</style>

