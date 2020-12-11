<template>
    <div class="post-repost pl-3 ml-1 mt-3">
        <div class="post-user-data  d-flex align-items-center mb-2">
            <div class="media-pic border rounded-circle  mr-3">
                <img :src="user ? user.userPic : community.primaryImage" :alt="user ? user.firstName : community.name" />
            </div>
            <div class="media-body">
                <h6 class="chatHeader-title w-75 align-self-start mt-2 pb-0 mb-0 pull-left text-body" style="line-height: 20px;">{{ user ? user.firstName : community.name }}</h6>
            </div>
        </div>

        <template v-if="livePreview && typeof livePreview === 'object'">
            <p v-if="livePreview.text"
               class="post-main-text mb-0"
               v-html="livePreview.text">
            </p>

            <template v-if="livePreview.videoLinks">
                <div class="youtube-video-link d-flex justify-content-center">
                    <p class="post-main-text  mt-2"
                       v-html="livePreview.videoLinks">
                    </p>
                    <button class="video__button" type="button" aria-label="Запустить видео">
                        <IconYoutube/>
                    </button>
                </div>
            </template>
        </template>

        <template v-else>
            <p v-if="post.body"
               class="post-main-text mb-2"
               v-html="this.$options.filters.toBR(post.body)"></p>
        </template>

        <div v-if="post.attachments && post.attachments.length" class="attachments">
            <Gallery :post="post" v-if="imageAttachments.length > 0" :images="imageAttachments"></Gallery>

            <template v-for="(postAttachment) in post.attachments">
                <template v-if="!postAttachment.isImage">
                    <AttachmentFile :attach="postAttachment"/>
                </template>
            </template>
        </div>

        <template v-if="recursivePosts && recursivePosts.length">
            <div v-for="(recursivePost, index) in recursivePosts"
                 :key="index" class="sharedFrom pt-3 pl-3">

                <template v-if="recursivePost.body && recursivePost.attachments">
                    <div class="col-12 plz-post-item-header">

                        <div class="post-news-item d-flex flex-row align-content-center shared  pb-2">

                            <router-link v-if="recursivePost.user"
                                         :to="{name: 'PersonalPage', params: {id: recursivePost.user.id}}"
                                         class="post-poster-pic mr-3 media-pic border rounded-circle">
                                <img :src="recursivePost.posterPic" :alt="recursivePost.posterName"/>
                            </router-link>
                            <router-link v-else :to="{name: 'CommunityPage', params: {id: recursivePost.community.id}}"
                                         class="post-poster-pic mr-3 media-pic border rounded-circle">
                                <img :src="recursivePost.posterPic" :alt="recursivePost.posterName"/>
                            </router-link>

                            <div class="post-poster-name d-flex flex-column justify-content-center media-body">
                                <h6 class="post-poster-title mb-1 chatHeader-title w-75">
                                    <!-- TODO: @TGA странно что мы нигде не выводим название поста-->

                                    <router-link v-if="recursivePost.user"
                                                 :to="{name: 'PersonalPage', params: {id: recursivePost.user.id}}"
                                                 class="text-body">
                                        <b>{{recursivePost.posterName}}</b>
                                    </router-link>
                                    <router-link v-else
                                                 :to="{name: 'CommunityPage', params: {id: recursivePost.community.id}}"
                                                 class="text-body">
                                        <b>{{recursivePost.posterName}}</b>
                                    </router-link>
                                </h6>
                                <time :datetime="recursivePost.createdAt" class="post-poster-time">
                                    {{ recursivePost.createdAt | lastPostTime }}
                                </time>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 plz-post-item-body pt-2">
                        <template v-if="recursivePost.livePreview && typeof recursivePost.livePreview === 'object'">
                            <p v-if="recursivePost.livePreview.text"
                               class="post-main-text shared mb-0"
                               v-html="recursivePost.livePreview.text">
                            </p>

                            <template v-if="recursivePost.livePreview.videoLinks">
                                <div class="youtube-video-link d-flex justify-content-center">
                                    <p class="post-main-text  mt-2"
                                       v-html="recursivePost.livePreview.videoLinks"
                                       @click.stop="recursivePost.livePreview ? openVideoModal() : null">
                                    </p>
                                    <button class="video__button" type="button" aria-label="Запустить видео">
                                        <svg width="68" height="48" viewBox="0 0 68 48">
                                            <path class="video__button-shape"
                                                  d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                                            <path class="video__button-icon" d="M 45,24 27,14 27,34"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </template>

                        <template v-else>
                            <p v-if="recursivePost.body"
                               class="post-main-text shared mb-0"
                               v-html="recursivePost.body"></p>
                        </template>
                    </div>

                    <div class="col-12 plz-post-item-images attachments">
                        <div class="post-images shared">
                            <Gallery :post="post" v-if="recursivePost.imageAttachments.length > 0" :images="recursivePost.imageAttachments"></Gallery>

                            <template v-for="(postAttachment) in recursivePost.attachments">
                                <template v-if="!postAttachment.isImage">
                                    <AttachmentFile :attach="postAttachment"/>
                                </template>
                            </template>
                        </div>
                    </div>
                </template>

            </div>
        </template>
    </div>
</template>

<script>
    import PliziUser from '../../classes/PliziUser.js';
    import PliziCommunity from '../../classes/PliziCommunity.js';
    import PliziPost from '../../classes/PliziPost.js';
    import LinkMixin from '../../mixins/LinkMixin.js';

    import Gallery from '../Gallery/Gallery.vue';
    import AttachmentFile from "../AttachmentFile.vue";
    import IconYoutube from "../../icons/IconYoutube.vue";

    export default {
        name: "PostRepostItem",
        props: {
            user: PliziUser,
            community: PliziCommunity,
            post: PliziPost,
        },
        mixins: [LinkMixin],
        components: {
            Gallery,
            AttachmentFile,
            IconYoutube,
        },
        computed: {
            imageAttachments() {
                return this.post.attachments.filter(attachment => attachment.isImage);
            },
            sharedFromImageAttachments() {
                return this.post.sharedFrom.attachments.filter(attachment => attachment.isImage);
            },
            livePreview() {
                let str = this.post.body.replace(/<\/?[^>]+>/g, '').trim();

                return this.transformStrWithLinks(str);
            },
            sharedFromLivePreview() {
                let str = this.post.sharedFrom.body.replace(/<\/?[^>]+>/g, '').trim();

                return this.transformStrWithLinks(str);
            },
            hasYoutubeLinks() {
                let str = this.post.body.replace(/<\/?[^>]+>/g, '').trim();

                return this.detectYoutubeLinks(str);
            },
            hasSharedFromYoutubeLinks() {
                let str = this.post.sharedFrom.body.replace(/<\/?[^>]+>/g, '').trim();

                return this.detectYoutubeLinks(str);
            },
            sharedFromUser() {
                return this.post.sharedFrom ? this.post.sharedFrom.user : null;
            },
        },
        data() {
            return {
                recursivePostsSimple: [],
                recursivePosts: [],
            }
        },
        methods: {
            recursiveParent(post) {
                if (!post) return;

                if (this.post.id === post.id) {
                    this.recursiveParent(post.sharedFrom);
                    return;
                }

                if (!post.sharedFrom) {
                    this.recursivePostsSimple.push(post);
                    this.transformRecursivePosts();
                } else {
                    this.recursivePostsSimple.push(post);
                    this.recursiveParent(post.sharedFrom);
                }
            },
            recursiveLivePreview(str) {
                str = str.replace(/<\/?[^>]+>/g, '').trim();

                return this.transformStrWithLinks(str);
            },
            recursiveHasYoutubeLinks(str) {
                str = str.replace(/<\/?[^>]+>/g, '').trim();

                return this.detectYoutubeLinks(str);
            },
            recursiveImageAttachments(attachments) {
                return attachments.filter(attach => attach.isImage);
            },
            transformRecursivePosts() {
                this.recursivePostsSimple.forEach((recursivePost) => {
                    this.recursivePosts.push({
                        id: recursivePost.id,
                        body: recursivePost.body,
                        user: recursivePost.user,
                        posterPic: recursivePost.posterPic,
                        posterName: recursivePost.posterName,
                        community: recursivePost.community,
                        createdAt: recursivePost.createdAt,
                        livePreview: this.recursiveLivePreview(recursivePost.body),
                        hasYoutubeLinks: this.recursiveHasYoutubeLinks(recursivePost.body),
                        imageAttachments: this.recursiveImageAttachments(recursivePost.attachments),
                        attachments: recursivePost.attachments,
                    });
                });
            },
        },
        mounted() {
            if (this.post) {
                this.recursiveParent(this.post);
            }
        },
    }
</script>

