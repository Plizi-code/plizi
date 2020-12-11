class PliziLastEntry {

    /**
     * @type {Number|null}
     * @private
     */
    _id = null;

    /**
     * @type {String|null}
     * @private
     */
    _email = null;

    /**
     * @type {String|null}
     * @private
     */
    _firstName = null;

    /**
     * @type {String|null}
     * @private
     */
    _lastName = null;

    /**
     * @type {String|null}
     * @private
     */
    _userPic = null;

    /**
     * @type {Date|null}
     * @private
     */
    _lastLoginAt = null;


    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get email() {
        return this._email;
    }

    set email(value) {
        this._email = value;
    }

    get firstName() {
        return this._firstName;
    }

    set firstName(value) {
        this._firstName = value;
    }

    get lastName() {
        return this._lastName;
    }

    set lastName(value) {
        this._lastName = value;
    }

    get userPic() {
        return this._userPic;
    }

    set userPic(value) {
        this._userPic = value;
    }

    get lastLoginAt() {
        return this._lastLoginAt;
    }

    get lastLoginUnix() {
        return this._lastLoginAt.valueOf();
    }

    set lastLoginAt(value) {
        this._lastLoginAt = value;
    }

    constructor(user) {
        this._id = user.id;
        this._email = user.email;
        this._firstName = user.firstName;
        this._lastName = user.lastName;
        this._userPic = user.userPic;
        this._lastLoginAt = user.lastLoginAt;
    }

    toJSON() {
        return {
            id: this.id,
            email: this.email,
            firstName: this.firstName,
            lastName: this.lastName,
            userPic: this.userPic,
            lastLoginAt: this.lastLoginAt,
        };
    }

}

export default PliziLastEntry;
