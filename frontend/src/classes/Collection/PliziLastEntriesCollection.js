import PliziStoredCollection from './PliziStoredCollection.js';
import PliziLastEntry from '../PliziLastEntry.js';

class PliziLastEntriesCollection extends PliziStoredCollection {

    localStorageKey = `pliziLastEntries`;

    addNewLastEntries(entry) {
        this.restore();
        let collectionAsArray = this.asArray();

        if (this.collection.size < 5) {
            this.add(entry);
        } else {
            let entryWithMinDate = collectionAsArray.reduce(function (a, b) { return a < b ? a : b; });

            this.collection.delete(entryWithMinDate.id);
            this.add(entry);
        }

        this.storeData();
    }

    new(data){
        return new PliziLastEntry(data);
    }

    /**
     * метод сравнения для сортировки
     * @param {PliziLastEntry} d1
     * @param {PliziLastEntry} d2
     * @returns {number}
     */
    compare(d1, d2){
        if (d1.lastLoginUnix === d2.lastLoginUnix)
            return 0;

        return d1.lastLoginUnix > d2.lastLoginUnix ? -1 : 1;
    }

    /**
     * @return {PliziLastEntry[]}
     */
    asArray(){
        let arr = [];

        this._collection.forEach((value) => {
            arr.push( value );
        });

        arr.sort( this.compare );

        return arr;
    }

}

export default PliziLastEntriesCollection;
