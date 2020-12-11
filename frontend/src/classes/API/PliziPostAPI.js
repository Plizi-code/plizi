import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziPostAPI extends PliziBaseAPI {
    /**
     * Получение пользовательских постов.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getPosts(limit, offset){
        let path = 'api/user/posts/';
        let qParams = '';

        if (limit && offset) {
            qParams = `?limit=${limit}&offset=${offset}`;
        }

        let response = await this.axios.get( path + qParams, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `getPosts` );
                throw new PliziAPIError( `getPosts`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Получение новостей.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getNews(limit, offset, search = '', parts = []){
        let path = 'api/user/news';

        const params = new URLSearchParams({
            limit: limit || 50,
            offset: offset || 0,
            search: search || '',
        });
        parts.map((part) => {
            params.append('parts[]', part);
        })
        // params.append('parts[]', 'own');
        // params.append('parts[]', 'friends');
        // params.append('parts[]', 'communities');

        let response = await this.axios.get( path + '?' + params.toString(), this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `getNews` );
                throw new PliziAPIError( `getNews`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Получение постов на страницах других пользователей.
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getPostsByUserId( id, limit, offset ){
        let path = `api/user/${id}/posts/`;
        let qParams = '';

        if (limit && offset) {
            qParams = `?limit=${limit}&offset=${offset}`;
        }

        let response = await this.axios.get( path + qParams, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `getPostsByUserId` );
                throw new PliziAPIError( `getPostsByUserId`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Создание постов.
     * @param formData
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async storePost( formData ){
        let response = await this.axios.post( 'api/posts', formData, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `storePost` );
                throw new PliziAPIError( `storePost`, error.response );
            } );

        if ( response.status === 201 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Создание постов в сообществе.
     * @param communityId
     * @param formData
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async storePostByCommunity(communityId, formData) {
        let response = await this.axios.post( `api/communities/${communityId}/posts`, formData, this.authHeaders )
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `storePostByCommunity` );
              throw new PliziAPIError( `storePostByCommunity`, error.response );
          } );

        if ( response.status === 201 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Загружка файлов для постов.
     * @param picsArr
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async storePostAttachments( picsArr ){
        const formData = new FormData();

        for ( let i = 0; i < picsArr.length; i++ ){
            formData.append( 'files[]', picsArr[i] );
        }

        let response = await this.axios.post( 'api/posts/attachments', formData, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `storePostAttachments` );
                throw new PliziAPIError( `storePostAttachments`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Удаление постов со стены аутентифицированого пользователя.
     * @param id
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async deletePost( id ){
        let response = await this.axios.delete( `api/posts/${id}`, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `deletePost` );
                throw new PliziAPIError( `deletePost`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }

    /**
     * Восстановление поста.
     *
     * @param id
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async restorePost( id ){
        let response = await this.axios.get( `api/posts/${id}/restore`, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `restorePost` );
                throw new PliziAPIError( `restorePost`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }

    /**
     * Редактирование поста.
     *
     * @param id
     * @param formData
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async updatePost( id, formData ){
        let response = await this.axios.post( `api/posts/${id}/update`, formData, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `editPost` );
                throw new PliziAPIError( `editPost`, error.response );
            } );


        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Репост чужой записи на свойю стену.
     *
     * @param id
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async sharePostToWall( id ){
        let response = await this.axios.post( `api/posts/share/wall`, { id }, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `sharePostToWall` );
                throw new PliziAPIError( `sharePostToWall`, error.response );
            } );


        if ( response.status === 201 ){
            return response.data;
        }

        return null;
    }

    /**
     * Удаление атачментов из поста.
     *
     * @param id
     * @returns {null}
     * @throws PliziAPIError
     */
    async deletePostImage(postId, attachmentId) {
        let response = await this.axios.delete( `api/posts/${postId}/attachment/${attachmentId}`, this.authHeaders )
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `deletePostImage` );
              throw new PliziAPIError( `deletePostImage`, error.response );
          } );


        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Лайк постов.
     *
     * @param postId
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async likePost(postId) {
        let response = await this.axios.post( `api/posts/rate`, { postId },this.authHeaders )
          .catch( ( error ) => {
              this.checkIsTokenExpires( error, `likePost` );
              throw new PliziAPIError( `likePost`, error.response );
          } );


        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Получение пользователей которые лайкнули пост.
     *
     * @param {number} postId
     * @param {number} limit
     * @param {number} offset
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async getUsersLikes(postId, limit, offset) {
        let path = `api/posts/${postId}/likes/users`;
        let qParams = '';

        if (limit && offset) {
            qParams = `?limit=${limit}&offset=${offset}`;
        }

        let response = await this.axios.get(path + qParams, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `getUsersLikes`);
                throw new PliziAPIError(`getUsersLikes`, error.response);
            });


        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Оставить комментарий
     *
     * @param {string} body
     * @param {number} postId
     * @param {number[]} attachmentIds
     * @return {object[]|null}
     * @throws PliziAPIError
     */
     async setPostComments(body, postId, attachmentIds = []) {
         const response = await this.axios.post(`api/comment/post`, {
             body,
             postId,
             attachmentIds,
        }, this.authHeaders
     ).catch((error) => {
             this.checkIsTokenExpires(error, `$comment.sendComment`);
             throw new PliziAPIError(`$comment.sendComment`, error.response);
         });

      if (response.status === 200) {
          return response.data
      }

      return null;
    }

    /**
     * получить ответы к комментарию
     *
     * @param {string} body
     * @param {number} postId
     * @param {number[]} attachmentIds
     * @param {number} replyOn
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async getAnswerToComment(body, postId, attachmentIds = [], replyOn) {
        const response = await this.axios.post(`api/comment/post`, {
                body,
                postId,
                attachmentIds,
                replyOn
            }, this.authHeaders
        ).catch((error) => {
            this.checkIsTokenExpires(error, `$comment.getAnswer`);
            throw new PliziAPIError(`$comment.getAnswer`, error.response);
        });

        if (response.status === 200) {
            return response.data
        }

        return null;
    }

    /**
     * Получить коментарии к посту
     *
     * @param {number} postId
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async getCommentsById(postId) {
        const response = await this.axios.get(`api/comment/post/${postId}`, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$comment.getComment`);
                throw new PliziAPIError(`$comment.getComment`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    };

    /**
     * Удалить комментарий
     *
     * @param {number} commentId
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async deleteCommentById(commentId) {
        const response = await this.axios.delete(`api/comment/${commentId}`, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$comment.deleteComment`);
                throw new PliziAPIError(`$comment.deleteComment`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    };

    /**
     * Изменить комментарий
     * @param {string} body
     * @param {number} commentId
     * @param {number[]} attachmentIds
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async editCommentById(commentId, body, attachmentIds = []) {
        const response = await this.axios.patch(`api/comment/${commentId}`, {
            commentId,
            body,
            attachmentIds,
        }, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$comment.editComment`);
                throw new PliziAPIError(`$comment.editComment`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null
    }

    /**
     * Загрузка файлов для комментариев.
     * @param picsArr
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async addAttachmentsToComment( picsArr ){
        const formData = new FormData();

        for ( let i = 0; i < picsArr.length; i++ ){
            formData.append( 'files[]', picsArr[i] );
        }

        let response = await this.axios.post( 'api/comment/attachments', formData, this.authHeaders )
            .catch((error) => {
                this.checkIsTokenExpires(error, `$comment.uploadAttachments`);
                throw new PliziAPIError(`$comment.uploadAttachments`, error.response);
            });

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Лайк комментариев.
     *
     * @param commentId
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async likeComment(commentId) {
        let response = await this.axios.post( `api/comment/${commentId}/like`, {}, this.authHeaders )
            .catch((error) => {
                this.checkIsTokenExpires(error, `$comment.likeComment`);
                throw new PliziAPIError(`$comment.likeComment`, error.response);
            });

        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }

    /**
     * Оставить комментарий к галереи
     *
     * @param {string} body
     * @param {number} postId
     * @param {number[]} attachmentId
     * @param {number[]} attachmentIds
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async setGalleryComments(body, postId, attachmentId, attachmentIds = []) {
        const response = await this.axios.post(`api/posts/attachments/${attachmentId}/comment`, {
                body,
                postId,
                attachmentId,
                attachmentIds,
            }, this.authHeaders
        ).catch((error) => {
            this.checkIsTokenExpires(error, `$comment.sendCommentToGallery`);
            throw new PliziAPIError(`$comment.sendCommentToGallery`, error.response);
        });

        if (response.status === 200) {
            return response.data
        }

        return null;
    }


    /**
     * Получить коментарии к галереии
     *
     * @param {number} attachmentId
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async getCommentsByIdOnGallery(attachmentId) {
        const response = await this.axios.get(`api/posts/attachments/${attachmentId}/comment`, this.authHeaders)
        .catch((error) => {
            this.checkIsTokenExpires(error, `$comment.getCommentToGallery`);
            throw new PliziAPIError(`$comment.getCommentToGallery`, error.response);
        });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    };

    /**
     * Получить коментарии к посту
     *
     * @param {number} imageId
     * @return {object[]|null}
     * @throws PliziAPIError
     */

    async getAlbumComments(imageId) {
        const response = await this.axios.get(`api/user/images/${imageId}/comment`, this.authHeaders);

        if (response.status === 200) {
            return response.data;
        }

        return null;
    };

    /**
     * Оставить комментарий
     *
     * @param {string} body
     * @param {number} imageId
     * @param {number[]} attachmentIds
     * @return {object[]|null}
     * @throws PliziAPIError
     */
    async sendCommentToAlbum(body, imageId, attachmentIds = []) {
        const response = await this.axios.post(`api/user/images/${imageId}/comment`,  {
                body,
                imageId,
                attachmentIds,
            }, this.authHeaders
        );

        if (response.status === 200) {
            return response.data
        }

        return null;
    }


    async addView(postId, userId) {
        const sendData = {
            postId : postId
        };
        let response = await this.axios.post( `api/posts/view`, sendData, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$post.addView` );
                throw new PliziAPIError( `$post.addView`, error.response );
            });

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }

}

export default PliziPostAPI;
