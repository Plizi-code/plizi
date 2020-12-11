import PliziAPI from './PliziAPI.js';

class PliziManager {

    /**
     * коллекция сущностей
     * @type {object[]}
     * @private
     */
    _collection = [];

    /**
     * флаг, что данные были загружены из источника (серверное API)
     * @type {boolean}
     * @private
     */
    _isLoad = false;

    /**
     * ссылка на API
     * @type {PliziAPI}
     * @private
     */
    _api = null;


    /**
     * @param {PliziAPI} apiObj
     */
    constructor(apiObj){
        this._api = apiObj;
    }

    get api(){
        return this._api;
    }

    get amount(){
        return this._collection.length;
    }

    get list(){
        return this._collection;
    }

    clean(){
        this._collection = [];
        this._isLoad = false;
    }

    get isLoad(){
        return this._isLoad;
    }

    set isLoad( value ){
        this._isLoad = value;
    }


    /**
     * метод сравнения для сортировки
     * @param d1
     * @param d2
     * @returns {number}
     * @private
     */
    __compare(d1, d2){
        if (d1 > d2) return -1;
        if (d2 > d1) return 1;

        return 0;
    }


    /**
     * поиск в коллекции по ID
     * @param {number} ID - ID нужной сущности
     * @returns {object} - нужная сущность, или UNDEFINED если не нашли
     */
    getByID(ID){
        return this._collection.find( dItem => ID === dItem.id);
    }


    removeByID(ID){
        this._collection = this._collection.filter( dItem => ID !== dItem.id);
        return this._collection;
    }


    reArrange(){
        this._collection = this._collection.slice().sort(this.__compare);
    }


    /**
     * @param {number} ID - ID сущности, которую нужно обновить
     * @param {object} updatedData - объект с полями
     */
    update(ID, updatedData){
        let frnd = this.getByID(ID);
    }


    /**
     * должен получать респонс от сервера, сам преобразует в коллекцию нужного вида
     * @param {object[]} entities
     */
    load(entities){
        //this.clean();
        //
        //entities.map( (invItem) => {
        //    this._collection.push( invItem );
        //});
        //
        //this._isLoad = true;
    }

}

export { PliziManager as default}
