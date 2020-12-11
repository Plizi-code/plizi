import { convertToDate } from '../utils/DateUtils.js';

class PliziAttendee {
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = `/images/noavatar-256.png`;

    /**
     * ID собеседника
     * @type {string}
     * @private
     */
    _id = null;

    /**
     * время последней активности собеседника
     * TODO: с бэка приходит как lastActivityDT
     * @type {Date}
     * @private
     */
    _lastActivity = null;

    /**
     * флаг собеседник онлайн или нет
     * @type {Boolean}
     * @private
     */
    _isOnline = null;

    /**
     * аватарка собеседника
     * @type {string}
     * @private
     */
    _userPic = null;

    /**
     * имя собеседника
     * @type {string}
     * @private
     */
    _firstName = null;

    /**
     * фамилия собеседника
     * @type {string}
     * @private
     */
    _lastName = null;

    /**
     * пол собеседника
     * @type {string}
     * @private
     */
    _sex = null;

    /**
     * флаг, админ этот себеседник или нет
     * @type {Boolean}
     * @private
     */
    _isAdmin = false;

    constructor( attData ){
        this._id = attData.id;
        this._lastActivity = attData.lastActivity ? convertToDate(attData.lastActivity) : this.lastActivity;
        this._isOnline = attData.isOnline;
        this._userPic = attData.userPic;
        this._firstName = attData.firstName;
        this._lastName = attData.lastName;
        this._sex = attData.sex;
        this._isAdmin = !!attData.isAdmin;
    }


    get id(){
        return this._id;
    }

    get lastActivity(){
        return this._lastActivity;
    }

    set lastActivity(tValue){
        this._lastActivity = convertToDate(tValue);
    }

    get isAdmin(){
        return this._isAdmin;
    }

    get isOnline(){
        return this._isOnline;
    }

    set isOnline(olValue){
        this._isOnline = !!olValue;
    }

    get userPic(){
        if (this._userPic!==``)
            return this._userPic;

        return this.__defaultAvatarPath;
    }

    get firstName(){
        return this._firstName;
    }

    get lastName(){
        return this._lastName;
    }

    get fullName(){
        return this._firstName +` `+ this._lastName;
    }

    get sex(){
        return this._sex;
    }

    toJSON(){
        return {
            id: this.id,
            lastActivity: this.lastActivity ? (this.lastActivity.valueOf() / 1000) : this.lastActivity,
            userPic: this._userPic,
            firstName: this.firstName,
            lastName: this.lastName,
            isOnline: this.isOnline,
            isAdmin: this.isAdmin,
            sex : this.sex
        }
    }
}

export { PliziAttendee as default}
