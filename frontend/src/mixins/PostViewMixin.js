const PostViewMixin = {

methods: {

    /**
     * https://github.com/scaccogatto/vue-waypoint
     * @param {PliziPost} post
     * @param going
     * @param direction
     */
    postIsRead(post, going, direction){
        if (! direction)
            return;

        if (! post)
            return;

        if (going !== 'in')
            return;

        if (direction !== 'top')
            return;

        if (post.alreadyViewed)
            return;

        const sendData= {
            postId : post.id,
            userId : this.$root.$auth.user.id,
            views : post.views,
        };

        //window.console.log(sendData);

        this.incrementPostViewCounter(post.id, this.$root.$auth.user.id);
    },

    async incrementPostViewCounter(postId, userId){
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$post.addView( postId, userId );
        }
        catch (e){
            window.console.warn( e.detailMessage );
            throw e;
        }
    },
}

};

export {PostViewMixin as default}
