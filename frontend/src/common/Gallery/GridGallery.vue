<template>
    <div v-if="images" class="gallery grid-gallery col-12">
        <div class="row">
            <div v-for="image in images"
                 :key="image.id"
                 class="gallery-item col-12 col-sm-6 col-xl-3 my-3 d-flex align-items-stretch position-relative">
                <div v-if="image.image && image.image.original.path"
                     class="gallery-image-wrapper d-flex flex-column position-relative overflow-hidden">
                    <img :src="image.image.original.path"
                         @click="showImage(image)"
                         class="gallery-image img-fluid"
                         alt=""/>
                </div>

                <div v-else
                     class="gallery-image-wrapper d-flex flex-column position-relative overflow-hidden">
                    <img :src="image.fileBlob"
                         class="gallery-image img-fluid"
                         alt=""/>
                    <div class="spinner-wrap">
                        <SmallSpinner v-if="image.isBlob"
                                      clazz="media__spinner"
                                      :hide-text="true"/>
                    </div>
                </div>

                    <button v-if="isOwner" type="button"
                            aria-label="Удалить изображение"
                            class="delete-button"
                            @click.prevent="$emit('onDelete', image.id)">
                        <i class="fa fa-plus"
                           aria-hidden="true"></i>
                    </button>
            </div>
        </div>

        <div class="plz-gallery__show" v-if="activeImage">
            <GalleryViewer
                :images="images"
                :active-image="activeImage"
                @showImage="showImage"
                @close="closeModal">
            </GalleryViewer>
            <GalleryDescription
                :comments="comments"
                :post="{id:0}"
                :image="activeImage"
                :type="type"
                @updateComments="updateComments">
            </GalleryDescription>

        </div>
    </div>
</template>

<script>
    import SmallSpinner from "../../common/SmallSpinner.vue";

    import GalleryDescription from "./GalleryDescription.vue";
    import GalleryViewer from './GalleryViewer.vue';

    import PliziComment from "../../classes/PliziComment.js";

    export default {
        name: "GridGallery",
        components: {
            GalleryDescription,
            GalleryViewer,
            SmallSpinner,
        },
        props: {
            type: {
                type: String,
            },
            images: {
                type: Array,
                default: null,
            },
            isOwner: Boolean
        },
        data() {
            return {
                activeImage: null,
                comments: [],
            }
        },
        methods: {
            async getCommentsOfAlbum( activeImageId ) {
                try {
                    let response = await this.$root.$api.$post.getAlbumComments(activeImageId);
                    this.comments = response.data.list.map(comment => new PliziComment(comment));
                } catch (e) {
                    console.warn(e.detailMessage);
                }
            },

            updateComments({ comments, id }) {
                if (this.activeImage.id === id) {
                    this.comments = comments;
                }
            },

            showImage(image) {
                this.getCommentsOfAlbum( image.id );


                this.activeImage = this.images.find(attach => attach.id === image.id);

                const link = `${this.$router.currentRoute.path}?activeImageId=${image.id}&galleryType=${this.type}`;
                history.pushState({url: link}, '', link);
            },

            closeModal() {
                const link = this.$router.currentRoute.path;

                history.pushState({url: link}, '', link);
                this.activeImage = null;
            },
        },

        created() {
            const activeId = this.$router.history.current.query.activeImageId;
            const typeGallery = this.$router.history.current.query.galleryType;

            if (typeGallery !== this.type || !activeId) {
                return;
            }

            const foundImage = this.images.find(image => image.id.toString() === activeId);

            if (foundImage) {
                this.showImage(foundImage);
            }
        },
    }
</script>
