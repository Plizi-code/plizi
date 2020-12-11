<template>
    <div class="plz-gallery-viewer">
        <button class="plz-gallery-viewer-close" @click="close"></button>
        <div class="plz-gallery-viewer-overflow"
             :style="{'background-image':
             `linear-gradient(to right, rgba(0, 0, 0, .85) 0%, rgba(0, 0, 0, .85) 100%),
             url('${activeImage.image.normal.path}'),
             linear-gradient(to right, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 1) 100%)`}"
        >
        </div>
        <div class="plz-gallery-viewer-nav">
            <div class="plz-gallery-viewer-nav-desktop" @click="prevImage">
                <div v-if="images.length > 1" class="plz-gallery-viewer-nav-btn plz-gallery-viewer-nav-btn-prev">
                    <IconArrowLeft></IconArrowLeft>
                </div>
            </div>
            <div class="plz-gallery-viewer-nav-desktop" @click="nextImage">
                <div v-if="images.length > 1" class="plz-gallery-viewer-nav-btn plz-gallery-viewer-nav-btn-next">
                    <IconArrowRight></IconArrowRight>
                </div>
             </div>
        </div>
        <div class="plz-gallery-viewer-current">
            <img :src="activeImage.image.original.path" :alt="activeImage.originalName">
        </div>
    </div>
</template>

<script>
import IconArrowRight from "../../icons/IconArrowRight.vue";
import IconArrowLeft from "../../icons/IconArrowLeft.vue";

export default {
    name: 'GalleryViewer',
    components: {IconArrowLeft, IconArrowRight},
    props: {
        images: {
            type: Array,
            required: true,
        },
        activeImage: null,
    },
    created() {
        if (!this.activeImage && this.images.length > 0) {
            this.goToImage(this.images.slice(0, 1).pop());
        }

        this.addBodyViewerOpen();
    },
    destroyed() {
       this.removeBodyViewerOpen();
    },
    computed: {
        currentImageIndex() {
            return this.images.findIndex(image => image.id === this.activeImage.id);
        },
    },
    methods: {
        prevImage() {
            const prevImageIndex = this.currentImageIndex - 1;

            if (prevImageIndex >= 0 && this.images[prevImageIndex]) {
                this.goToImage(this.images[prevImageIndex]);
            } else {
                this.goToImage([...this.images].pop());
            }
        },
        nextImage() {
            const nextImageIndex = this.currentImageIndex + 1;

            if (nextImageIndex >= 0 && this.images[nextImageIndex]) {
                this.goToImage(this.images[nextImageIndex]);
            } else {
                this.goToImage([...this.images].shift());
            }
        },
        close() {
            this.$emit('close');
        },
        goToImage(image) {
            this.$emit('showImage', image);
        },
       addBodyViewerOpen() {
            document.querySelector('body').classList.add('plz-gallery--open');
       },
       removeBodyViewerOpen() {
            document.querySelector('body').classList.remove('plz-gallery--open');
       }
    }
}
</script>
