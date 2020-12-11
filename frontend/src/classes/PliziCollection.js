/**
 * класс упрощения работы с коллекциями
 * @see https://developer.mozilla.org/ru/docs/Web/JavaScript/Reference/Global_Objects/Map
 */
class PliziCollection {

    /**
     * @type {Map}
     * @private
     */
    _collection = null;

    constructor(inputData, objClass){
        //this._collection = new Map(); /** @TGA пока не удалять **/
        Vue.set(this, '_collection', new Map()); /** https://ru.vuejs.org/v2/guide/reactivity.html#Для-объектов **/

        if(inputData  &&  objClass) {
            this.receive(inputData, objClass);
        }
    }

    get keys(){
        return this._collection.keys();
    }

    get list(){
        return this._collection;
    }

    get collection(){
        return this._collection;
    }

    get size(){
        return this._collection.size;
    }

    get length(){
        return this._collection.size;
    }

    clear(){
        this._collection.clear();
    }

    get first(){
        return Array.from(this._collection)[0][1];
    }

    get last(){
        return Array.from(this._collection)[this._collection.size-1][1];
    }

    /**
     * метод сравнения для сортировки
     * @param {PliziRecipient} d1
     * @param {PliziRecipient} d2
     * @returns {number}
     */
    compare(d1, d2){
        return d1.fullName > d2.firstName ? 1 : -1;
    }

    /**
     * @param {object} data
     */
    add(data){
        if (data.id) {
            const conv = this.new(data);
            this._collection.set(conv.id, conv);
            return data.id;
        }

        window.console.warn(`PliziCollection: empty id for entity`);
        return false;
    }

    /**
     * @param {number|string} id
     * @param {Object} data
     */
    set(id, data) {
        const conv = this.new(data);
        this._collection.set(conv.id, conv);
    }

    receive(inputArray, objClass){
        if (inputArray){
            inputArray.map( ( oItem ) => {
                this.add( new objClass( oItem ) );
            } );
        }
    }

    new(data){
        return data;
    }

    /**
     * @param {object} data
     */
    update(data){
        this.add(data);
    }

    /**
     * поиск в коллекции по ID
     * @param {number} ID - ID нужной сущности
     * @returns {Object} - нужная сущность, или UNDEFINED если не нашли
     */
    get(ID){
        return this._collection.get(ID);
    }


    delete(ID){
        this._collection.delete(ID);
    }


    /**
     * @param {Function} checkFunction
     * @returns {Object[]}
     */
    filter(checkFunction){
        let arr = [];

        this._collection.forEach((value) => {
            if (checkFunction(value)) {
                arr.push( value );
            }
        });

        return arr;
    }


    /**
     * @param {Function|null} compareFunction
     * @returns {Object[]}
     */
    asArray(compareFunction){
        let arr = [];

        this._collection.forEach((value) => {
            arr.push( value );
        });

        if (compareFunction){
            arr.sort( compareFunction );
        }
        else {
            arr.sort( this.compare );
        }

        return arr;
    }

    toString(){
        return JSON.stringify( this.toJSON() );
    }

    toJSON() {
        if (this.collection.size === 0)
            return [];

        let ret = [];

        this.collection.forEach((aItem)=>{
            ret.push( aItem.toJSON() );
        });

        return ret;
    }

}

export { PliziCollection as default }
