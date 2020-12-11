import PliziFriend from '../PliziFriend.js';
import PliziStoredCollection from './PliziStoredCollection.js';
//import PliziStoredList from './PliziStoredList.js';

/**
 * класс для работы со списком Избранных
 */
class PliziFavoritesCollection extends PliziStoredCollection {

    localStorageKey = `pliziFavorites`;

    restoreEventName = 'FavoritesIsRestored';
    loadEventName = 'FavoritesIsLoaded';
    updateEventName = 'FavoritesIsUpdated';

    /**
     * метод сравнения для сортировки
     * @param {PliziFriend} d1
     * @param {PliziFriend} d2
     * @returns {number}
     */
    compare(d1, d2){
        if (d1.fullName === d2.fullName)
            return 0;

        return d1.fullName > d2.fullName ? -1 : 1;
    }


    onAddToFavorites(evData){
        this.add(evData);
        this.storeData();
        this.restore();
        this.emit(this.updateEventName, evData);
    }


    removeFromFavorites(removedFriendId){
        this.delete(removedFriendId);
        this.storeData();
        this.restore();
        this.emit(this.updateEventName, { friendId : removedFriendId });
    }


    /**
     * проверяем есть юзер с userID ли в Избранных
     * @param {string} userID - проверяемый ID
     * @returns {boolean} - true если userID есть во Избранных
     */
    checkIsFavorite(userID){
        const res = this.collection.get(userID);
        return !! res;
    }


    new(data){
        return new PliziFriend(data);
    }


    /**
     * поиск друга по ID
     * @param {number} ID - ID нужной сущности
     * @returns {PliziFriend} - нужная сущность, или UNDEFINED если не нашли
     */
    get(ID){
        return this.collection.get(ID);
    }


    /**
     * @returns {PliziFriend[]}
     */
    asArray(){
        let arr = [];

        this.collection.forEach((notifItem) => {
            arr.push( notifItem );
        });

        return arr;
    }


    async load(){
        this.clear();

        let apiResponse = null;

        try {
            apiResponse = await this.api.$friend.favorites();
        }
        catch (e){
            window.console.warn(e.detailMessage);
        }

        if (apiResponse) {
            apiResponse.map( (notifItem) => {
                this.add( notifItem );
            });

            this.storeData();

            this.isLoad = true;

            if (this.loadEventName) {
                this.emit(this.loadEventName);
            }
        }

        return true;
    }


    updateOnlineStatus(userID){
        let favorite = this.get(userID);

        if (favorite) {
            favorite.lastActivity = (new Date()).valueOf()/1000;
            favorite.isOnline = true;

            this.add(favorite);
            this.storeData();
        }
    }

}

export { PliziFavoritesCollection as default }
