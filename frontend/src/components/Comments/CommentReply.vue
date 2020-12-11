<template>
<div class="plz-comment-reply"  v-if="isSending">
    <div class="plz-comment-post">
        <div class="plz-comment-item-data-pic mr-3">
            <img class="plz-comment-item-data-img" :src="userData.userPic" alt="">
        </div>
        <TextEditor :clazz="`plz-text-editor h-auto plz-comment-post-text-field align-items-start flex-grow-1 `"
                    :dropToDown="true"
                    :maximumCharacterLimit="500"
                    workMode="comment"
                    @editorPost="onTextPost"
                    :input-editor-text="name + ','">
        </TextEditor>
    </div>
</div>
</template>

<script>
    import TextEditor from "../../common/TextEditor.vue";
    import ChatMixin from "../../mixins/ChatMixin.js";
    export default {
        name: "CommentReply",
        components: {TextEditor},
        mixins: [ChatMixin],
        props: {
            commentId: {
                type: String|Number,
            },
            postId: {
                type: String|Number,
            },
            name: {
                type: String,
            },
        },
        data() {
            return {
              newAnswer: [],
              isSending: true,
            }
        },
        computed: {
            userData() {
                const usrData = this.$root.$auth.user;

                if (! (!!usrData.profile)){
                    window.console.warn(usrData, 'profile is null');
                }

                return usrData;
            },
        },
        methods: {
            async onTextPost(evData){
                let msg = evData.postText.trim();

                if (msg !== '') {
                    const brExample = `<br/>`;
                    msg = msg.replace(/<p><\/p>/g, brExample);
                    msg = this.killBrTrail(msg);

                    if (msg !== '') {
                        this.getAnswerToComment(msg, evData.attachments);
                    } else if (evData.attachments.length > 0) {
                        this.getAnswerToComment('', evData.attachments);
                    }
                } else {
                    if (evData.attachments.length > 0) {
                        this.getAnswerToComment('', evData.attachments);
                    }
                }

                this.isAnswer = !this.isAnswer;
                this.isSending = false;
            },
            async getAnswerToComment(msg, attachments) {
                try {
                    let response = await this.$root.$api.$post.getAnswerToComment(msg, this.postId, attachments, this.commentId);
                    this.$emit('addComment', response.data);
                } catch (e) {
                    console.warn(e.detailMessage);
                }
            },
        }
    }
</script>
