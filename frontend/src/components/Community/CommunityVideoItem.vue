<template>
    <span v-if="livePreview && typeof livePreview === 'object'">
        <p v-if="livePreview.text"
           class="post-main-text mb-0"
           v-html="livePreview.text">
        </p>

        <template v-if="livePreview.videoLinks">
            <div class="youtube-video-link d-flex justify-content-center">
                <p class="post-main-text  mt-2"
                   v-html="livePreview.videoLinks"
                   @click.stop="openVideoModal(true)">
                </p>
                <button class="video__button" type="button" aria-label="Запустить видео">
                    <IconYoutube/>
                </button>
            </div>
        </template>
    </span>
</template>

<script>
    import LinkMixin from "../../mixins/LinkMixin.js";
    import IconYoutube from "../../icons/IconYoutube.vue";

    export default {
        name: "CommunityVideoItem",
        components: {IconYoutube},
        mixins: [LinkMixin],
        props: {
            link: String,
        },
        computed: {
            livePreview() {
                let str = this.link.replace(/<\/?[^>]+>/g, '').trim();

                return this.transformStrWithLinks(str);
            },
        },
        methods: {
            openVideoModal(shared = false) {
                const videoLink = this.detectYoutubeLinks(this.link.replace(/<\/?[^>]+>/g, '').trim())[0];

                this.$emit('openVideoModal', {
                    videoLink,
                });
            },
        }
    }
</script>
