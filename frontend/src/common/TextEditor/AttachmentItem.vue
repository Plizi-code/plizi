<template>
    <li class="media position-relative pt-1 pr-1 mr-3 mb-1">
        <SmallSpinner v-if="attach.isBlob" clazz="media__spinner" :hide-text="true"></SmallSpinner>
        <img v-if="attach.isImage" :src="imageSrc"
             v-on:load="onAttachmentLoaded"
             :alt="attach.originalName.originalName" :title="attach.originalName.originalName" />
        <template v-else-if="attach.isBlob" v-on:show="onZipDisplayed">
            <AttachmentFile :attach="attach"/>
        </template>
        <template v-else v-on:show="onZipDisplayed">
            <AttachmentFile :attach="attach.attachment"/>
        </template>

        <button type="button"
                @click.prevent="onRemoveBtnClick($event)"
                class="btn btn-close btn-link border-0 border-danger bg-danger text-white rounded-circle" aria-label="delete">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    </li>
</template>

<script>
import AttachmentFile from "../AttachmentFile.vue";
import PliziAttachmentItem from '../../classes/PliziAttachmentItem.js';
import SmallSpinner from "../SmallSpinner";

export default {
name : 'AttachmentItem',
  components: {
    SmallSpinner,
    AttachmentFile,
  },
props : {
    attach : PliziAttachmentItem
},
methods: {
    onRemoveBtnClick(ev){
        this.$emit(`RemoveAttachment`, {
            event: ev,
            attach : this.attach.attachment
        });
    },

    onAttachmentLoaded(ev) {
        this.$emit(`AttachmentLoaded`, {
            event: ev,
            attach : this.attach.attachment
        });
    },

    onZipDisplayed () {
        this.$emit(`ZipAttachmentDisplayed`, {
            event: ev,
            attach : this.attach.attachment
        });

    }
},
computed: {
    imageSrc() {
        return this.attach.isBlob ? this.attach.fileBlob : this.attach.attachment.thumb.path;
    }
}
}
</script>
