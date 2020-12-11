import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziFriendAPI extends PliziBaseAPI {

    /**
     * получаем список френдов, свой или другого юзера
     * @param {number} userID - ID юзера чей список друзей хотим получить
     * @param {number} limit - лимит считывания
     * @param {number} offset - смещение от начала
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async friendsList(userID, limit, offset){
        let path = 'api/user/friendship';

        if (userID) {
            path = `api/user/${userID}/friendship`;
        }

        let qParams = '';
        if (limit && offset) {
            qParams = `?limit=${limit}&offset=${offset}`;
        }

        let response = await this.axios.get(path + qParams, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friend.friendsList');
                throw new PliziAPIError('$friend.friendsList', error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


    /**
     * получаем список Избранных френдов
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async favorites(){
        let response = await this.axios.get('api/user/friendship/group/featured', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friend.favorites');
                throw new PliziAPIError('$friend.favorites', error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


    /**
     * Получение списка возможных друзей.
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getPossibleFriends(){
        const apiPath = 'api/user/friendship/possible/?offset=0&limit=10'
        let response = await this.axios.get( apiPath, this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, '$friend.getPossibleFriends' );
                throw new PliziAPIError( `$friend.getPossibleFriends`, error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }


    /**
     * Получение списка рекомендуемых друзей
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async getRecommendedFriends(){
        let response = await this.axios.get( 'api/user/friendship/recommended', this.authHeaders )
            .catch( ( error ) => {
                this.checkIsTokenExpires( error, '$friend.getRecommendedFriends' );
                throw new PliziAPIError( '$friend.getRecommendedFriends', error.response );
            } );

        if ( response.status === 200 ){
            return response.data.data.list;
        }

        return null;
    }


    /**
     * отправляет приграшение дружбы
     * поле status в ответе:
     * 200 - инвайт отправили
     * 422 - инвайт отправляли ранее
     * @param {number} futureFriendID - ID юзера с которым хотим подружиться
     * @returns {{ status: number, message: string }}
     * @throws PliziAPIError
     */
    async sendFriendshipInvitation(futureFriendID) {
        const data = {
            userId: futureFriendID
        };

        return await this.axios.post('/api/user/friendship', data, this.authHeaders)
            .catch((error) => {
                /** @TGA так сервер отвечает, что инвайт уже отправлялся **/
                if (error.response.status === 422) {
                    return {
                        status: 422,
                        message: error.response.data.message
                    }
                }
                else {
                    this.checkIsTokenExpires(error, '$friend.sendFriendshipInvitation');
                    throw new PliziAPIError('$friend.sendFriendshipInvitation', error.response);
                }
            });
    }


    /**
     * удаляет френда из друзей
     * @param {number} userID - ID френда
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async friendshipStop(userID) {
        let response = await this.axios.delete('/api/user/friendship/'+userID, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friends.friendshipStop');
                throw new PliziAPIError('$friends.friendshipStop', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * получаем список приглашений дружбы
     * @returns {object[]|null} - список инвайтов или NULL
     * @throws PliziAPIError
     */
    async invitationsList() {
        let response = await this.axios.get('api/user/friendship/pending', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friends.invitationsList');
                throw new PliziAPIError('$friends.invitationsList', error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


    /**
     * принимаем приглашение дружбы
     * @param friendID
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async invitationAccept(friendID) {
        const sendData = {
            userId: friendID
        };

        let response = await this.axios.post('api/user/friendship/accept', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friends.invitationAccept');
                throw new PliziAPIError('$friends.invitationAccept', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * отклоняем приглашение дружбы
     * @param friendID
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async invitationDecline(friendID) {
        const sendData = {
            userId: friendID
        };

        let response = await this.axios.post('api/user/friendship/decline', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friends.invitationDecline');
                throw new PliziAPIError('$friends.invitationDecline', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * добавляет френда в Избранные
     * @param {number} userID - ID френда
     * @returns {object|null}
     * @throws PliziAPIError
     */
    async addToFavorites(userID) {
        const sendData = {
            userId: userID,
            group: 'featured'
        };

        let response = await this.axios.post('api/user/friendship/group', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friends.addToFavorites');
                throw new PliziAPIError('$friends.addToFavorites', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    async removeFromFavorites(userID) {
        let response = await this.axios.delete('api/user/friendship/group/featured/'+userID, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$friends.removeFromFavorites');
                throw new PliziAPIError('$friends.removeFromFavorites', error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

}

export default PliziFriendAPI;
