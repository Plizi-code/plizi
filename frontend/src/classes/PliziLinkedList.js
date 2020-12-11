import PliziLinkedListItem from './PliziLinkedListItem.js';
import PliziCollection from './PliziCollection.js';

/**
 * двусвязный список
 */
class PliziLinkedList extends PliziCollection {

    /**
     * ID первого элемента в списке
     * @type {number|string}
     * @private
     */
    _headId = null;

    /**
     * ID последнего элемента в списке
     * @type {number|string}
     * @private
     */
    _tailId = null;

    /**
     * MAP, в котором храним ноды
     * @type {Map}
     * @private
     */
    _nodes = null;

    constructor(){
        super(null);
        this._nodes = new Map();
    }

    /**
     * @private
     * @param {object} data
     */
    add(data){
        window.console.warn('PliziLinkedList::add - запрещён для связных списков. Используйте append/prepend');
    }

    /**
     * @returns {Map}
     */
    get nodes(){
        return this._nodes;
    }

    /**
     * @param {string|number} id
     * @returns {PliziLinkedListItem}
     */
    getNode(id){
        return this._nodes.get(id);
    }

    append(data) {
        super.add(data);

        this._nodes.set(data.id, new PliziLinkedListItem(data.id, this._tailId, null ) );

        if (this._tailId) {
            const prevTail = this.getNode(this._tailId);

            if (prevTail) {
                prevTail.next = data.id;

                this._nodes.set(prevTail.id, new PliziLinkedListItem(prevTail.id, prevTail.prev, prevTail.next ) );
            }
        }

        this._tailId = data.id;

        if (!this._headId) {
            this._headId = data.id;
        }
    }


    prepend(data) {
        super.add(data);

        this._nodes.set(data.id, new PliziLinkedListItem(data.id, null, this._headId ) );

        if (this._headId) {
            const prevHead = this.getNode(this._headId);

            if (prevHead) {
                prevHead.prev = data.id;

                this._nodes.set(prevHead.id, new PliziLinkedListItem(prevHead.id, prevHead.prev, prevHead.next ) );
            }
        }

        this._headId = data.id;

        if (!this._tailId) {
            this._tailId = data.id;
        }
    }


    clear(){
        super.clear();
        this.nodes.clear();

        this._headId = null;
        this._tailId = null;
    }


    delete(ID) {
        const delItem = this.nodes.get(ID);

        if (!delItem)
            return;

        let oPrev = delItem.prev ? this.nodes.get(delItem.prev).toJSON() : null;
        let oNext = delItem.next ? this.nodes.get(delItem.next).toJSON() : null;

        oPrev.next = oNext ? oNext.self : null;
        oNext.prev = oPrev ? oPrev.self : null;

        super.delete(ID);
        this.nodes.delete(ID);

        if (oPrev) {
            this.nodes.set( oPrev.self, new PliziLinkedListItem(oPrev.self, oPrev.prev, oPrev.next ) );
        }
        else {
            this._headId = oNext ? oNext.self : null;
        }

        if (oNext) {
            this.nodes.set( oNext.self, new PliziLinkedListItem(oNext.self, oNext.prev, oNext.next ) );
        }
        else {
            this._tailId = oNext ? oNext.self : null;
        }
    }

    get first(){
        return this.collection.get(this._headId);
    }

    get last(){
        return this.collection.get(this._tailId);
    }

}

export { PliziLinkedList as default }
