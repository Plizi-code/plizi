<template>
    <div class="row plz-post-item mb-4 bg-white-br20 py-4">

        <template v-if="!post.deleted">
            <div class="col-12 border-bottom plz-post-item-header">
                <div class="post-news-item d-flex flex-row align-content-center pb-4">
                    <div class="post-poster-pic mr-3">
                        <router-link v-if="this.post.user" :to="{name: 'PersonalPage', params: {id: postable.id}}">
                            <img :src="post.posterPic" :alt="post.posterName"/>
                        </router-link>
                        <router-link v-else :to="{name: 'CommunityPage', params: {id: postable.id}}">
                            <img :src="communityAvatar" :alt="post.posterName"/>
                        </router-link>
                    </div>

                    <div class="post-poster-name d-flex flex-column justify-content-center">
                        <h6 class="post-poster-title mb-1">
                            <!-- TODO: @TGA странно что мы нигде не выводим название поста-->

                            <router-link v-if="this.post.user" :to="{name: 'PersonalPage', params: {id: postable.id}}">
                                <b>{{post.posterName}}</b>
                            </router-link>
                            <router-link v-else :to="{name: 'CommunityPage', params: {id: postable.id}}">
                                <b>{{post.posterName}}</b>
                            </router-link>
                        </h6>
                        <time :datetime="post.createdAt" class="post-poster-time">
                            {{ post.createdAt | lastPostTime }}
                        </time>
                    </div>

                    <div v-if="post.author.id === user.id || isAdmin" class="post-poster-actions my-auto ml-auto">
                        <button class="btn btn-link post-settings"
                                :id="`postSettings` + post.id"
                                type="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right py-3 px-0"
                             :aria-labelledby="`postSettings` + post.id">

                            <div v-if="post.author.id === user.id" class="nav-item">
                                <button class="btn dropdown-item text-left px-3 py-1"
                                        @click="$emit('onEditPost', post)">
                                    Редактировать
                                </button>
                            </div>
                            <div class="nav-item">
                                <button class="btn dropdown-item text-left px-3 py-1"
                                        @click="$emit('onDeletePost', post.id)">
                                    Удалить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template v-if="recursivePosts && recursivePosts.length">
                <div v-for="(recursivePost, index) in recursivePosts"
                     :key="index" class="sharedFrom pt-3 pl-3">

                    <div class="col-12 plz-post-item-header">

                            <div class="post-news-item d-flex flex-row align-content-center shared  pb-2">

                                <router-link v-if="recursivePost.user"
                                             :to="{name: 'PersonalPage', params: {id: recursivePost.user.id}}"
                                             class="post-poster-pic mr-3">
                                    <img :src="recursivePost.posterPic" :alt="recursivePost.posterName"/>
                                </router-link>
                                <router-link v-else :to="{name: 'CommunityPage', params: {id: recursivePost.community.id}}"
                                             class="post-poster-pic mr-3">
                                    <img :src="recursiveCommunityAvatar" :alt="recursivePost.posterName"/>
                                </router-link>

                                <div class="post-poster-name d-flex flex-column justify-content-center">
                                    <h6 class="post-poster-title mb-1">
                                        <!-- TODO: @TGA странно что мы нигде не выводим название поста-->

                                        <router-link v-if="recursivePost.user"
                                                     :to="{name: 'PersonalPage', params: {id: recursivePost.user.id}}">
                                            <b>{{recursivePost.posterName}}</b>
                                        </router-link>
                                        <router-link v-else
                                                     :to="{name: 'CommunityPage', params: {id: recursivePost.community.id}}">
                                            <b>{{recursivePost.posterName}}</b>
                                        </router-link>
                                    </h6>
                                    <time :datetime="recursivePost.createdAt" class="post-poster-time">
                                        {{ recursivePost.createdAt | lastPostTime }}
                                    </time>
                                </div>
                            </div>
                        </div>

                    <div v-if="recursivePost.body" class="col-12 plz-post-item-body pt-2">
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

                    <div v-if="recursivePost.attachments" class="col-12 plz-post-item-images">
                            <div class="post-images shared">
                                <Gallery type="gallery" :post="post" v-if="recursivePost.imageAttachments.length > 0" :images="recursivePost.imageAttachments"></Gallery>

                                <template v-for="(postAttachment) in recursivePost.attachments">
                                    <template v-if="!postAttachment.isImage">
                                        <AttachmentFile :attach="postAttachment"/>
                                    </template>
                                </template>
                            </div>
                        </div>

                </div>
            </template>

            <div class="col-12 plz-post-item-body pb-2"
                 :class="{'--px-2 --pt-2': recursivePosts.length, 'pt-4': !recursivePosts.length}">
                <template v-if="livePreview && typeof livePreview === 'object'">
                    <p v-if="livePreview.text"
                       class="post-main-text mb-0"
                       v-html="livePreview.text">
                    </p>

                    <template v-if="livePreview.videoLinks">
                        <div class="youtube-video-link d-flex justify-content-center">
                            <p class="post-main-text  mt-2"
                               v-html="livePreview.videoLinks"
                               @click.stop="hasYoutubeLinks ? openVideoModal(true) : null">
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
            </div>

            <div class="col-12 plz-post-item-images">
                <div class="post-images">
                    <Gallery v-if="post.imageAttachments.length > 0"
                             type="gallery"
                             :post="post"
                             :images="post.imageAttachments"/>

                    <template v-for="(postAttachment) in post.attachments">
                        <template v-if="!postAttachment.isImage">
                            <AttachmentFile :attach="postAttachment"/>
                        </template>
                    </template>
                </div>
            </div>

            <div class="plz-post-item-footer col-12 pt-4">
                <div class="d-flex">
                    <div class="d-flex">
                        <div class="post-watched-counter"
                             :class="{'is-active': post.alreadyLiked}"
                             @click="onLike">
                            <IconFillHeard v-if="post.alreadyLiked"/>
                            <IconHeard v-else/>
                            <span>{{ post.likes | space1000 }}</span>
                            <div v-if="post.usersLikes && post.usersLikes.length" class="usersLikes p-3" @click.stop="">
                                <p class="mb-1">
                                    <b @click.stop="$emit('onShowUsersLikes', post.id)"
                                       style="cursor: pointer">
                                        Понравилось
                                    </b>
                                    {{ post.likes }} пользователям
                                </p>
                                <div class="d-flex mb-0">
                                    <router-link v-for="(user, index) in shortUsersLikes"
                                                 :key="index"
                                                 class="usersLikes-user m-1"
                                                 :to="{ name: 'PersonalPage', params: { id: user.id } }">
                                        <img :src="user.profile.userPic"
                                             :alt="user.profile.firstName + ' ' + user.profile.lastName"
                                             :title="user.profile.firstName + ' ' + user.profile.lastName"
                                             class="rounded-circle">
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="post-watched-counter ml-4" @click="getCommentsByPostId">
                            <IconMessage/>
                            <span>{{ post.commentsCount | space1000 }}</span>
                        </div>

                        <div class="post-watched-counter ml-4" @click="$emit('onShare', post)">
                            <IconShare/>
                            <span>{{ post.sharesCount | space1000 }}</span>
                        </div>
                    </div>

                    <div class="ml-auto d-flex align-items-center">
                        <div class="post-watched-counter">
                            <span>{{ post.views | space1000 }}</span>
                            <IconEye/>
                        </div>
                    </div>
                </div>
                <template v-if="!isShowComment">
                    <div class="plz-comments" v-for="comment in comments">
                        <CommentItem
                            :key="comment.id"
                            type="post"
                            :comment="comment"
                            :postId="post.id"
                            @onDelete="removeComment"
                            @update="editComment"
                            :attachments="comment.attachments"
                        ></CommentItem>
                    </div>

                    <CommentTextField type="post" :postId="post.id" @updateComments="addNewComment"></CommentTextField>
                </template>
            </div>
        </template>

        <template v-else>
            <div class="col-12">
                <div class="post-deleted text-center">
                    <p>Запись удалена.</p>
                    <button class="btn btn-secondary"
                            @click="$emit('onRestorePost', post.id)">
                        Восстановить запись
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import IconEye from '../../icons/IconEye.vue';
    import IconHeard from '../../icons/IconHeard.vue';
    import IconFillHeard from '../../icons/IconFillHeard.vue';
    import IconMessage from '../../icons/IconMessage.vue';
    import IconMessageUserPost from '../../icons/IconMessageUserPost.vue';
    import IconShare from '../../icons/IconShare.vue';
    import IconYoutube from "../../icons/IconYoutube.vue";
    import CommentTextField from "../../components/Comments/CommentTextField.vue";
    import CommentItem from "../../components/Comments/CommentItem.vue";
    import PostImage from './PostImage.vue';

    import AttachmentFile from "../AttachmentFile.vue";
    import PliziPost from '../../classes/PliziPost.js';
    import Gallery from '../Gallery/Gallery.vue';
    import LinkMixin from '../../mixins/LinkMixin.js';
    import AvatarMixin from '../../mixins/AvatarMixin.js';
    import PliziComment from "../../classes/PliziComment.js";

    export default {
        name: 'Post',
        components: {
            CommentTextField,
            CommentItem,
            Gallery,
            IconShare,
            IconMessage,
            IconHeard,
            IconFillHeard,
            IconEye,
            IconMessageUserPost,
            IconYoutube,
            PostImage,
            AttachmentFile,
        },
        props: {
            post: PliziPost,

            isCommunity: {
                type: Boolean,
                default: false,
            },
            isAdmin: {
                type: Boolean,
                default: false,
            },
        },
        mixins: [LinkMixin, AvatarMixin],
        data() {
            return {
                recursivePostsSimple: [],
                recursivePosts: [],
                isShowComment: true,
                comments: [],
            }
        },
        computed: {
            hasYoutubeLinks() {
                let str = this.post.body.replace(/<\/?[^>]+>/g, '').trim();

                return str ? this.detectYoutubeLinks(str) : null;
            },
            livePreview() {
                let str = this.post.body.replace(/<\/?[^>]+>/g, '').trim();

                return str ? this.transformStrWithLinks(str, 'hqdefault') : null;
            },
            postable() {
                if (this.post.community) {
                    return this.post.community;
                }

                return this.post.author;
            },
            user() {
                return this.$root.$auth.user;
            },
            shortUsersLikes() {
                return this.post.usersLikes && this.post.usersLikes.length ? this.post.usersLikes.slice(0, 8) : null;
            },
            communityAvatar() {
                return this.getCommunityAvatar(this.post?.community);
            },
            recursiveCommunityAvatar() {
                return this.getCommunityAvatar(this.post?.sharedFrom?.community);
            },
        },
        methods: {
            editComment(newComment) {
                this.comments = this.comments.map(comment => comment.id === newComment.id ? comment.update(newComment) : comment);
            },
            removeComment(commentId) {
                this.comments = this.comments.filter(comment => comment.id !== commentId);
                this.post.commentsCount = this.comments.length;
            },
            addNewComment(newComment) {
                this.comments.push(new PliziComment(newComment));
                this.post.commentsCount = this.comments.length;
            },
            openVideoModal(shared = false) {
                let videoLink;

                if (shared) {
                    videoLink = this.detectYoutubeLinks(this.post.body.replace(/<\/?[^>]+>/g, '').trim())[0];
                } else {
                    videoLink = this.detectYoutubeLinks(this.post.sharedFrom.body.replace(/<\/?[^>]+>/g, '').trim())[0];
                }

                this.$emit('openVideoModal', {
                    videoLink,
                })
            },
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

                return this.transformStrWithLinks(str, 'hqdefault');
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

            async onLike() {
                let response = null;

                try {
                    response = await this.$root.$api.$post.likePost(this.post.id);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response !== null) {
                    if (this.post.alreadyLiked) {
                        this.post.alreadyLiked = false;
                        this.post.likes--;
                        let userLikeIndex = this.post.usersLikes.findIndex((userLike) => {
                            return userLike.id === this.$root.$auth.user.id;
                        });
                        this.post.usersLikes.splice(userLikeIndex, 1);
                    } else {
                        this.post.alreadyLiked = true;
                        this.post.likes++;
                        this.post.usersLikes.push(this.$root.$auth.user);
                    }
                }
            },
            async getCommentsByPostId() {
                this.isShowComment = !this.isShowComment;
                if (this.isShowComment) {
                    return;
                }
                try {
                    let response = await this.$root.$api.$post.getCommentsById(this.post.id);
                    this.comments = response.data.list.map(comment => new PliziComment(comment));
                } catch (e) {
                    console.warn(e.detailMessage);
                }
            },
        },
        mounted() {
            if (this.post) {
                this.recursiveParent(this.post);
            }
        },
    }
</script>
