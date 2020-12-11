<template>
    <div class="plz-gallery-description d-flex flex-column justify-content-between">
        <div class="plz-gallery-description-header px-4 pt-4 pb-0">
            <div class="plz-gallery-description--holder d-flex">
                <div class="plz-gallery-description--holder-avatar mr-3">
                    <img class="plz-gallery-description--holder-avatar-item" :src="userAvatar" :alt="userName +' '+ userSurname">
                </div>
                <div class="plz-gallery-description--holder-data d-flex flex-column w-100 justify-content-center">
                    <p class="plz-gallery-description--holder-data-author d-flex mb-0">
                        {{ userName +' '+ userSurname }}
                    </p>
                    <p class="plz-gallery-description--holder-data-time mb-0">
                        {{getTimePost}}
                    </p>
                </div>
                <div class="post-poster-actions my-auto ml-auto">
                    <button class="btn btn-link post-settings"
                            :id="`postSettings` + getTimePost"
                            type="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        <i class="dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right py-3 px-0"
                         :aria-labelledby="`postSettings` + getTimePost">

                        <div class="nav-item">
                            <button class="btn dropdown-item text-left px-3 py-1">
                                Редактировать
                            </button>
                        </div>
                        <div  class="nav-item">
                            <button class="btn dropdown-item text-left px-3 py-1">
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="plz-gallery-description--post-data d-flex">
                <div class="plz-gallery-description--post-data-option post-watched-counter"
                     :class="{'is-active': image.alreadyLiked}"
                     @click="onLike">
                    <IconFillHeard v-if="image.alreadyLiked"/>
                    <IconHeard v-else/>
                    <span>{{ image.likes | space1000 }}</span>
                </div>
                <div class="plz-gallery-description--post-data-option post-watched-counter ml-4">
                    <IconMessage/>
                    <span>{{ comments.length }}</span>
                </div>
                <div class="plz-gallery-description--post-data-option post-watched-counter ml-4">
                    <IconShare/>
                    <span>{{ post.sharesCount | space1000 }}</span>
                </div>
            </div>
            <div class="plz-gallery-description-comment-wrapper">
                <div class="plz-gallery-description-comment-item" v-for="comment in comments">
                    <CommentItem :key="comment.id"
                                 :comment="comment"
                                 :post-id="postIdForComment"
                                 @onDelete="removeComment"
                                 @update="editComment"
                                 :attachments="comment.attachments">
                    </CommentItem>
                </div>
            </div>
        </div>
        <CommentTextField class="plz-gallery-description-write-comment"
                        :post-id="postIdForComment"
                        @updateComments="addNewComment"
                        :imageId="image.id"
                        :type="type">
        </CommentTextField>
    </div>
</template>

<script>
import moment from "moment";

import CommentTextField from "../../components/Comments/CommentTextField.vue";
import CommentItem from "../../components/Comments/CommentItem.vue";
import TextEditor from "../TextEditor.vue";

import IconHeard from "../../icons/IconHeard.vue";
import IconFillHeard from '../../icons/IconFillHeard.vue';
import IconMessage from "../../icons/IconMessage.vue";
import IconShare from "../../icons/IconShare.vue";

import PliziAttachment from '../../classes/PliziAttachment.js';
import PliziComment from "../../classes/PliziComment.js";

 export default {
  name: "GalleryDescription",
  components: {
      CommentTextField,
      CommentItem, TextEditor, IconShare, IconMessage, IconHeard, IconFillHeard},
  props: {
   comments: {
       type: Array,
       required: true,
   },
   post: {
    type: Object,
   },
  image: {
      type: PliziAttachment,
      default: null,
  },
  type: {
       type: String,
  },
  },
  computed: {
   userAvatar() {
       if (this.getUserData.profile.avatar === null) {
           return this.image.__defaultAvatarPath;
       }

       return this.getUserData.profile.avatar.image.thumb.path;
   },
    postIdForComment() {
      return this.post?.sharedFrom?.id || this.post.id;
    },
   userName() {
    return this.getUserData.profile.firstName;
   },
   userSurname() {
    return this.getUserData.profile.lastName;
   },
   getTimePost() {
    return moment(this.post.createdAt).fromNow();
   },
  getUserData() {
      return this.$root.$auth.user;
  },
  },
  methods: {
      addNewComment(newComment) {
          this.updateComments([...this.comments, new PliziComment(newComment)]);
      },
      editComment(newComment) {
          const comments = this.comments.map(comment => comment.id === newComment.id ? comment.update(newComment) : comment);
          this.updateComments(comments);
      },
      removeComment(commentId) {
          const comments = this.comments.filter(comment => comment.id !== commentId);
          this.updateComments(comments);
      },
      updateComments(comments) {
          this.$emit('updateComments', { comments, id: this.image.id });
      },
    async onLike() {
        let response = null;

        try {
            response = await this.$root.$api.$image.likePostImage(this.post.id, this.image.id);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response !== null) {
            if (this.image.alreadyLiked) {
                this.image.alreadyLiked = false;
                this.image.likes--;
                let userLikeIndex = this.image.usersLikes.findIndex((userLike) => {
                    return userLike.id === this.$root.$auth.user.id;
                });
                this.image.usersLikes.splice(userLikeIndex, 1);
            } else {
                this.image.alreadyLiked = true;
                this.image.likes++;
                this.image.usersLikes.push(this.$root.$auth.user);
            }
        }
    }
   },
 }
</script>
