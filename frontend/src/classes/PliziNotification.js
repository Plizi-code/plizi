import PliziNotificationData from './Notification/PliziNotificationData.js';

class PliziNotification {
    /**
     * в формате UUID - ae62a84c-3d9c-4d52-8f71-ec0c1939e2ad
     * @type {string}
     * @private
     */
    _id = null;

    /**
     * @type {Date}
     * @private
     */
    _createdAt = null;

    /**
     *
     * @type {Date|null}
     * @private
     */
    _readAt = null;

    /**
     * @type {PliziNotificationData}
     * @private
     */
    _data = null;


    constructor(notif){
        this._id = notif.id || '';
        this.createdAt = notif.createdAt;
        this._readAt = (notif.readAt) ? (new Date(notif.readAt)) : null;
        this._data = (notif.data) ? (new PliziNotificationData(notif.data)) : null;
    }

    get id() {
        return this._id;
    }

    get createdAt() {
        return this._createdAt;
    }

    set createdAt(dValue) {
        if ( !(!!dValue) ) {
            this._createdAt = null;
            return;
        }

        // Unixtime
        if ( typeof dValue === 'number') {
            this._createdAt = new Date(dValue * 1000);
            return;
        }

        // String или Date
        this._createdAt = new Date(dValue);
    }

    get readAt() {
        return this._readAt;
    }

    get data() {
        return this._data;
    }

    get senderId(){
        return this.data.sender.id;
    }

    get communityId(){
        try{
            return this.data.community.id;
        } catch (e){
            if ( console !== undefined && console.error ) console.error( e );
        }
    }

    get senderFirstName(){
        return this.data.sender.firstName;
    }

    get senderLastName(){
        return this.data.sender.lastName;
    }

    get senderFullName(){
        return this.data.sender.fullName;
    }

    get communityName(){
        try{
            return this.data.community.name;
        } catch (e){
            if ( console !== undefined && console.error ) console.error( e );
        }
    }

    get senderPic(){
        return this.data.sender.userPic;
    }

    get communityPic(){
        try{
            return this.data.community.primaryImage;
        } catch (e){
            if ( console !== undefined && console.error ) console.error( e );
        }
    }

    get notifType(){
        return this.data.notificationType;
    }

    get notifBody(){
        return this.data.body;
    }

    get notifMessage(){
        return this.data.message;
    }

    get isCommunityNotification(){
        try{
            return !! this.data.community;
        } catch (e){
            if ( console !== undefined && console.error ) console.error( e );
        }
    }

    get isHumanNotification(){
        return !! this.data.sender;
    }

    toString(){
        return JSON.stringify( this.toJSON() );
    }

    toJSON(){
        return {
            id : this._id,
            createdAt: (this._createdAt) ? (this.createdAt.getTime() / 1000)>>>0 : null ,
            reedAt : (this._readAt) ? (this.readAt.getTime() / 1000)>>>0 : null,
            data : (this._data) ? this.data.toJSON() : null
        }
    }
}

export { PliziNotification as default}
