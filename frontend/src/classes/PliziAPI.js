import Vue from 'vue';
import axios from 'axios';
import PliziAPIError from './API/PliziAPIError.js';

import PliziChatAPI from './API/PliziChatAPI.js';
import PliziPostAPI from './API/PliziPostAPI.js';
import PliziFriendAPI from './API/PliziFriendAPI.js';
import PliziCommunitiesAPI from './API/PliziCommunitiesAPI.js';
import PliziUsersAPI from './API/PliziUsersAPI.js';
import PliziNotificationsAPI  from './API/PliziNotificationsAPI.js';
import PliziPhotoalbumsAPI  from './API/PliziPhotoalbumsAPI.js';
import PliziVideoAPI from './API/PliziVideoAPI.js';
import PliziImageAPI from './API/PliziImageAPI.js';

class PliziAPIClass {

    /**
     * @type {AxiosInstance}
     * @private
     */
    __axios = null;

    /**
     * ссылка на Vue'шный $root
     * @type {Vue|null}
     * @private
     */
    __$root = null;

    /**
     * токен авторизации, который возвращает нам наш серверный API после логина
     * @type {string}
     * @private
     */
    __token = ``;

    /**
     * базовый URL для доступа к API по HTTP
     * @type {string}
     * @private
     */
    __baseURL = ``;

    __defaultHeaders = {
        'X-Requested-With': 'XMLHttpRequest',
    };

    /**
     * токен канала, который возвращает нам наш серверный API после логина
     * @type {string}
     * @private
     */
    __channel = ``;

    /**
     * базовый URL для доступа к API по WebSockets
     * @type {string}
     * @private
     */
    __baseWsURL = ``;

    __wsChannelCarrier = null;

    __wsIsConnected = false;

    __wsChannelOptions = {
        maxRetries: 10,
        retryDelay: 4000,
        skipSubprotocolCheck: true
    };

    /**
     * @type {PliziChatAPI}
     * @private
     */
    __chat = null;

    /**
     * @type {PliziPostAPI}
     * @private
     */
    __post = null;

    /**
     * @type {PliziFriendAPI}
     * @private
     */
    __friend = null;

    /**
     * @type {PliziCommunitiesAPI}
     * @private
     */
    __communities = null;

    /**
     * @type {PliziUsersAPI}
     * @private
     */
    __users = null;

    /**
     * @type {PliziNotificationsAPI}
     * @private
     */
    __notifications = null;

    /**
     * @type {PliziPhotoalbumsAPI}
     * @private
     */
    __photoalbums = null;

    /**
     * @type {PliziVideoAPI}
     * @private
     */
    __video = null;

    /**
     * @type {PliziImageAPI}
     * @private
     */
    __image = null;

    __isInit = false;

    /**
     * @param {Vue} $root - ссылка на Vue объект, который вызывает этот конструктор
     * @param {string} token
     */
    init($root, token) {
        if (this.__isInit)
            return;

        //this.__baseURL   = (window.apiURL) ? (window.apiURL + ``).trim() : ``;
        //this.__baseWsURL = (window.wsUrl) ? (window.wsUrl + ``).trim() : ``;
        this.__baseURL   = (process.env.API_URL) ? (process.env.API_URL + ``).trim() : ``;
        this.__baseWsURL = (process.env.WS_URL) ? (process.env.WS_URL + ``).trim() : ``;

        if ($root) {
            this.__$root = $root;
        }

        if (token) {
            this.token = token;
        }

        this.__axios = axios.create({
            baseURL: this.__baseURL,
            headers: this.__defaultHeaders
        });

        this.__chat = new PliziChatAPI(this);
        this.__post = new PliziPostAPI(this);
        this.__friend = new PliziFriendAPI(this);
        this.__communities = new PliziCommunitiesAPI(this);
        this.__users = new PliziUsersAPI(this);
        this.__notifications = new PliziNotificationsAPI(this);
        this.__photoalbums = new PliziPhotoalbumsAPI(this);
        this.__video = new PliziVideoAPI(this);
        this.__image = new PliziImageAPI(this);

        this.__isInit = true;
    }


    /**
     * @returns {PliziChatAPI}
     */
    get $chat() {
        return this.__chat;
    }

    /**
     * @returns {PliziPostAPI}
     */
    get $post() {
        return this.__post;
    }

    /**
     * @returns {PliziFriendAPI}
     */
    get $friend() {
        return this.__friend;
    }

    /**
     * @returns {PliziCommunitiesAPI}
     */
    get $communities() {
        return this.__communities;
    }

    /**
     * @returns {PliziUsersAPI}
     */
    get $users() {
        return this.__users;
    }

    /**
     * @returns {PliziNotificationsAPI}
     */
    get $notifications() {
        return this.__notifications;
    }

    /**
     * @return {PliziPhotoalbumsAPI}
     */
    get $photoalbums() {
        return this.__photoalbums;
    }

    /**
     * @return {PliziVideoAPI}
     */
    get $video() {
       return this.__video;
    }

    /**
     * @return {PliziImageAPI}
     */
    get $image() {
        return this.__image;
    }

    get axios() {
        return this.__axios;
    }


    /**
     * устанавливает токен для запросов
     * @param {string} jwToken
     */
    set token(jwToken) {
        //if (jwToken === ``) {
        //    window.console.warn(`API: try to set empty token!`);
        //}

        this.__token = (jwToken + '').trim();
        localStorage.setItem('pliziJWToken', this.__token);
    }

    /**
     * @returns {string} - токен для запросов
     */
    get token() {
        return this.__token;
    }


    set channel(cnl){
        //if (cnl === ``) {
        //    window.console.warn(`Try to set empty channel`);
        //}

        this.__channel = (cnl+'').trim();

        localStorage.setItem('pliziChatChannel', this.__channel);
    }


    get channel(){
        return this.__channel;
    }

    /**
     * пытаемся законнектится к WebSocket каналу
     * @param {string} cnlToken
     */
    connectToChannel(cnlToken){
        if (cnlToken !==``){
            this.__channel = cnlToken;
        }

        this.__tryConnectToWebSocketsChannel();
    }


    /**
     * метод для упрощения получения заголовков
     * @returns {{headers: {Authorization: string}}}
     * @private
     */
    __getAuthHeaders() {
        return {
            headers: {
                Authorization: this.__getBearer()
            }
        };
    }


    get authHeaders() {
        return this.__getAuthHeaders();
    }


    /**
     * метод для упрощения получения заголовков для отправки файлов
     * @returns {{headers: {Authorization: string, "Content-Type": string}}}
     * @private
     */
    __getAuthFileHeaders() {
        return {
            headers: {
                'Content-Type'  : 'multipart/form-data',
                'Authorization' : this.__getBearer()
            }
        };
    }

    get authFileHeaders(){
        return this.__getAuthFileHeaders();
    }

    /**
     * геттер для получения header'а bearer
     * @returns {string} - Bearer вместе с токеном
     */
    __getBearer() {
        if (this.token === ``) {
            window.console.warn(`API:getBearer - token is empty!`);

            throw new PliziAPIError(`getBearer`, {});
        }

        return 'Bearer ' + this.__token;
    }


    /**
     * @param email
     * @param password
     * @returns {Promise}
     */
    async login(email, password) {
        let loginData = {
            email: email.trim(),
            password: password.trim()
        };

        return await this.__axios.post('api/login', loginData)
            .catch((error) => {
                throw new PliziAPIError(`login`, error.response);
            });
    }


    /**
     * попытка регистрации пользователя
     * @public
     * @param {object} regData - регистрационные данные
     * @returns {Promise} - промис для обработки
     */
    async register(regData) {
        return await this.__axios.post('api/register', regData)
            .catch((error) => {
                throw new PliziAPIError(`register`, error.response);
            });
    }


    /**
     * Регистрация через соц. сети.
     * @param provider
     * @param token
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async registerThroughSocialServices(provider, token) {
        let response = await this.__axios.post(`/api/sociallogin/${provider}`, {token})
            .catch((error) => {
                throw new PliziAPIError(`registerThroughSocialServices`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * обновление данных юзера
     * @public
     * @param {Object} userData - данные юзера
     * @returns {Promise}
     */
    async updateUser(userData) {
        let response = await this.__axios.patch('api/user', userData, this.__getAuthHeaders())
            .catch((error) => {
                this.__checkIsTokenExperis(error, `updateUser`);
                throw new PliziAPIError(`updateUser`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }

    async updateUserPrivacy(privacyData) {
        let response = await this.__axios.patch('api/user/privacy', privacyData, this.__getAuthHeaders())
            .catch((error) => {
                this.__checkIsTokenExperis(error, `updateUserPrivacy`);
                throw new PliziAPIError(`updateUserPrivacy`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }


    /**
     * загружает аватарку юзера
     * @param {formData} formData - данные для загрузки
     * @returns {object|null} - ответ сервера
     * @throws PliziAPIError
     */
    async userProfileImage(formData) {
        let response = await this.__axios.post('/api/user/profile/image', formData, this.authFileHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `userProfileImage`);
                throw new PliziAPIError(`userProfileImage`, error.response);
            });

        if (response.status === 201) {
            return response.data;
        }

        return null;
    }


    /**
     * Отправка запроса на восстановление пароля.
     * @param email
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async recoveryPassword(email) {
        let response = await this.__axios.post('api/password/email', {email})
            .catch((error) => {
                throw new PliziAPIError(`recoveryPassword`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    /**
     * Обновление пароля.
     * @param formData
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async resetPassword(formData) {
        let response = await this.__axios.post('api/password/reset', formData)
            .catch((error) => {
                throw new PliziAPIError(`updatePassword`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    /**
     * Обновление пароля на странице профиля.
     * @param formData
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async changePassword(formData) {
        let response = await this.__axios.post('api/user/password/change', formData, this.__getAuthHeaders())
            .catch((error) => {
                throw new PliziAPIError(`changePassword`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }

    /**
     * Получение списка локаций по аргументу.
     * @param location
     * @returns {object[]|null}
     */
    async getLocationsByInput(location) {
        let response = await this.__axios.get(`api/city/search?search=${location}`, this.__getAuthHeaders())
            .catch((error) => {
                this.__checkIsTokenExperis(error, `getLocationsByInput`);
                throw new PliziAPIError(`getLocationsByInput`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


    /**
     * бросает событие с именем eventName через Vue'шный $root.emit
     * @private
     * @param {string} eventName
     * @param {object|boolean|null} eventData
     */
    emit(eventName, eventData) {
        if (this.__$root)
            return this.__$root.$emit(eventName, eventData || {});
    }

    /**
     **************************************************************************
     * Private section
     **************************************************************************
     */

    __tryConnectToWebSocketsChannel(){
        if (this.__wsIsConnected)
            return;

        setTimeout(()=>{
            if(this.channel !== ``) {
                this.__wsRealConnect();
            }
            else {
                this.__tryConnectToWebSocketsChannel();
            }
        }, 500);
    }

    __s = null;

    __wsRealConnect(){
        const channelOptions = {
            maxRetries: 10,
            retryDelay: 4000,
            skipSubprotocolCheck: true
        };

        this.__wsChannelCarrier = new ab.connect(this.__baseWsURL,
            (s)=>{this.__s = s; this.__channelReceiver(s)},
            (code, reason, detail)=>{this.__channelErrorHandler(code, reason, detail)},
            channelOptions
        );

        this.__wsIsConnected = true;
    }


    __channelReceiver(s) {
        s.subscribe(this.__channel, (channelID, data) => {
            if (`user.typing`===data.event_type) {
                //const compName = data.data.profile.firstName + ` `+data.data.profile.lastName;
                //window.console.info( (new Date()).getMilliseconds()+ ` ${data.chatId} ${compName}`, 'user.typing');
                //window.console.dir(data, 'WebSockets user.typing');
            }
            else {
                if (data) {
                    this.$dir( JSON.parse( JSON.stringify(data) ), 'from WebSockets server');
                }
            }

            if (channelID=== this.channel  &&  `user.typing`===data.event_type) {
                this.emit('userIsTyping', {
                    chatId :  data.chatId,
                    user : data.data,
                });
            }

            if (channelID=== this.channel  &&  `user.notification`===data.event_type) {
                this.emit('UserNotification', data.data);
                this.emit('NewAppNotification', {
                    type :  data.event_type,
                    notification : data.data
                });
            }

            if (channelID=== this.channel  &&  `message.new`===data.event_type) {
                this.emit('newMessageInDialog', {
                    chatId :  data.data.chatId,
                    message : data.data
                });
                this.emit('NewAppNotification', {
                   type :  data.event_type,
                   message : data.data
                });
            }

            if (channelID=== this.channel  &&  `message.deleted`===data.event_type) {
                this.emit('removeMessageInDialog', {
                    chatId :  data.data.chatId,
                    messageId : data.data.messageId,
                });
            }

            if (channelID=== this.channel  &&  `chat.removed`===data.event_type) {
                this.emit('NewAppNotification', {
                    type :  data.event_type,
                    chatId :  data.data.id
                });
                setTimeout(() => {
                    this.emit('remoteRemoveDialog', {
                        chatId :  data.data.id
                    });
                }, 300);

            }
            if (channelID=== this.channel  &&  `chat.created`===data.event_type) {
                this.emit('remoteCreateDialog', {
                    data :  data.data
                });
                this.emit('NewAppNotification', {
                   type :  data.event_type,
                   dialog :  data.data
                });
            }
            if (channelID=== this.channel  &&  `chat.attendee.appended`===data.event_type) {
                this.emit('remoteAddAttendee', {
                    data :  data.data
                });
                this.emit('NewAppNotification', {
                   type :  data.event_type,
                   dialog :  data.data
                });
            }
            if (channelID=== this.channel  &&  `chat.attendee.removed`===data.event_type) {
                this.emit('NewAppNotification', {
                    type :  data.event_type,
                    chatId :  data.data.id
                });
                setTimeout(() => {
                    this.emit('remoteRemoveAttendee', {
                        chatId :  data.data.id,
                        userId :  data.data.userId
                    });
                }, 100);
            }
        });
    }

    /** @param {object} sendData **/
    sendToChannel(sendData) {
        sendData.token = this.__token;

        if (this.__s) {
            this.__s.call('user.typing', sendData);
        }
    }

    __channelErrorHandler(code, reason, detail){
        //window.console.warn(`__channelErrorHandler`);
        //window.console.log(code, `code`);
        //window.console.log(reason, `reason`);
        //window.console.log(detail, `detail`);

        this.$info(`${code}: ${reason} ${(detail || '')}`);
    }


    /**
     * @private
     * @param {Object} errResponse
     * @returns {string}
     */
    __getServerMessage(errResponse) {
        if (errResponse && errResponse.data) {
            if (errResponse.data.message) {
                const serverMessage = (errResponse.data.message + '').trim();
                return serverMessage.trim().toUpperCase().replace(/\s/g, '_')
            }

            if (errResponse.data.messages && Array.isArray(errResponse.data.messages) && errResponse.data.messages.length > 0) {
                let serverMessages = [];
                errResponse.data.messages.map(mItem => serverMessages.push(mItem));
                return serverMessages.join('\r\n').trim();
            }
        }
    }


    /**
     * если в ответе сервер вернул, что `Token is expired`, то бросит событие `api:Unauthorized`
     * @private
     * @param {Object} error - ответ сервера с ошибкой в том виде как возвращает axios
     * @param {string} srcMethod - имя API-метода, который вызвал ошибку
     * @throws {Event} - событие `api:Unauthorized`
     */
    __checkIsTokenExperis(error, srcMethod) {
        const srvMsg = this.__getServerMessage(error.response);

        if (`TOKEN_IS_EXPIRED` === srvMsg) {
            this.emit(`api:Unauthorized`, {
                srcMethod: srcMethod || `pliziAPI`
            });
        }
    }


    checkIsTokenExpires(error, srcMethod) {
        return this.__checkIsTokenExperis(error, srcMethod);
    }

    $log(){
        if (process.env.NODE_ENV.toLowerCase() === 'production')
            return;

        if (window &&  console && console.log) {
            console.log.apply(window, arguments);
        }
    };

    $info(){
        if (process.env.NODE_ENV.toLowerCase() === 'production')
            return;

        if (window &&  console && console.info) {
            console.log.apply(window, arguments);
        }
    };

    $warn(){
        if (process.env.NODE_ENV.toLowerCase() === 'production')
            return;

        if (window &&  console && console.warn) {
            console.log.apply(window, arguments);
        }
    };

    $dir(){
        if (process.env.NODE_ENV.toLowerCase() === 'production')
            return;

        if (window &&  console && console.dir) {
            console.log.apply(window, arguments);
        }
    };

    $error = function(){
        if (process.env.NODE_ENV.toLowerCase() === 'production')
            return;

        if (window &&  console && console.error) {
            console.log.apply(window, arguments);
        }
    };

}

//export {PliziAPI as default}
export const PliziAPI = new PliziAPIClass();
