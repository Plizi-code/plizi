<template>
    <div class="modal" id="postLikeModal" tabindex="-1" role="dialog" aria-labelledby="postLikeModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hideLikeModal">

        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable"
             role="document"
             @click.stop="">
            <div class="modal-content bg-white-br20">
                <div id="postLikeBody" class="modal-body p-4">
                    <div class="row">
                        <div class="col-4 col-lg-2 text-center mb-3 d-flex align-items-stretch text-center"
                             v-for="(user, index) in users"
                             :key="index">
                            <router-link :to="{ name: 'PersonalPage', params: { id: user.id } }"
                                         class="post-like-body-box text-dark d-flex flex-column w-100">
                                <p class="post-like-body-desc mb-1">{{ user.profile.firstName + ' ' + user.profile.lastName }}</p>
                                <div class="post-like-body-pic">
                                    <img :src="user.profile.userPic"
                                         class="post-like-body-img rounded-circle mt-auto"
                                         :alt="user.profile.firstName + ' ' + user.profile.lastName"
                                         :title="user.profile.firstName + ' ' + user.profile.lastName">
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PliziUser from "../../classes/PliziUser.js";

export default {
name: "PostLikeModal",
props: {
    postId: Number,
},
data() {
    return {
        isStarted: false,
        noMore: false,
        users: [],
    }
},
methods: {
    hideLikeModal() {
        this.$emit('hideLikeModal');
    },

    async getUsersLikes(limit = 20, offset = 0) {
        let response = null;

        try{
            response = await this.$root.$api.$post.getUsersLikes(this.postId, limit, offset);
        } catch (e){
            console.warn( e.detailMessage );
        }

        if ( response !== null ) {
            response.map((post) => {
                this.users.push(new PliziUser(post));
            });

            return response.length;
        }
    },
    async lazyLoadLikes() {
        if (this.isStarted || this.noMore) return;

        this.isStarted = true;
        let oldSize = this.users.length;
        let added = 0;

        if (oldSize) {
            added = await this.getUsersLikes(10, oldSize++);
        }

        if (added === 0) {
            this.noMore = true;
        }

        this.isStarted = false;
        this.onScrollYPageLikes();
    },
    async onScrollYPageLikes() {
        let body = document.querySelector('#postLikeBody');

        if (body.scrollTop >= (body.scrollHeight - body.clientHeight - (body.clientHeight / 2))) {
            await this.lazyLoadLikes();
        }
    },
},
async mounted() {
    await this.getUsersLikes();

    let body = document.querySelector('#postLikeBody');
    body.addEventListener('scroll', this.onScrollYPageLikes);
},
beforeRouteLeave(to, from, next) {
    let body = document.querySelector('#postLikeBody');
    body.removeEventListener('scroll', this.onScrollYPageLikes);
    next();
},
}
</script>

