import PliziAPI from './PliziAPI.js';
import PliziAuthUser from './PliziAuthUser.js';

import PliziFriendsCollection from './Collection/PliziFriendsCollection.js';
import PliziFavoritesCollection from './Collection/PliziFavoritesCollection.js';
import PliziDialogsCollection from './Collection/PliziDialogsCollection.js';
import PliziInvitationsCollection from './Collection/PliziInvitationsCollection.js';
import PliziNotificationsCollection from './Collection/PliziNotificationsCollection.js';
import PliziCommunitiesCollection from './Collection/PliziCommunitiesCollection.js';

class PliziAuthClass {
    /**
     * ключ в localStorage куда сохраняем данные юзера
     * @type {string}
     * @private
     */
    __localStorageKey = `pliziUser`;

    /**
     * @type {string}
     * @private
     */
    _token = ``;

    /**
     * @type {string}
     * @private
     */
    _channel = ``;

    /**
     * @type {PliziAuthUser}
     * @private
     */
    _user = null;

    /**
     * ID чисто для отладки, пока не удалять
     * @type {string}
     * @private
     */
    __lastUserID = null;

    /**
     * ссылка на API
     * @type {PliziAPI}
     * @private
     */
    _api = null;

    /**
     * ссылка на менеджер друзей
     * @type {PliziFriendsCollection}
     * @private
     */
    _frm = null;

    /**
     * ссылка на менеджер Избранных
     * @type {PliziFavoritesCollection}
     * @private
     */
    _fm = null;

    /**
     * ссылка на менеджер диалогов
     * @type {PliziDialogsCollection}
     * @private
     */
    _dm = null;

    /**
     * ссылка на - менеджер инвайтов
     * @type {PliziInvitationsCollection}
     * @private
     */
    _im = null;

    /**
     * ссылка на менеджер нотификаций
     * @type {PliziNotificationsCollection}
     * @private
     */
    _nm = null;

    /**
     * ссылка на менеджер сообществ
     * @type {PliziCommunitiesCollection}
     * @private
     */
    _cm = null;

    _isLoaded = false;

    __isInit = false;

    __restoreEventName = 'UserIsRestored';
    __loadEventName    = 'UserIsLoaded';
    __updateEventName  = 'UserIsUpdated';

    /**
     * @param {PliziAPI|PliziAPIClass} apiObj
     */
    init(apiObj){
        if (this.__isInit)
            return;

        this._api = apiObj;

        //this._user = new PliziAuthUser(null);
        Vue.set(this, '_user', new PliziAuthUser(null)); /** https://ru.vuejs.org/v2/guide/reactivity.html#Для-объектов **/

        this._frm= new PliziFriendsCollection(apiObj);
        this._fm = new PliziFavoritesCollection(apiObj);
        this._dm = new PliziDialogsCollection(apiObj);
        this._im = new PliziInvitationsCollection(apiObj);
        this._nm = new PliziNotificationsCollection(apiObj);
        this._cm = new PliziCommunitiesCollection(apiObj);

        this.__isInit = true;
    }

    /**
     * загружаем тут данные которые пришли от метода api/user или из localStorage
     * @param {Object} inputData
     * @param {string} token
     */
    updateAuthUserData(inputData, token){
        this.token = token;
        this.channel = inputData.channel;

        this.user.updateAuthUser(inputData.data);
        this.__lastUserID = inputData.data.id;

        this._isLoaded = true;

        this.storeUserData();
    }


    /**
     * сохраняет в localStorage (по ключу localStorageKey) данные юзера в строком виде
     * в том виде как это присылает api/user
     * @returns {string} - данные юзера в строком виде
     */
    storeUserData() {
        const jData = {
            data : this.user.toJSON(),
            channel : this.channel
        };

        const sData = JSON.stringify(jData);

        window.localStorage.setItem( this.__localStorageKey, sData );

        this.emit(this.__updateEventName);

        return sData;
    }


    /**
     * пытается восстановить данные юзера из localStorage
     * @returns {object|null} - данные юзера в виде объекта, если данные из localStorage
     */
    restoreData() {
        const sData = localStorage.getItem(this.__localStorageKey);

        if (typeof sData === 'undefined'  ||  sData===null  ||  sData==='')
            return null;

        const oData = JSON.parse(sData);

        return (oData  &&  oData.data  &&  oData.data.email  &&  oData.channel) ? oData : null;
    }

    /**
     * очищает данные
     */
    cleanData(){
        window.console.warn(this.__lastUserID, `Auth: cleanData`);
        delete this._user;
        this._user = new PliziAuthUser(null);

        this._isLoaded = false;

        localStorage.removeItem( this.__localStorageKey );
        localStorage.removeItem( 'pliziJWToken' );
        localStorage.removeItem( 'pliziChatChannel' );
        localStorage.removeItem( this.fm.localStorageKey );
        localStorage.removeItem( this.frm.localStorageKey );
        localStorage.removeItem( this.dm.localStorageKey );
        localStorage.removeItem( this.im.localStorageKey );
        localStorage.removeItem( this.nm.localStorageKey );
        localStorage.removeItem( this.cm.localStorageKey );
    }

    /**
     * ссылка на менеджер друзей
     * @returns {PliziFriendsCollection}
     */
    get frm(){
        return this._frm;
    }

    /**
     * ссылка на менеджер Избранных
     * @returns {PliziFavoritesCollection}
     */
    get fm(){
        return this._fm;
    }

    /**
     * ссылка на менеджер диалогов
     * @returns {PliziDialogsCollection}
     */
    get dm(){
        return this._dm;
    }

    /**
     * ссылка на менеджер инвайтов
     * @returns {PliziInvitationsCollection}
     */
    get im(){
        return this._im;
    }

    /**
     * ссылка на менеджер нотификаций
     * @returns {PliziNotificationsCollection}
     */
    get nm(){
        return this._nm;
    }

    /**
     * ссылка на менеджер сообщест
     * @returns {PliziCommunitiesCollection}
     */
    get cm(){
        return this._cm;
    }

    /**
     * @returns {PliziAPI}
     */
    get api(){
        return this._api;
    }

    get isLoaded(){
        return this._isLoaded;
    }

    get user(){
        return this._user;
    }

    /**
     * @returns {string}
     */
    get token(){
        return this._token;
    }

    /**
     * @param {string} jToken
     */
    set token(jToken){
       this._token = (jToken+'').trim();

        localStorage.setItem('pliziJWToken', this._token);
    }

    /**
     * @returns {string}
     */
    get channel(){
        return this._channel;
    }

    friendsIncrease(){
        this.setFriendsNumber(this.user.stats.totalFriendsCount + 1);
    }

    friendsDecrease(){
        this.setFriendsNumber(this.user.stats.totalFriendsCount - 1);
    }

    setFriendsNumber(newValue){
        this.user.stats.totalFriendsCount = (newValue >= 0) ? newValue : 0;
        this.storeUserData();
    }

    videosIncrease(){
        this.setVideosNumber(this.user.stats.videosCount + 1);
    }

    videosDecrease(){
        this.setVideosNumber(this.user.stats.videosCount - 1);
    }

    setVideosNumber(newValue){
        this.user.stats.videosCount = (newValue >= 0) ? newValue : 0;
        this.storeUserData();
    }

    followersIncrease(){
        this.setFollowersNumber(this.user.stats.followCount + 1);
    }

    followersDecrease(){
        this.setFollowersNumber(this.user.stats.followCount - 1);
    }

    setFollowersNumber(newValue){
        this.user.stats.followCount = (newValue >= 0) ? newValue : 0;
        this.storeUserData();
    }

    set channel(val){
        this._channel = val;

        localStorage.setItem('pliziChatChannel', this._channel);
    }

    emit(eventName, eventData){
        if (eventName) {
            this.api.emit(eventName, eventData || {});
        }
    }
}

//export { PliziAuth as default}
export const PliziAuth = new PliziAuthClass();
