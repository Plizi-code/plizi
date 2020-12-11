const LazyLoadPosts = {
    data() {
        return {
            isStarted: true,
            noMore: true,
        }
    },
    methods: {
        async onScrollYPage() {
            if (window.scrollY >= (document.body.scrollHeight - document.documentElement.clientHeight - (document.documentElement.clientHeight / 2))) {
                await this.lazyLoadPosts();
            }
        },
        async lazyLoadPosts() {
            if (this.isStarted || this.noMore) return;

            this.isStarted = true;
            let oldSize = this.posts.length;
            let added = 0;

            if (oldSize) {
                added = await this.getPosts(10, oldSize++);
            }

            if (added === 0) {
                this.noMore = true;
            }

            this.isStarted = false;
            this.onScrollYPage();
        },
    },

    async mounted() {
        window.addEventListener('scroll', this.onScrollYPage);
    },

    beforeRouteLeave(to, from, next) {
        window.removeEventListener('scroll', this.onScrollYPage);
        next();
    },

    async beforeRouteUpdate(to, from, next) {
        next();

        window.addEventListener('scroll', this.onScrollYPage);
    },
};

export {LazyLoadPosts as default}
