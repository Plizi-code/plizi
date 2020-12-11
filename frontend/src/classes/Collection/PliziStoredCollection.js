import PliziAPI from '../PliziAPI.js';
import PliziCollection from '../PliziCollection.js';

/**
 * класс упрощения работы с коллекциями
 */
class PliziStoredCollection extends PliziCollection{

    /**
     * ключ в localStorage куда сохраняем данные
     * @type {string}
     */
    localStorageKey = ``;

    /**
     * ссылка на API
     * @type {PliziAPI}
     * @private
     */
    _api = null;

    /**
     * имя эвента, который мы кидаем когда данные в коллекцию загружены с сервера
     * @type {string}
     * @public
     */
    loadEventName = '';

    /**
     * имя эвента, который мы кидаем когда данные в коллекцию загружены с localStorage
     * @type {string}
     * @public
     */
    restoreEventName = '';

    /**
     * имя эвента, который мы кидаем когда из коллекции сохранены в localStorage
     * @type {string}
     * @public
     */
    storeEventName = '';

    /**
     * имя эвента, который мы кидаем когда данные в коллекции обновлены
     * @type {string}
     * @public
     */
    updateEventName = '';

    /**
     * размер "страницы" для дозагрузки
     * @type {number}
     */
    pageSize = 10;

    /**
     * флаг, что данные были загружены из источника (серверное API)
     * @type {boolean}
     * @private
     */
    _isLoad = false;


    /**
     * @param {PliziAPI} apiObj
     */
    constructor(apiObj){
        super(null, null);
        this._api = apiObj;
    }

    /**
     * @returns {PliziAPI}
     */
    get api(){
        return this._api;
    }

    get isLoad(){
        return this._isLoad;
    }

    set isLoad( value ){
        this._isLoad = value;
    }

    restore(){
        this.clear();

        this.restoreData();
        this.isLoad = true;
    }

    /**
     * должен получать респонс от сервера, сам преобразует в коллекцию нужного вида
     * @param {object[]} entities
     */
    load(entities){
        this._isLoad = true;
    }

    /**
     * сохраняет в localStorage данные коллекции
     * @returns {string} - данные коллекции в строком виде
     */
    storeData() {
        const sData = this.toString();
        window.localStorage.setItem( this.localStorageKey, sData );
        if (this.storeEventName) {
            this.emit(this.storeEventName);
        }

        return sData;
    }

    /**
     * пытается восстановить данные коллекции из localStorage
     * @returns {object|null} - данные коллекции в виде объекта, если данные из localStorage
     */
    restoreData() {
        const sData = localStorage.getItem(this.localStorageKey);

        if (typeof sData === 'undefined'  ||  sData===null  ||  sData===``)
            return null;

        let oData = null;

        try {
            oData = JSON.parse(sData);

            if (oData) {
                oData.map((oItem)=>{ this.add( oItem ); });
                if (this.restoreEventName) {
                    this.emit(this.restoreEventName);
                }
            }
        }
        catch (e){
            if ( window.console !== undefined && window.console.error ) window.console.warn( e.toString() );

            return null;
        }

        return this.toJSON();
    }

    emit(eventName, eventData){
        if (eventName) {
            this.api.emit(eventName, eventData || {});
        }
    }

}

export { PliziStoredCollection as default }
