<template>
    <div id="communityVideos" class="bg-white-br20 mb-5 mb-4 py-3 px-4 d-none d-lg-block" v-if="videos.length > 0">

        <h6 class="plz-community-participants-title w-auto mb-4">Видео
            <!--                            <span class="plz-community-participants-subtitle ml-2">14</span>-->
        </h6>

        <div class="videos-item mb-4" v-for="video in videos">
            <CommunityVideoItem
                :key="video.id"
                :link="video.link"
                @openVideoModal="openVideoModal"/>
        </div>

        <div class="text-center" v-if="totalCount > videos.length">
            <router-link tag="a"
                         class="plz-community-header-desc "
                         :to="`/community-videos-`+communityId"><small>Все видео</small></router-link>
        </div>
    </div>
</template>

<script>
    import PliziVideo from "../../classes/PliziVideo.js";
    import CommunityVideoItem from "./CommunityVideoItem";

    export default {
        name: "CommunityVideoBlock",
        components: {
            CommunityVideoItem
        },
        data() {
            return {
                totalCount: 0,
                videos: [],
                isLoaded: true,
            }
        },
        props: {
            communityId: Number,
            avatarMedium: String,
        },
        methods: {
            async getVideoList() {
                let apiResponse;
                this.isLoaded = false;

                try {
                    apiResponse = await this.$root.$api.$communities.videos(this.communityId, 2);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    this.totalCount = apiResponse.totalCount;
                    apiResponse.list.map((video) => {
                        this.videos.push(new PliziVideo(video));
                    });
                    this.isLoaded = true;
                }
            },
            openVideoModal(evData) {
                this.$emit('openVideoModal', evData);
            },
        },
        mounted() {
            this.getVideoList();
        }
    }
</script>
