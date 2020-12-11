import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziVideoAPI extends PliziBaseAPI {
    /**
     * Получение видео текущего пользователя.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getUserVideo(limit = 20, offset = 0) {
        let response = await this.axios.get(`api/user/videos?limit=${limit}&offset=${offset}`, this.authHeaders)
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `getUserVideo` );
              throw new PliziAPIError( `getUserVideo`, error.response );
          } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Получение видео пользователя.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getUserVideoById(userId, limit = 20, offset = 0) {
        let response = await this.axios.get(`api/user/${userId}/videos?limit=${limit}&offset=${offset}`, this.authHeaders)
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `getUserVideo` );
                throw new PliziAPIError( `getUserVideo`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Сохранение ссылок на видео.
     * @public
     * @param {object} formData
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async storeVideo(formData) {
        let response = await this.axios.post('api/videos', formData, this.authHeaders)
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `storeVideo` );
              throw new PliziAPIError( `storeVideo`, error.response );
          } );

        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Удаление ссылок на видео.
     * @public
     * @param {number} id
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async deleteVideo(id) {
        let response = await this.axios.delete(`api/videos/${id}`, this.authHeaders)
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `deleteVideo` );
                throw new PliziAPIError( `deleteVideo`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }
}

export default PliziVideoAPI;
