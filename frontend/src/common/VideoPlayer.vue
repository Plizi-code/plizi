<template>
    <videoPlayer :options="generatePlayerOptions"
                 @play="onPlay($event)"
                 @ready="playerReady"
                 class="w-100"/>
</template>

<script>
    // @link https://github.com/surmon-china/vue-video-player

    import 'video.js/dist/video-js.css';
    import {videoPlayer} from 'vue-video-player';
    import 'videojs-youtube/dist/Youtube.min';

    import LinkMixin from "../mixins/LinkMixin.js";

    export default {
        name: "VideoPlayer",
        components: {
            videoPlayer,
        },
        mixins: [LinkMixin],
        props: {
            videoLink: {
                type: String,
                default: null,
            },
        },
        computed: {
            detectYoutubeLink() {
                let str = this.videoLink.replace(/<\/?[^>]+>/g, '').trim();
                let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
                let match = str.match(regExp);

                return (match && match[7].length === 11) ? str : false;
            },
            generatePlayerOptions() {
                return {
                    techOrder: this.detectYoutubeLink ? ["youtube"] : null,
                    sources: [{
                        type: this.detectYoutubeLink ? 'video/youtube' : 'video/mp4',
                        src: this.detectYoutubeLink ? this.detectYoutubeLink : null,
                    }],
                    youtube: this.detectYoutubeLink ? { "iv_load_policy": 1 } : null,
                }
            },
            getTime() {
                return Number(this.getParameterByName('t', this.detectYoutubeLink));
            },
        },
        data() {
            return {
                isSetStatedTime: false,
            }
        },
        methods: {
            onPlay(player) {
                if (!this.isSetStatedTime) {
                    player.currentTime(this.getTime);
                    this.isSetStatedTime = true;
                }
            },
            playerReady(player) {},
        },
    }
</script>

