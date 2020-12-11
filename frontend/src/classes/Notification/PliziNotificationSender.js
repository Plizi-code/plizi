class PliziNotificationSender {

    /**
     * @type {string}
     * @private
     */
    __defaultAvatarPath = `/images/noavatar-256.png`;

    /**
     * @type {number|null}
     * @private
     */
    _id = null;

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
    _userPic = ``;

    /**
     * @type {string}
     * @private
     */
    _sex = 'n';


    /**
     * @param {object} senderData
     */
    constructor(senderData){
        this._id = senderData.id || null;
        this._firstName = senderData.firstName || '';
        this._lastName = senderData.lastName || '';
        this._sex = senderData.sex || 'n';
        this._userPic = senderData.userPic || '';
    }


    get id() {
        return this._id;
    }

    get firstName(){
        return this._firstName;
    }

    get lastName(){
        return this._lastName;
    }

    get fullName(){
        return this._firstName +' '+ this._lastName;
    }

    get sex(){
        return this._sex;
    }

    get userPic(){
        if (this._userPic!==``)
            return this._userPic;

        return this.__defaultAvatarPath;
    }


    toString(){
        return JSON.stringify( this.toJSON() )
    }


    toJSON() {
        return {
            id: this._id,
            firstName: this._firstName,
            lastName: this._lastName,
            sex: this._sex,
            userPic: this._userPic
        };
    }


}

export { PliziNotificationSender as default}
