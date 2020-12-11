<template>
    <div v-if="message.attachments.length"
         class="message-attachments d-flex flex-wrap"
         :class="{'message-attachments-z': attachItem}">
        <div class="message-attachment-item mb-2 message-gallery --flex-grow-1" >
            <Gallery type="chat" v-if="attachItem"
                     v-bind:images="imageList" class="message-sended-image">
            </Gallery>
            <span v-else class="message-sended-attach d-flex align-items-center mb-2">
                <IconZip/>
                <span class="message-sended-attach-info d-flex flex-column mx-2">
                    <span class="message-sended-name m-0">{{attach.originalName}}</span>
                    <span class="message-sended-size m-0">{{attach.size}}</span>
                </span>
            </span>

        </div>
    </div>
</template>

<script>
import IconZip from '../../icons/IconZip.vue';
import Gallery from '../Gallery/Gallery.vue';

import PliziMessage from '../../classes/PliziMessage.js';

export default {
name : 'ChatMessageItemAttachments',
components : { Gallery, IconZip },

props : {
    message : PliziMessage
},

computed:{
    isArchive(){
        const ext = this.attach.originalName.split('.').pop().toLowerCase();
        return (`zip`===ext  || `rar`===ext);
    },

    imageList() {
        return this.message.attachments;
    },

    attachItem() {
        return this.imageList.map(attach => attach.isImage);
    },
}

}
</script>
