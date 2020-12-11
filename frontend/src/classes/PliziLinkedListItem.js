class PliziLinkedListItem {

    /**
     * ID элемента списка
     * @type {number|string}
     * @private
     */
    _self = null;

    /**
     * ID предыдущего элемента списка
     * @type {number|string}
     * @private
     */
    _prev = null;

    /**
     * ID следующего элемента списка
     * @type {number|string}
     * @private
     */
    _next = null;

    /**
     * @param {number|string} self - ID самого элемента
     * @param {number|string} prev - ID предыдущего элемента списка
     * @param {number|string} next - ID следующего элемента списка
     */
    constructor( self, prev, next ){
        this._self = self;
        this._prev = prev;
        this._next = next;
    }

    get self(){
        return this._self;
    }

    set self( value ){
        this._self = value;
    }

    get prev(){
        return this._prev;
    }

    set prev( value ){
        this._prev = value;
    }

    get next(){
        return this._next;
    }

    set next( value ){
        this._next = value;
    }

    toJSON(){
        return {
            self: this.self,
            prev: this.prev,
            next: this.next,
        }
    }
}

export { PliziLinkedListItem as default }
