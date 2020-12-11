<template>
    <div class="row">
        <div class="col-12">
            <div v-if="community" id="communityHeader"
                 class="plz-community-header bg-white-br20 mb-4 text-left overflow-hidden">
                <div class="plz-community-header-pic position-relative overflow-hidden">
                    <img :src="headerImage" alt="image">
                </div>
                <div
                    class="plz-community-header-bottom d-flex flex-wrap align-items-start justify-content-between py-3 px-4">
                    <div
                        class="plz-community-header-details d-flex align-items-start flex-wrap flex-sm-nowrap justify-content-center justify-content-sm-start">
                        <template v-if="isAuthor">
                            <label for="communityPrimaryImage"
                                   class="community-primary-image cursor-pointer plz-community-header-logo position-relative mb-2 mb-sm-0 mx-0 mr-sm-3">
                                <img ref="communityAvatar" :src="avatarMedium" :alt="community.name"/>
                            </label>

                            <input id="communityPrimaryImage" ref="communityPrimaryImage" type="file"
                                   @change="uploadPrimaryImage()"
                                   class="d-none"/>
                        </template>
                        <template v-else>
                            <div class="plz-community-header-logo position-relative mr-3">
                                <img ref="communityAvatar" :src="avatarThumb" :alt="community.name"/>
                            </div>
                        </template>
                        <div class="plz-community-header-details-text pt-2">
                            <h1 class="plz-community-header-title mb-2">
                                <router-link :to="`/community-`+community.id" tag="a">
                                    {{community.name}}
                                </router-link>
                            </h1>
                            <PrivacyLabel :community="community"/>
                            <p class="plz-community-header-desc mb-0">
                                {{community.notice}}
                            </p>
                        </div>
                    </div>
                    <div
                        class="plz-community-subscribe file-label d-flex align-items-center mt-4 justify-content-between mx-auto mx-md-0">

                        <button v-if="subscribeType === 'new'"
                                class="btn align-items-center justify-content-center d-flex w-75 border-right m-0"
                                @click="subscribeInvite(community)">
                            подписаться
                        </button>
                        <button v-else-if="subscribeType === 'request'"
                                class="btn align-items-center justify-content-center d-flex w-75 border-right m-0"
                                @click="sendRequest(community)">
                            запрос
                        </button>
                        <button v-else-if="subscribeType === 'exists'"
                                class="btn align-items-center justify-content-center d-flex w-75 border-right m-0"
                                @click="unsubscribeInvite(community)">
                            отписаться
                        </button>
                        <router-link :to="{name: 'CommunitySettingsPage', params: {id: community.id}}"
                                     v-else-if="subscribeType === 'author'"
                                     class="btn align-items-center justify-content-center d-flex w-75 border-right m-0">
                            управление
                        </router-link>

                        <button title="опции"
                                class="btn align-items-center justify-content-center d-flex w-25"
                                type="button"
                                id="CommunityOptions"
                                data-toggle="dropdown"
                                data-offset="0,5"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <span class="ps-dot"></span>
                            <span class="ps-dot"></span>
                            <span class="ps-dot"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right py-3"
                             aria-labelledby="CommunityOptions">
                            <CommunityAuthorOptions v-if="isAuthor" v-bind:community="community"/>
                            <CommunityUserOptions v-else v-bind:community="community"/>
                        </div>
                    </div>

                </div>
            </div>
            <Spinner v-else/>
        </div>
    </div>
</template>

<script>
    import PliziCommunity from '../../classes/PliziCommunity.js';
    import PliziCommunityAvatar from "../../classes/Community/PliziCommunityAvatar.js";

    import CommunitiesSubscribeMixin from "../../mixins/CommunitiesSubscribeMixin.js"

    import Spinner from "../../common/Spinner.vue";
    import CommunityAuthorOptions from "../../common/Communities/CommunityAuthorOptions.vue";
    import CommunityUserOptions from "../../common/Communities/CommunityUserOptions.vue";

    import PrivacyLabel from "./PrivacyLabel.vue";

    export default {
        name: "CommunityHeader",
        components: {Spinner, CommunityUserOptions, CommunityAuthorOptions, PrivacyLabel},
        mixins: [CommunitiesSubscribeMixin],
        props: {
            community: PliziCommunity,
            subscribeType: String,
        },
        computed: {
            isAuthor() {
                return this.community?.role === 'author';
            },
            headerImage() {
                return this.community?.headerImage?.image.normal.path || 'images/community-header-bg.jpg';
            },
            avatarThumb() {
                return this.community?.avatar?.image.thumb.path || this.community?.primaryImage;
            },
            avatarMedium() {
                return this.community?.avatar?.image.medium.path || this.community?.primaryImage;
            },
        },
        methods: {
            /**
             * @returns {boolean|FormData}
             */
            getFormData() {
                const fName = this.$refs.communityPrimaryImage.value;
                const fExt = fName.split('.').pop().toLowerCase();
                const allowExts = ['png', 'jpg', 'jpeg', 'bmp', 'webp', 'gif'];

                if (!allowExts.includes(fExt)) {
                    this.$alert(`<h4 class="text-white">Ошибка</h4>
<div class="alert alert-danger">
Недопустимое расширение у файла <b>${fName}</b><br />
Допустимы только: <b class="text-success">${allowExts.join(', ')}</b>
</div>`, `bg-danger`, 30);
                    return false;
                }

                const formData = new FormData();
                formData.append('file', this.$refs.communityPrimaryImage.files[0]);
                formData.append('id', this.community.id);
                this.$refs.communityPrimaryImage.value = '';

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
            async uploadPrimaryImage() {
                if (!this.isAuthor)
                    return;

                const formData = this.getFormData();

                if (!formData) {
                    return;
                }

                const {size} = formData.get('file');

                if (size > 2000000) {
                    this.showErrorOnLargeFile();
                    return;
                }

                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$communities.updatePrimaryImage(formData);
                } catch (e) {
                    if (e.status === 422) {
                        this.showErrorOnLargeFile();
                        return;
                    }

                    window.console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    this.community.avatar = new PliziCommunityAvatar(apiResponse.data);
                }
            },
        }
    }
</script>
