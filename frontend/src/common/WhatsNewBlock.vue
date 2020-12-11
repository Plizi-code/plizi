<template>
    <TextEditor :id="`profileWhatsNew`"
                :clazz="`row plz-text-editor mb-4 p-4 h-auto  align-items-start bg-white-br20`"
                :editorPlaceholder="'Что у Вас нового?'"
                :dropToDown="true"
                :maximumCharacterLimit="10000"
                :errors="errors"
                @editorPost="onTextPost"
                @onUpdateEditor="onUpdateEditor"
                workMode="post">
    </TextEditor>
</template>

<script>
import TextEditor from './TextEditor.vue';

import ChatMixin from '../mixins/ChatMixin.js';

export default {
name: 'WhatsNewBlock',
components: {
    TextEditor
},
    mixins: [ChatMixin],
data() {
    return {
        errors: null,
    }
},
computed: {
    userData() {
        return this.$root.$auth.user;
    },
},
methods: {
    onUpdateEditor() {
        this.errors = null;
    },

  async onTextPost(evData) {
      let msg = evData.postText.trim();

    if (msg !== '' || evData.videoLink) {
      const brExample = `<br/>`;
      msg = msg.replace(/<p><\/p>/g, brExample);
      msg = this.killBrTrail(msg);

        if (msg !== '' || evData.videoLink) {
        this.savePost( msg, evData.attachments, evData.videoLink, evData.workMode );
      } else if (evData.attachments.length > 0 || evData.videoLink) {
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
          response = await this.$root.$api.$post.storePost(formData);
      } catch (e) {
          this.errors = e.data.errors;
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

