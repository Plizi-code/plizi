<template>
    <div id="videoPageFilter"
         class="bg-white-br20 col-12 d-flex flex-wrap flex-sm-nowrap align-items-center justify-content-between mb-4">

        <nav class="videos-filter-links col-lg-7 nav pt-2 pt-sm-0" role="tablist">
            <span class="nav-link py-2 py-sm-3 py-xl-4 px-1 mr-2 mr-xl-4"
                  :class="{ 'active': wMode === 'my' }" id="tabMyVideos" role="tab"
                  @click.stop="wallPostsSelect(`my`)">Мои видео</span>

            <span class="nav-link py-2 py-sm-3 py-xl-4 px-1 mr-2 mr-xl-4 d-none"
                  :class="{ 'active': wMode === 'all' }" id="tabAllVideos" role="tab"
                  @click.stop="wallPostsSelect(`all`)">Видеокаталог</span>
        </nav>

        <div class="additionalBtns col-12 col-sm-5 d-flex justify-content-between justify-content-sm-end px-0  my-3 my-sm-0">
            <button class="btn plz-btn plz-btn-primary p-0 mr-0" @click="openAddVideoModal">
                Добавить видео
            </button>
        </div>

        <AddVideoModal v-if="addVideoModal.isVisible"
                       @onHide="hideAddVideoModal"></AddVideoModal>
    </div>
</template>

<script>
import AddVideoModal from "../../components/VideosPage/AddVideoModal.vue";

export default {
name: "VideosPageFilter",
components: {
    AddVideoModal,
},
data() {
    return {
        wMode: `my`,
        addVideoModal: {
            isVisible: false,
        },
    }
},
methods: {
    wallPostsSelect(wMode) {
        this.wMode = wMode;
        this.$emit('wallPostsSelect', {wMode: wMode});
    },

    openAddVideoModal() {
        this.addVideoModal.isVisible = true;
    },

    hideAddVideoModal(){
        this.addVideoModal.isVisible = false;
    },
},
}
</script>


