import PliziCommunity from '../PliziCommunity.js';
import PliziStoredCollection from './PliziStoredCollection.js';

/**
 * класс для работы со списком сообществ
 */
class PliziCommunitiesCollection extends PliziStoredCollection {

    localStorageKey = 'pliziCommunities';

    restoreEventName= 'CommunitiesIsRestored';
    loadEventName   = 'CommunitiesIsLoaded';
    updateEventName = 'CommunitiesIsUpdated';

    constructor(apiObj){
        super(apiObj);
    }


    /**
     * метод сравнения для сортировки, сортируем по названию
     * @param {PliziCommunity} d1
     * @param {PliziCommunity} d2
     * @returns {number}
     */
    compare(d1, d2){
        if (d1.name.toUpperCase() === d2.name.toUpperCase())
            return 0;

        return d1.name.toUpperCase() > d2.name.toUpperCase() ? -1 : 1;
    }


    onAddToFavorites(evData){
        this.add(evData);
        this.storeData();
        this.emit(this.updateEventName);
    }


    removeFromFavorites(removedCommunityId){
        this.delete(removedCommunityId);
        this.storeData();
        this.emit(this.updateEventName);
    }


    new(data){
        return new PliziCommunity(data);
    }


    /**
     * поиск диалога по его ID
     * @param {string} ID - ID диалога
     * @returns {PliziCommunity} - диалог, или UNDEFINED если не нашли
     */
    get(ID){
        return this.collection.get(ID);
    }


    isCanAddToFavorites(ID){
        return !this.collection.get(+ID);
    }

    /**
     * @returns {PliziCommunity[]}
     */
    asArray(){
        let arr = [];

        this.collection.forEach((value) => {
            arr.push( value );
        });

        arr.sort( this.compare );

        return arr;
    }


    async load(){
        let apiResponse = null;

        try {
            apiResponse = await this.api.$communities.favorites();
        }
        catch (e){
            window.console.warn(e.detailMessage);
        }

        if (apiResponse) {
            this.clear();

            apiResponse.map( (favItem) => {
                this.add( favItem );
            });

            this.storeData();
            this.isLoad = true;

            if (this.loadEventName) {
                this.emit(this.loadEventName);
            }
        }

        return true;
    }

}

export { PliziCommunitiesCollection as default }
