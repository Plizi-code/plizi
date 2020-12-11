<template>
    <TextEditor id="communityEditor"
                :clazz="`row plz-text-editor mb-4 p-4 h-auto  align-items-start bg-white-br20`"
                :editorPlaceholder="'Введите текст ...'"
                :dropToDown="true"
                @editorPost="onTextPost"
                workMode="post">
    </TextEditor>
</template>

<script>
import TextEditor from '../TextEditor.vue';
import ChatMixin from '../../mixins/ChatMixin.js';

export default {
name: 'CommunityEditor',
components: {
    TextEditor
},
mixins: [ChatMixin],
props: {
    communityId: {
        type: Number,
        default: null,
    },
},

methods: {
    async onTextPost(evData){
        let msg = evData.postText.trim();

        if (msg !== '' || evData.videoLink) {
            const brExample = `<br/>`;
            msg = msg.replace(/<p><\/p>/g, brExample);
            msg = this.killBrTrail(msg);

            if (msg !== '' || evData.videoLink) {
                this.savePost( msg, evData.attachments, evData.videoLink, evData.workMode );
            } else if (evData.attachments.length > 0) {
                this.savePost( '<p></p>', evData.attachments, evData.videoLink, evData.workMode );
            }
        } else {
            if (evData.attachments.length > 0) {
                this.savePost( '', evData.attachments );
            }
        }
    },

    async savePost(text, attachments, videoLink = null, workMode = null) {
        let response;
        let formData = {};

        if (videoLink) {
            formData.body = videoLink;
        } else {
            formData.body = text.trim();
        }

        if (attachments && attachments.length) {
            formData.attachmentIds = attachments;
        }

        try {
            response = await this.$root.$api.$post.storePostByCommunity(this.communityId, formData);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            this.$emit('addNewPost', response);
            this.storeVideo(videoLink, workMode, response.id);
        }
    },
    async storeVideo(youtubeLink, workMode, id) {
        if (!(youtubeLink && workMode && id)) return;

        let response;
        let formData = {
            link: youtubeLink,
            workMode: workMode,
            id: id,
        };

        try {
            response = await this.$root.$api.$video.storeVideo(formData);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (response) {
            console.log(response);
        }
    },
},
}
</script>
