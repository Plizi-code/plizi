import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziCommunitiesAPI extends PliziBaseAPI {

    /**
     * Получение списка сообществ
     * @param {string} searchText
     * @param {number} limit
     * @param {number} offset
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async loadCommunities(searchText = '', limit = 20, offset = 0){
        const search = searchText
            ? `?search=${searchText}&limit=${limit}&offset=${offset}`
            : `?limit=${limit}&offset=${offset}`;
        const url = 'api/communities' + search;

        let response = await this.axios.get( url, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$communities.loadCommunities` );
                throw new PliziAPIError( `$communities.loadCommunities`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Получение списка сообществ для гостя
     * @param {string} sText
     // * @param {number} limit
     // * @param {number} offset
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async guestLoadCommunities(sText){
        const sData = (sText + '').trim();

        let response = await this.axios.get('api/communities/search/' + sData)
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$communities.guestLoadCommunities` );
                throw new PliziAPIError( `$communities.guestLoadCommunities`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Получение списка сообществ для управления
     * @param {string} searchText
     * @param {number} limit
     * @param {number} offset
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async loadManagedCommunities(searchText = '', limit = 20, offset = 0) {
        const search = searchText
            ? `&search=${searchText}&limit=${limit}&offset=${offset}`
            : `&limit=${limit}&offset=${offset}`;
        const url = 'api/communities?list=owner' + search;
        let response = await this.axios.get(url, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.loadManagedCommunities`);
                throw new PliziAPIError(`$communities.loadManagedCommunities`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


    /**
     * Получение списка сообществ пользователя.
     * @param {string} searchText
     * @param {number} limit
     * @param {number} offset
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async userCommunities(searchText = '', limit = 20, offset = 0) {
        const search = searchText
            ? `&search=${searchText}&limit=${limit}&offset=${offset}`
            : `&limit=${limit}&offset=${offset}`;
        const url = 'api/communities?list=my' + search;

        let response = await this.axios.get(url, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$communities.userCommunities` );
                throw new PliziAPIError( `$communities.userCommunities`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }


    async getCommunity(communityID){
        let response = await this.axios.get( 'api/communities/'+communityID, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$communities.getCommunity` );
                throw new PliziAPIError( `$communities.getCommunity`, error.response );
            });

        if ( response.status === 200 ){
            return response.data.data;
        }

        return null;
    }


    /**
     * создаёт новое сообщество
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/createCommunity
     * @param {object} formData - данные для создания
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async communityCreate( formData ){
        let response = await this.axios.post( 'api/communities', formData, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, '$communities.communityCreate' );
                throw new PliziAPIError( '$communities.communityCreate', error.response );
            } );

        if ( response.status === 201 ){
            return response.data.data;
        }

        return null;
    }


    /**
     * подписываемся на сообщество
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/subscribeOnCommunity
     * @param {number} communityID - ID сообщества, на которое собираемся подписаться
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async subscribe(communityID){
        let response = await this.axios.get( `api/communities/${communityID}/subscribe`, this.authHeaders )
            .catch( ( error ) => {
                /** @TGA так сервер отвечает, что юзер уже в этом сообществе **/
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        message: error.response.data.message
                    }
                }
                else {
                    this.checkIsTokenExpires( error, `$communities.subscribe` );
                    throw new PliziAPIError( `$communities.subscribe`, error.response );
                }
            } );

        if ( response.status === 200 ){
            return response.data; // TODO: @TGA потом опять сделать data.data
        }

        return null;
    }


    /**
     * отписываемся от сообщества
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/unsubscribeFromCommunity
     * @param {number} communityID - ID сообщества, на которое собираемся подписаться
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async unsubscribe(communityID){
        let response = await this.axios.get( `api/communities/${communityID}/unsubscribe`, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$communities.unsubscribe` );
                throw new PliziAPIError( `$communities.unsubscribe`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data; // TODO: @TGA потом опять сделать data.data
        }

        return null;
    }


    /**
     * Получение постов сообщества
     * @param {number} communityID - ID сообщества, посты которого пытаемся получить
     * @param {number} limit
     * @param {number} offset
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async posts(communityID, limit, offset){
        let path = `api/communities/${communityID}/posts`;
        let qParams = '';

        if (limit && offset) {
            qParams = `?limit=${limit}&offset=${offset}`;
        }

        let response = await this.axios.get( path + qParams, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `$communities.posts` );
                throw new PliziAPIError( `$communities.posts`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }


    /**
     * Обновление данных сообщества
     * @param {string} id
     * @param {formData} formData - данные для загрузки
     * @returns {object|null} - ответ сервера
     * @throws PliziAPIError
     */
    async update(id, formData) {
        let response = await this.__axios.patch('/api/communities/' + id, formData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.update`);
                throw new PliziAPIError(`$communities.update`, error.response);
            });

        if (response.status === 200 || response.status === 201) {
            return response.data;
        }

        return null;
    }


    /**
     * загружает аватарку сообщества
     * @param {formData} formData - данные для загрузки
     * @returns {object|null} - ответ сервера
     * @throws PliziAPIError
     */
    async updatePrimaryImage(formData) {
        let response = await this.__axios.post('/api/communities/avatar', formData, this.authFileHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.updatePrimaryImage`);
                throw new PliziAPIError(`$communities.updatePrimaryImage`, error.response);
            });

        if (response.status === 200 || response.status === 201) {
            return response.data;
        }

        return null;
    }


    /**
     * загружает аватарку сообщества
     * @param {formData} formData - данные для загрузки
     * @returns {object|null} - ответ сервера
     * @throws PliziAPIError
     */
    async updateHeaderImage(formData) {
        let response = await this.__axios.post('/api/communities/header-image', formData, this.authFileHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.updateHeaderImage`);
                throw new PliziAPIError(`$communities.updateHeaderImage`, error.response);
            });

        if (response.status === 200 || response.status === 201) {
            return response.data;
        }

        return null;
    }


    /**
     * загружает список тем
     * @returns {object[]}
     */
    async getThemes() {
        let response = await this.axios.get('/api/communities/themes/list', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.getThemes`);
                throw new PliziAPIError(`$communities.getThemes`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * Список запросов на вступление в сообщество
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/getCommunityRequestList
     * @param {number} communityID - ID сообщества, на которое собираемся подписаться
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async requestList(communityID) {
        let response = await this.axios.get(`api/communities/requests/list/${communityID}`, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        message: error.response.data.message
                    }
                }

                this.checkIsTokenExpires(error, `$communities.requestList`);
                throw new PliziAPIError('$communities.requestList', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * Отправить запрос на вступление в сообщество
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/createCommunityRequest
     * @param {number} communityID - ID сообщества, на которое собираемся подписаться
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async requestCreate(communityID) {
        let response = await this.axios.post(`api/communities/requests/create/${communityID}`, {}, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        data: {
                            message: error.response.data.message
                        },
                    }
                }
                this.checkIsTokenExpires(error, `$communities.requestCreate`);
                throw new PliziAPIError('$communities.requestCreate', error.response);
            });

        if ([200, 422].includes(response.status)) {
            return response.data;
        }

        return null;
    }


    /**
     * Принятие запроса на вступление в сообщество
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/acceptCommunityRequest
     * @param {number} communityID - ID сообщества, на которое собираемся подписаться
     * @param {number} id - ID заявки
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async requestAccept(communityID, id) {
        let response = await this.axios.patch(`api/communities/requests/accept/${communityID}/${id}`, {}, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        message: error.response.data.message
                    }
                }
                this.checkIsTokenExpires(error, `$communities.requestAccept`);
                throw new PliziAPIError('$communities.requestAccept', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * Отклонение запроса на вступление в сообщество
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Communities/rejectCommunityRequest
     * @param {number} communityID - ID сообщества, на которое собираемся подписаться
     * @param {number} id - ID заявки
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async requestReject(communityID, id) {
        let response = await this.axios.patch(`api/communities/requests/reject/${communityID}/${id}`, {}, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        message: error.response.data.message
                    }
                }
                this.checkIsTokenExpires(error, `$communities.requestReject`);
                throw new PliziAPIError('$communities.requestReject', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    async recommended() {
        let response = await this.axios.get(`api/communities/recommended/list`, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        message: error.response.data.message
                    }
                }
                this.checkIsTokenExpires(error, '$communities.recommended');
                throw new PliziAPIError('$communities.recommended', error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }


    /**
     * Получение списка участников сообщества
     * @param {number} communityId
     * @param {number} limit
     * @param {number} offset
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async members(communityId, limit = 20, offset = 0) {
        const url = `api/communities/${communityId}/members?limit=${limit}&offset=${offset}`;
        let response = await this.axios.get(url, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.members`);
                throw new PliziAPIError(`$communities.members`, error.response);
            });

        if (response.status === 200) {
            /**
             * FIXME: @TGA тут должен быть возврат response.data.data.list
             **/
            return response.data.data;
        }

        return null;
    }

    /**
     *
     * @param {number} communityId
     * @param {number} limit
     * @param {number} offset
     * @returns {Promise<null|*>}
     */
    async videos(communityId, limit = 5, offset = 0) {
        const url = `api/communities/${communityId}/videos?limit=${limit}&offset=${offset}`;

        let response = await this.axios.get(url, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.videos`);
                throw new PliziAPIError(`$communities.videos`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }

    async favorites() {
        let response = await this.axios.get('api/communities/favorite/list', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.favorites`);
                throw new PliziAPIError(`$communities.favorites`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }
        return null;
    }

    /**
     * @param {number} communityId
     * @param {string} userId
     * @returns {Promise<null|any>}
     */
    async becomeAdmin(communityId, userId) {
        const url = `api/communities/admin/${communityId}/${userId}`;
        let response = await this.axios.post(url, {}, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.members`);
                throw new PliziAPIError(`$communities.members`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    async addToFavorites(communityID) {
        const sendData = {
            id: communityID,
        };

        let response = await this.axios.post('api/communities/favorite/subscribe', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$communities.addToFavorites');
                throw new PliziAPIError('$communities.addToFavorites', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * @param {number} communityId
     * @param {string} userId
     * @returns {Promise<null|any>}
     */
    async stopBeAdmin(communityId, userId) {
        const url = `api/communities/admin/${communityId}/${userId}`;
        let response = await this.axios.delete(url, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.members`);
                throw new PliziAPIError(`$communities.members`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    async removeToFavorites(communityID) {
        let response = await this.axios.delete('api/communities/favorite/unsubscribe/'+communityID, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$communities.removeToFavorites');
                throw new PliziAPIError('$communities.removeToFavorites', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    /**
     * @param {number} communityId
     * @returns {Promise<null|any>}
     */
    async subscribeNotify(communityId) {
        const url = `api/communities/${communityId}/notify`;
        let response = await this.axios.post(url, {}, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.subscribeNotify`);
                throw new PliziAPIError(`$communities.subscribeNotify`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    /**
     * @param {number} communityId
     * @returns {Promise<null|any>}
     */
    async unsubscribeNotify(communityId) {
        const url = `api/communities/${communityId}/notify`;
        let response = await this.axios.delete(url, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$communities.unsubscribeNotify`);
                throw new PliziAPIError(`$communities.unsubscribeNotify`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }
}

export default PliziCommunitiesAPI;
