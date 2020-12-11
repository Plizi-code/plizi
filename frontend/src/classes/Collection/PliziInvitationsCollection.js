import PliziStoredCollection from './PliziStoredCollection.js';

import PliziInvitation from '../PliziInvitation.js';
import PliziNotification from '../PliziNotification.js';

/**
 * класс для работы со списком приглашений дружбы
 */
class PliziInvitationsCollection extends PliziStoredCollection {

    localStorageKey  = `pliziInvitations`;

    restoreEventName = 'InvitationsIsRestored';
    loadEventName    = 'InvitationsIsLoaded';
    updateEventName  = 'InvitationsIsUpdated';

    /**
     * метод сравнения для сортировки
     * @param {PliziInvitation} d1
     * @param {PliziInvitation} d2
     * @returns {number}
     */
    compare(d1, d2){
        if (d1.fullName === d2.fullName)
            return 0;

        return d1.fullName > d2.fullName ? -1 : 1;
    }


    /**
     * обработка прилетевшей нотификации, что кто-то хочет подружиться
     * @param {PliziNotification} evData - тут данные в виде объекта-нотификации
     * @throws PliziAPIError
     * @emits InvitationsIsUpdated
     */
    async onAddNewInvitation(evData){
        const userId = evData.data.sender.id;

        let apiResponse = null;

        try {
            apiResponse = await this.api.$users.getUser(userId);
        }
        catch (e){
            window.console.warn(e.detailMessage);
            throw e;
        }

        if (apiResponse) {
            this.add(apiResponse.data);

            this.storeData();
            this.restore();
            this.emit(this.updateEventName, { invitationId : userId });
            return true;
        }

        return false;
    }


    removeInvitation(invitationId){
        this.delete(invitationId);
        this.storeData();
        this.restore();
        this.emit(this.updateEventName, { invitationId : invitationId });
    }


    new(data){
        return new PliziInvitation(data);
    }


    /**
     * поиск приглашения по ID
     * @param {number} ID - ID нужной сущности
     * @returns {PliziInvitation} - нужная сущность, или UNDEFINED если не нашли
     */
    get(ID){
        return this.collection.get(ID);
    }


    /**
     * @returns {PliziInvitation[]}
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
        this.clear();

        let apiResponse = null;

        try {
            apiResponse = await this.api.$friend.invitationsList();
        }
        catch (e){
            window.console.warn(e.detailMessage);
        }

        if (apiResponse) {
            apiResponse.map( (dialogItem) => {
                this.add( dialogItem );
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

export { PliziInvitationsCollection as default }
