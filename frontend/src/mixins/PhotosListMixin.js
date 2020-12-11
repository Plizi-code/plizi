import PliziAttachment from "../classes/PliziAttachment";
import PliziPhotoAlbum from "../classes/PliziPhotoAlbum";

const PhotosListMixin = {
data(){
    return {
        userPhotos: [],
        isPhotosDataReady: false,
        photoAlbums: null,
        isDataReady: false,
        isAlbumDataReady: false,
        photoAlbum: null
    }
},
methods: {
    async getUserPhotos(userId) {
        this.userPhotos = [];
        let apiResponse = null;
        try {
            apiResponse = await this.$root.$api.$users.lastPhotos(userId);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }
        if (apiResponse) {
            apiResponse.forEach((image) => {
                this.userPhotos.push(new PliziAttachment(image));
            });
            this.isPhotosDataReady = true;
        }
    },

    async getPhotoAlbums(userId) {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$users.getUserPhotoalbums(userId);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.photoAlbums = apiResponse.map((photoAlbum) => {
                return new PliziPhotoAlbum(photoAlbum);
            });
            this.isDataReady = true;
        }
    },

    async getPhotoAlbum(photoAlbumId) {
        let apiResponse = null;

        try {
            apiResponse = await this.$root.$api.$photoalbums.getPhotoAlbum(photoAlbumId);
        } catch (e) {
            console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.photoAlbum = new PliziPhotoAlbum(apiResponse);
            this.isAlbumDataReady = true;
        }
    }
}

};

export {PhotosListMixin as default}
