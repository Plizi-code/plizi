/**
 * класс для упрощения работы со списком получателей
 */
class PliziRecipient {
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = `/images/noavatar-256.png`;

    /**
     * @type {number}
     * @private
     */
    _chatId = null;

    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {boolean}
     * @private
     */
    _isOnline = false;

    /**
     * метка времени когда у юзера была последняя актиновсть
     * @type {Date}
     * @private
     */
    _lastActivity = null;

    /**
     * @type {string}
     * @private
     */
    _firstName = ``;

    /**
     * @type {string}
     * @private
     */
    _lastName = ``;

    /**
     * @type {string}
     * @private
     */
    _sex = `n`;

    /**
     * аватарка
     * @type {string}
     * @private
     */
    _userPic = null;

    constructor( userData, chatId ){
        this._chatId = chatId || null;
        this._id = userData.id;
        this._isOnline = !!userData.isOnline;
        this._lastActivity = userData.lastActivity;
        this._firstName = userData.firstName;
        this._lastName = userData.lastName;
        this._sex = userData.sex;
        this._userPic = userData.userPic;
    }

    get id(){
        return this._id;
    }

    get chatId(){
        return this._chatId;
    }

    get isOnline(){
        return this._isOnline;
    }

    get lastActivity(){
        return this._lastActivity;
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
            chatId: this.chatId,
            lastActivity: (this.lastActivity.getTime() / 1000) >>> 0,
            userPic: this._userPic,
            firstName: this.firstName,
            lastName: this.lastName,
            isOnline: this.isOnline,
            sex : this.sex
        }
    }
}

export { PliziRecipient as default}
