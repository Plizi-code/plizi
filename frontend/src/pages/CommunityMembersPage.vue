<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft/>
            </div>

            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-10">
                <CommunityHeader :community="communityData" :subscribe-type="subscribeType"/>

                <div class="row">
                    <div class="col-12 --col-sm-7 col-lg-8 col-xl-8">

                        <div class="col-12 order-1 order-md-0 bg-white-br20 py-2">
                            <CommunityMember
                                v-for="member in allMembers"
                                v-if="isLoaded"
                                :key="member.id"
                                :srItem="member"
                                :isAdmin="role && role !== 'user'"
                                :communityId="parseInt(id)" />
                            <Spinner v-else/>
                        </div>
                    </div>

                    <div class="col-12 --col-sm-5 col-lg-4">
                        <!--               TODO @tga мы этот блок можем удалить? -->
                        <!--                    <CommunityManagedActionBlock :community="communityData" v-if="isAuthor"></CommunityManagedActionBlock>-->
                        <!--               TODO @tga мы этот блок можем удалить? -->
                        <!--                    @TGA отвечаю - пока не можем -->
                        <CommunityUserActionBlock v-if="!isAuthor" v-bind:community="communityData"/>

                        <CommunityFriendsInformer v-bind:community="communityData"/>

                        <CommunityShortMembers v-if="isDataReady" v-bind:community="communityData"/>

                        <CommunityVideoBlock v-if="isDataReady && hasAccess"
                                             :key="'cv' + communityData.id"
                                             :avatarMedium="avatarMedium"
                                             :communityId="parseInt(id)"
                                             @openVideoModal="openVideoModal"/>
                    </div>
                </div>
            </div>

            <div class="col-sm-2  col-lg-2 col-xl-1 d-none d-lg-block">
                <FavoriteFriends :isNarrow="true"/>
            </div>

        </div>

        <PostVideoModal v-if="postVideoModal.isVisible"
                        :videoLink="postVideoModal.content.videoLink"
                        @hideVideoModal="hideVideoModal"/>

    </div>
</template>

<script>
    import PliziMember from '../classes/PliziMember.js';
    import PliziCommunity from "../classes/PliziCommunity.js";
    import CommunitiesSubscribeMixin from "../mixins/CommunitiesSubscribeMixin.js";
    import CommunityPageMixin from "../mixins/CommunityPageMixin.js";

    import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
    import FavoriteFriends from "../common/FavoriteFriends.vue";

    import FriendsAllList from '../components/FriendsAllList.vue';
    import CommunityMember from '../components/Community/CommunityMember.vue';

    import IconShare from '../icons/IconShare.vue';

    export default {
        name: 'CommunityMembersPage',
        mixins: [CommunitiesSubscribeMixin, CommunityPageMixin],
        components: {
            FavoriteFriends,
            FriendsAllList,
            AccountToolbarLeft,
            CommunityMember,
            IconShare,
        },
        props: {
            id: Number | String,
        },
        data() {
            return {
                allMembers: [],
                isLoaded: true,
                role: null,

                lazyLoadStarted: false,
                noMore: false,
            }
        },
        computed: {
            isAuthor(){
                return this.communityData?.role === 'author';
            },
            subscribeType() {
                return this.getSubscribeType(this.communityData);
            },
            avatarMedium() {
                return this.communityData?.avatar?.image.medium.path || this.communityData?.primaryImage;
            },
            hasAccess() {
                // Opened
                if (this.communityData?.privacy === 1) {
                    return true;
                }
                return !!this.communityData?.role;
            }
        },
        methods: {
            async getCommunityInfo() {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$communities.getCommunity(this.id);
                }
                catch (e){
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (apiResponse) {
                    this.communityData = new PliziCommunity(apiResponse);
                    this.isDataReady = true;
                    document.title = `Plizi: ${this.communityData?.name}`;

                    setTimeout(() => {
                        this.loadCommunityMembers();
                    }, 100);
                }
            },
            async loadCommunityMembers() {
                let apiResponse;
                this.isLoaded = false;

                try {
                    apiResponse = await this.$root.$api.$communities.members(this.id);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    apiResponse.list.map((request) => {
                        this.allMembers.push(new PliziMember(request));
                    });
                    this.role = apiResponse.role;
                    this.isLoaded = true;
                }
            },
            onScrollYPage() {
                if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight / 2))) {
                    this.membersLazyLoad(); // Дозагрузка!
                }
            },

            async membersLazyLoad() {
                if (this.lazyLoadStarted || this.noMore) {
                    return;
                }

                this.lazyLoadStarted = true;

                const added = 0;
                if (added === 0) {
                    this.noMore = true;
                }

                this.lazyLoadStarted = false;
                this.onScrollYPage();
            }
        },
        mounted() {
            this.getCommunityInfo();
            window.scrollTo(0, 0);
            // window.addEventListener('scroll', this.onScrollYPage);
        }

    }
</script>
