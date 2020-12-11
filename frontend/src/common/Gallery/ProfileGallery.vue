<template>
    <div class="profile-photos">
        <div v-if="profilePhotos" class="w-100  d-flex flex-row plz-profile-photos-list">

            <div class="profile-photos-item" v-for="(photo) in images" v-bind:key="photo.id">
                <div class="plz-profile-photo-item my-0 ml-0 mr-3"
                     :class="{'plz-gallery-image-mores': photo.isMore}"
                     @click="showImage(photo)">
                    <img @click="showImage(photo)"
                         :src="photo.medium.path"
                         :alt="photo.name"/>
                </div>
                <button v-if="isOwner" class="btn-close"  aria-label="delete" @click="onDeleteImage(photo.id)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>

            </div>

        </div>
        <div class="plz-gallery__show" v-if="activeImage">
            <GalleryViewer
                :images="images"
                :active-image="activeImage"
                @showImage="showImage"
                @close="closeAlbumModal">
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
    import GalleryViewer from './GalleryViewer.vue';
    import GalleryDescription from "./GalleryDescription.vue";
    import PliziComment from "../../classes/PliziComment";

    export default {
        name: 'ProfileGallery',
        components: {GalleryDescription, GalleryViewer},
        props: {
            profilePhotos: Boolean,
            images: {
                type: Array,
                required: true,
            },
            type: {
                type: String,
            },
            isOwner: Boolean
        },

        data() {
            return {
                activeImage: null,
                comments: [],
            };
        },

        computed: {
            viewImages() {
                return this.images.slice(0, this.moreCount);
            },

            firstImageView() {
                return this.images.slice(0, 1).pop();
            },

            lastImageView() {
                return this.viewImages.slice(-1).pop();
            },
        },

        methods: {
            async onDeleteImage(id) {
                let apiResponse = null;

                try {
                    apiResponse = await this.$root.$api.$photoalbums.deleteImage(id);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

                if (apiResponse) {
                    let deletedImageIndex = this.images.findIndex(image => image.id === id);
                    this.images.splice(deletedImageIndex, 1);

                    // TODO: сделать по нормальному после MVP
                    let lsUser = JSON.parse(localStorage.getItem('pliziUser'));

                    if (!lsUser.data.stats.imageCount) {
                        lsUser.data.stats.imageCount = 0;
                    } else {
                        lsUser.data.stats.imageCount--;
                    }

                    localStorage.setItem('pliziUser', JSON.stringify(lsUser));
                }
            },

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

            closeAlbumModal() {
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
