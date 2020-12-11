<template>
    <div class="modal" id="postEditModal" tabindex="-1" role="dialog" aria-labelledby="postEditModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hidePostRepostModal">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <div id="resendMessageModalBody" class="modal-body p-4">
                    <div class="form-group d-none">
                        <multiselect v-model="selectedShareList"
                                     :options="shareList"
                                     :searchable="true"
                                     trackBy="code"
                                     label="title"
                                     :close-on-select="true"
                                     :show-labels="false"
                                     :multiple="false"
                                     placeholder="Выберите получателя">
                        </multiselect>
                    </div>
                    <div class="form-group mb-4 post-repost-item">
                        <PostRepostItem :post="post"
                                        :user="user"
                                        :community="community"/>
                    </div>
                    <button type="button" class="btn plz-btn plz-btn-primary mt-4" @click.prevent="sharePostToWall">
                        Отправить себе на стену
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PostRepostItem from "./PostRepostItem.vue";

    import PliziUser from '../../classes/PliziUser.js';
    import PliziCommunity from '../../classes/PliziCommunity.js';
    import PliziPost from '../../classes/PliziPost.js';

    export default {
        name: "PostRepostModal",
        components: {
            PostRepostItem,
        },
        props: {
            user: PliziUser,
            community: PliziCommunity,
            post: PliziPost,
        },
        data() {
            return {
                selectedShareList: {
                    code: 'myWall',
                    title: 'На мою стену',
                },
                shareList: [
                   {
                       code: 'myWall',
                       title: 'На мою стену',
                   },
                ],
            }
        },
        methods: {
            hidePostRepostModal() {
                this.$emit('hidePostRepostModal');
            },

            async sharePostToWall() {
                if (!this.selectedShareList) return;

                let response;

                try {
                    if (this.selectedShareList.code === 'myWall') {
                        response = await this.$root.$api.$post.sharePostToWall(this.post.id);
                    }
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (response) {
                    this.hidePostRepostModal();
                }
            },
        },
    }
</script>

