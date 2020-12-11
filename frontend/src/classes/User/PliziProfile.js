import PliziLocation from './PliziLocation.js';
import PliziAvatar from './PliziAvatar.js';

class PliziUser {
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = `/images/noavatar-256.png`;

    /**
     *
     * @type {string}
     * @private
     */
    _firstName = ``;

    /**
     *
     * @type {string}
     * @private
     */
    _lastName = ``;

    /**
     *
     * @type {string}
     * @private
     */
    _sex = `n`;

    /**
     *
     * @type {Date}
     * @private
     */
    _birthday = null;

    /**
     * @type {PliziLocation}
     * @private
     */
    _location = null;

    /**
     *
     * @type {number|null}
     * @private
     */
    _relationshipId = null;

    /**
     * @type {PliziUser|null}
     * @private
     */
    _relationshipUser = null;

    /**
     *
     * @type {string}
     * @private
     */
    _userPic = ``;

    /**
     *
     * @type {PliziAvatar}
     * @private
     */
    _avatar = null;

    // значения как в PHP
    __RELATIONSHIP_MARRIED = 1;
    __RELATIONSHIP_NOT_MARRIED = 2;

    /**
     * @param {object} prof
     */
    constructor(prof) {
        this._firstName = prof.firstName ? (prof.firstName + ``).trim() : null;
        this._lastName = prof.lastName ? (prof.lastName + ``).trim() : null;
        this._sex = prof.sex ? (prof.sex + ``).trim() : null;
        this._birthday = prof.birthday;
        this._relationshipId = prof.relationshipId;
        this._relationshipUser = prof.relationshipUser ? prof.relationshipUser : null;
        this._location = (prof.location) ? new PliziLocation(prof.location) : null;

        if (prof.userPic) {
            this._userPic = (prof.userPic + ``).trim();
        }

        this._avatar = prof.avatar ? new PliziAvatar(prof.avatar) : null;
    }

    /**
     * @returns {string}
     */
    get firstName(){
        return this._firstName.trim();
    }

    /**
     * @returns {string}
     */
    get lastName(){
        return this._lastName.trim();
    }

    /**
     * @returns {string}
     */
    get fullName(){
        return `${this._firstName.trim()} ${this._lastName.trim()}`;
    }

    /**
     * Возвращает пол в "человеческом" виде.
     * @returns {string}
     */
    get sexShow(){
        switch (this._sex) {
            case `m`: return 'мужской';
            case `f`: return 'женский';
            default: return 'не указано';
        }
    }

    /**
     * @returns {string}
     */
    get sex() {
        return this._sex.trim();
    }

    /**
     * @returns {Date}
     */
    get birthday(){
        return !!this._birthday ? new Date(this._birthday) : null;
    }

    /**
     * @returns {PliziLocation|null}
     */
    get location(){
        return this._location;
    }

    /**
     * @returns {number|null}
     */
    get relationshipId(){
        return this._relationshipId;
    }

    /**
     * @returns {PliziUser|null}
     */
    get relationshipUser(){
        return this._relationshipUser;
    }

    get relationshipUserText() {
        if (this._relationshipId === 1) {
            switch (this._sex) {
                case 'm': return `на`;
                case 'f': return `за`;
                case 'n': return `с`;
            }
        }

        if (this._relationshipId === 4 || this._relationshipId === 5) {
            return 'с';
        }
    }

    /**
     * возвращает имя/фамилию партнёра (жены/мужа)
     * @returns {string}
     */
    get partnerFullname() {
        return this.relationshipUser.profile.firstName +' '+ this.relationshipUser.profile.lastName;
    }

    get family(){
        if (this.__RELATIONSHIP_MARRIED === this._relationshipId) {
            switch (this._sex) {
                case 'm': return `Женат`;
                case 'f': return `Замужем`;
                case 'n': return `В браке`;
            }
        }

        if (this.__RELATIONSHIP_NOT_MARRIED === this._relationshipId) {
            switch (this._sex) {
                case 'm': return `Не женат`;
                case 'f': return `Не замужем`;
                case 'n': return `Не в браке`;
            }
        }

        if (this.relationshipId === 3)
            return `В активном поиске`;

        if  (this.relationshipId === 4)
            return 'Встречаюсь';

        if  (this.relationshipId === 5)
            return 'В отношениях';

        return `Не указано`;
    }

    /**
     * @returns {string}
     */
    get userPic(){
        if (this._userPic!==``)
            return this._userPic;

        return this.__defaultAvatarPath;
    }

    /**
     * устанавливает юзерский аватар
     * @param {string} picPath
     */
    set userPic(picPath) {
        this._userPic = picPath;
    }

    /**
     * @returns {PliziAvatar|null}
     */
    get avatar() {
        return this._avatar;
    }

    /**
     * @param {PliziAvatar} avatar
     */
    set avatar(avatar) {
        this._avatar = avatar;
    }

    /**
     * @returns {string}
     */
    toString(){
        return JSON.stringify( this.toJSON() );
    }

    /**
     * возвращает данные юзера в том виде как их возвращает API
     * @returns {Object}
     */
    toJSON() {
        return {
            firstName: this.firstName,
            lastName: this.lastName,
            sex: this.sex,
            birthday: this._birthday,
            location: (this._location) ? this.location.toJSON() : null,
            relationshipId: this.relationshipId,
            relationshipUser: !!this._relationshipUser ? this.relationshipUser : null,
            userPic: this._userPic, // реальный
            avatar: this._avatar ? this._avatar.toJSON() : null,
        };
    }

}

export {PliziUser as default}
