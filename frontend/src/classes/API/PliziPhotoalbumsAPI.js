import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziPhotoalbumsAPI extends PliziBaseAPI {
    /**
     * Получение фотоальбомов пользователя.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async list() {
        let response = await this.axios.get('api/photo-albums', this.authHeaders)
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `list` );
              throw new PliziAPIError( `list`, error.response );
          } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Cоздание нового фотоальбома.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async createPhotoalbum(formData) {
        let response = await this.axios.post('api/photo-albums', formData, this.authHeaders)
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `createPhotoalbum` );
              throw new PliziAPIError( `createPhotoalbum`, error.response );
          } );

        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Обновление данных альбома
     * @param {string} albumId
     * @param {{description: string, title: string}} formData - данные для загрузки
     * @returns {object|null} - ответ сервера
     * @throws PliziAPIError
     */
    async updatePhotoAlbum( albumId, formData ){
        let response = await this.axios.post( `api/photo-albums/${albumId}`, formData, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `updatePhotoalbum` );
                throw new PliziAPIError( `updatePhotoalbum`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }

    /**
     * Удаление фотоальбома.
     * @param albumId
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async deleteAlbum( albumId ){
        let response = await this.axios.delete( `api/photo-albums/${albumId}`, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `deleteAlbum` );
                throw new PliziAPIError( `deleteAlbum`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }

    /**
     * Получение фотоальбома
     * @param {number} id
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async getPhotoAlbum(id) {
        let response = await this.axios.get( `api/photo-albums/${id}`, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `getPhotoAlbum` );
                throw new PliziAPIError( `getPhotoAlbum`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     *
     * @param id
     * @param images
     * @return {object[]|null}
     */
    async uploadImagesInPhotoAlbum(id, images) {
        const formData = new FormData();

        for(let i = 0; i < images.length; i++){
            formData.append('files[]', images[i]);
        }

        let response = await this.axios.post(`api/photo-albums/${id}/photos`, formData, this.authFileHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$photoAlbums.uploadImagesInPhotoAlbum`);
                throw new PliziAPIError(`$photoAlbums.uploadImagesInPhotoAlbum`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }

    /**
     *
     * @param {number} imageId
     * @return {object[]|null}
     */
    async deleteImage(imageId) {
        let response = await this.axios.delete(`api/photos/${imageId}`, this.authFileHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$photoAlbums.deleteImage`);
                throw new PliziAPIError(`$photoAlbums.deleteImage`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }
}

export default PliziPhotoalbumsAPI;
