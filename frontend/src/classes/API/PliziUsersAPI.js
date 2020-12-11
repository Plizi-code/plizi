import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziUsersAPI extends PliziBaseAPI{

    /**
     * получение детальной информации о юзере
     * @param {number|null} userId - если ID НЕ указан, то о текущем юзере
     * @returns {Object|null} - объект с данными юзера
     */
    async getUser(userId) {
        const path = (userId) ? `api/user/${userId}` : 'api/user';

        let response = await this.axios.get(path, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$users.getUser');
                throw new PliziAPIError('$users.getUser', error.response);
            });

        if (200 === response.status) {
            return response.data;
        }

        return null;
    }


    /**
     * @deprecated
     * получение детальной информации о юзере
     * @param {number} id - числовой ID юзера
     * @returns {Object|null} - объект с данными юзера
     */
    async infoUser(id) {
        window.console.warn(`$users.infoUser - DEPRECATED`);
        let response = await this.__axios.get('' + id, this.__getAuthHeaders())
            .catch((error) => {
                this.__checkIsTokenExperis(error, `infoUser`);
                throw new PliziAPIError(`infoUser`, error.response);
            });

        if (200 === response.status) {
            return response.data.data;
        }

        return null;
    }

    /**
     * получение черного списка юзера
     * @returns {Object|null} - черный списка юзера
     */
    async blacklist() {
        let response = await this.axios.get('api/user/blacklist', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$users.blacklistGet');
                throw new PliziAPIError('$users.blacklistGet', error.response);
            });

        if (200 === response.status) {
            return response.data.data.list;
        }
        return null;
    }

    /**
     * добавление пользователя в черный список юзера
     * @param {object} userId
     * @returns {Object|null} - черный списка юзера
     */
    async blacklistAdd(userId) {
        const postParam = {userId: userId};
        let response = await this.axios.post('api/user/blacklist', postParam, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$users.blacklistAdd');
                throw new PliziAPIError('$users.blacklistAdd', error.response);
            });

        if (200 === response.status) {
            return response.data.list;
        }
        return null;
    }

    /**
     * Удаление пользователя из черного списка.
     * @param {string} userId
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async blacklistDelete(userId){
        let response = await this.axios.delete( 'api/user/blacklist?userId=' + userId, this.authHeaders)
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, '$users.deleteFromBlacklist' );
                throw new PliziAPIError( '$users.deleteFromBlacklist', error.response );
            } );

        if ( response.status === 200 ){
            return response.data;
        }

        return null;
    }

    /**
     * поиск по юзерам
     * @param {string} sText - строка поиска
     * @returns {object[]|null} - коллеция с найденными юзерами или null как признак ошибки
     */
    async search(sText) {
        const sData = (sText + '').trim();

        let response = await this.axios.get('/api/user/search/' + sData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.search`);
                throw new PliziAPIError(`$users.search`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }

    /**
     * гостевой поиск по юзерам
     * @param {string} sText - строка поиска
     * @returns {object[]|null} - коллеция с найденными юзерами или null как признак ошибки
     */
    async guestSearch(sText) {
        const sData = (sText + '').trim();

        let response = await this.axios.get('/api/user/search/' + sData)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.guestSearch`);
                throw new PliziAPIError(`$users.guestSearch`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Смена email на странице редактирования профиля.
     *
     * @param {object} formData
     * @returns {object[]|null}
     */
    async changeEmail(formData) {
        let response = await this.axios.post('/api/user/email/change', formData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.changeEmail`);
                throw new PliziAPIError(`$users.changeEmail`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    /**
     * Подписаться на пользователя
     * @param userId
     * @returns {Promise<null|any>}
     */
    async follow(userId) {
        let response = await this.axios.post(`api/user/${userId}/follow`, {}, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        data: {
                            message: error.response.data.message
                        }
                    }
                } else {
                    this.checkIsTokenExpires(error, '$users.follow');
                    throw new PliziAPIError('$users.follow', error.response);
                }
            });

        if ([200, 422].includes(response.status)) {
            return response.data;
        }

        return null;
    }

    /**
     * Отписаться от пользователя
     * @param userId
     * @returns {Promise<null|any>}
     */
    async unFollow(userId) {
        let response = await this.axios.delete(`api/user/${userId}/follow`, this.authHeaders)
            .catch((error) => {
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        data: {
                            message: error.response.data.message
                        }
                    }
                } else {
                    this.checkIsTokenExpires(error, '$users.unFollow');
                    throw new PliziAPIError('$users.unFollow', error.response);
                }
            });

        if ([200, 422].includes(response.status)) {
            return response.data;
        }

        return null;
    }

    /**
     * Список тех, на кого я подписан
     * @param limit
     * @param offset
     * @returns {Promise<null|*>}
     */
    async followList(limit, offset) {
        let path = 'api/user/follow/list';
        let qParams = '';

        if (limit && offset) {
            qParams = `?limit=${limit}&offset=${offset}`;
        }

        let response = await this.axios.get(path + qParams, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.followList`);
                throw new PliziAPIError(`$users.followList`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }

    /**
     * Список тех, на кого я подписан
     * @param userId
     * @param limit
     * @param offset
     * @returns {Promise<null|*>}
     */
    async userFollowList(userId, limit = 20, offset = 0) {
        let path = `api/user/${userId}/follow/list?limit=${limit}&offset=${offset}`;
        let response = await this.axios.get(path, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.followList`);
                throw new PliziAPIError(`$users.followList`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }

    /**
     * Список последних фотографий
     // * @param limit
     // * @param offset
     * @returns {Promise<null|*>}
     */
    async lastPhotos(userId) {
        let response = await this.axios.get(`/api/user/${userId}/photos`, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$users.lastPhotos');
                throw new PliziAPIError('$users.lastPhotos', error.response);
            });

        if (200 === response.status) {
            return response.data.data.list;
        }
        return null;
    }

    /**
     * Получение фотоальбомов пользователя по ID.
     * @param {string} userId
     * @public
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getUserPhotoalbums(userId) {
        let response = await this.axios.get(`api/user/${userId}/photo-albums`, this.authHeaders)
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, `getUserPhotoalbums` );
                throw new PliziAPIError( `getUserPhotoalbums`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Список сообществ пользователя
     // * @param limit
     // * @param offset
     * @returns {Promise<null|*>}
     */
    async getUserCommunities(userId, limit = 10, offset = 0) {
        let path = `/api/user/${userId}/communities`;
        const params = new URLSearchParams({
            limit: limit || 50,
            offset: offset || 0,
        });
        let response = await this.axios.get(path  + '?' + params.toString(), this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$users.getUserCommunities');
                throw new PliziAPIError('$users.getUserCommunities', error.response);
            });

        if (200 === response.status) {
            return response.data.data;
        }
        return null;
    }

    /**
     * Получение списка активных сессий.
     *
     * @returns {object[]|null}
     */
    async getActiveSessions() {
        let response = await this.axios.get('/api/user/sessions/active', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.getActiveSessions`);
                throw new PliziAPIError(`$users.getActiveSessions`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }

    /**
     * Закрытие всех соединений кроме текущей.
     *
     * @returns {object[]|null}
     */
    async closeActiveSessions() {
        let response = await this.axios.post('/api/user/sessions/close', {}, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$users.closeActiveSessions`);
                throw new PliziAPIError(`$users.closeActiveSessions`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }
}

export { PliziUsersAPI as default}
