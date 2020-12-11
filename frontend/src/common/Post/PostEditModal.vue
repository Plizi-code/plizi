<template>
    <div class="modal" id="postEditModal" tabindex="-1" role="dialog" aria-labelledby="postEditModal"
         aria-hidden="true" style="display: block; background-color: rgba(0, 0, 0, .7);"
         @click.stop="hidePostEditModal">

        <div class="modal-dialog modal-xl modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">
                <div id="resendMessageModalBody" class="modal-body p-4">
                    <TextEditor work-mode="post"
                                class="border-0"
                                :showAvatar="textEditor.showAvatar"
                                :inputEditorText="post.body"
                                :inputEditorAttachment="post.attachments"
                                @editorPost="onTextPost"
                                @onRemoveAttachment="onRemoveAttachment"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextEditor from '../TextEditor.vue';

import ChatMixin from '../../mixins/ChatMixin.js';
import PliziPost from '../../classes/PliziPost.js';

export default {
name: 'PostEditModal',
components: {
    TextEditor,
},
props: {
    post: PliziPost,
},
mixins: [ChatMixin],
data() {
    return {
        textEditor: {
            showAvatar: false,
        },
    }
},
methods: {
    hidePostEditModal() {
        this.$emit('hidePostEditModal');
    },

    async onTextPost(evData) {
        let msg = evData.postText.trim();

        if (msg !== '' || evData.videoLink) {
            const brExample = `<br/>`;
            msg = msg.replace(/<p><\/p>/g, brExample);
            msg = this.killBrTrail(msg);

            if (msg !== '' || evData.videoLink) {
                this.updatePost(msg, evData.attachments, evData.videoLink, evData.workMode);
            } else if (evData.attachments.length > 0) {
                this.updatePost('<p></p>', evData.attachments, evData.videoLink, evData.workMode);
            }
        } else {
            if (evData.attachments.length > 0) {
                this.updatePost('', evData.attachments);
            }
        }
    },
    async updatePost(text, attachments, videoLink = null, workMode = null) {
        let response;
        let formData = {};

        if (videoLink) {
            formData.body = videoLink;

            if (text) {
                formData.body += ` ${text}`;
            }
        } else {
            formData.body = text.trim();
        }

        if (attachments && attachments.length) {
            formData.attachmentIds = attachments;
        }

        try {
            response = await this.$root.$api.$post.updatePost(this.post.id, formData);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            this.post.update(response);
            this.hidePostEditModal();
        }
    },
    async onRemoveAttachment(attachmentId) {
        let response;

        try {
            response = await this.$root.$api.$post.deletePostImage(this.post.id, attachmentId);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            this.post.update(response);
        }
    },
},
}
</script>
