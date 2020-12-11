<template>
    <div>
        <i v-if="attach.isArchive"
           class="fas fa-file-archive fa-3x d-inline-block"
           :alt="attach.originalName"
           :title="attach.originalName"></i>
        <i v-else class="fas fa-file-alt fa-3x d-inline-block"
           alt="attach.originalName"
           :title="attach.originalName"></i>
    </div>
</template>

<script>
  import PliziAttachment from "../classes/PliziAttachment.js";
  import PliziAttachmentItem from "../classes/PliziAttachmentItem.js";

  export default {
    name: "AttachmentFile",
    props : {
      attach : PliziAttachment | PliziAttachmentItem
    },
  }
</script>

<style scoped>

</style>
