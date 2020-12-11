import PliziFriend from '../PliziFriend.js';
import PliziStoredCollection from './PliziStoredCollection.js';

/**
 * класс для работы со списком друзей
 */
class PliziFriendsCollection extends PliziStoredCollection {

    localStorageKey  = `pliziFriends`;

    restoreEventName = 'FriendsIsRestored';
    loadEventName    = 'FriendsIsLoaded';
    updateEventName  = 'FriendsIsUpdated';

    pageSize = 10;

    /**
     * метод сравнения для сортировки
     * @param {PliziFriend} d1
     * @param {PliziFriend} d2
     * @returns {number}
     */
    compare(d1, d2){
        if (d1.compareName === d2.compareName)
            return 0;

        return d1.compareName < d2.compareName ? -1 : 1;
    }

    onAddAcceptFriendsShip(evData){
        this.add(evData);
        this.storeData();
        this.restore();
        this.emit(this.updateEventName, evData);
    }

    onAddAcceptOurInvitation(evData){
        if (evData.data &&  evData.data.user) {
            this.add(evData.data.user);
            this.storeData();
            this.restore();
            this.emit(this.updateEventName, evData.data.user);
        }
        else {
            window.console.warn('onAddAcceptOurInvitation: incorrect data structure');
        }
    }

    stopFriendship(removedFriendId){
        this.delete(removedFriendId);
        this.storeData();
        this.restore();
        this.emit(this.updateEventName, { friendId : removedFriendId });
    }


    /**
     * возвращает список избранных друзей
     * TODO: переписать на реальный возврат
     * @returns {PliziFriend[]} - список избранных друзей
     */
    get favorites(){
        return this.asArray().slice(0, 10);
    }


    /**
     * проверяем есть юзер с userID ли во френдах
     * @param {number} userID - проверяемый ID
     * @returns {boolean} - true если userID есть во френдах
     */
    checkIsFriend(userID){
        const res = this.collection.get(userID);
        return !! res;
    }


    new(data){
        return new PliziFriend(data);
    }


    /**
     * поиск друга по ID
     * @param {string} ID - ID нужной сущности
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

        arr.sort( this.compare );

        return arr;
    }


    async load(){
        this.clear();

        let apiResponse = null;

        try {
            apiResponse = await this.api.$friend.friendsList();
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


    async additionalLoad(){
        let apiResponse = null;

        try {
            apiResponse = await this.api.$friend.friendsList(null, this.pageSize, this.size+1);
        }
        catch (e){
            window.console.warn(e.detailMessage);
        }

        let addedNum = 0;

        if (apiResponse) {
            apiResponse.map( (notifItem) => {
                this.add( notifItem );
                this.collection.has( 'xxx' );
                addedNum++;
            });

            this.storeData();

            this.isLoad = true;

            if (this.loadEventName) {
                this.emit(this.loadEventName);
            }
        }

        return addedNum;
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

export { PliziFriendsCollection as default }
