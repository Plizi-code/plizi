import { convertToDate } from '../utils/DateUtils.js';

import PliziProfile from './User/PliziProfile.js';
import PliziUserStats from "./User/PliziUserStats.js";
import PliziUserPrivacySettings from './User/PliziUserPrivacySettings.js';

class PliziUser {
    /**
     * @type {string}
     * @private
     */
    _id = ``;

    /**
     * @type {boolean}
     * @private
     */
    _isOnline = false;

    /**
     * @type {boolean}
     * @private
     */
    _isOwner = false;

    /**
     * метка времени когда у юзера была последняя активность
     * @type {Date}
     * @private
     */
    _lastActivity = null;

    /**
     * @type {PliziProfile}
     * @private
     */
    _profile = null;

    /**
     * кол-во общих друзей
     * @type {number|null}
     * @private
     */
    _mutualFriendsCount = null;

    /**
     * статистика
     * @type {PliziUserStats}
     * @private
     */
    _stats = null;

    /**
     * @type {PliziUserPrivacySettings}
     * @private
     */
    _privacySettings = null;

    /**
     * @param {object} usrData
     */
    constructor(usrData) {
        if (usrData && typeof usrData === 'object' && Object.keys(usrData).length > 0) {
            this.updateUserData(usrData);
        }
    }

    /**
     * @param {Object} inputData
     */
    updateUserData(inputData) {
        this._id = inputData.id ? inputData.id : this.id;
        this._isOwner = !!inputData.isOwner;
        this._isOnline = inputData.isOnline ? inputData.isOnline : this.isOnline;
        this._lastActivity = inputData.lastActivity ? convertToDate(inputData.lastActivity) : this.lastActivity;

        this._profile = inputData.profile ? new PliziProfile(inputData.profile) : this.profile;
        this._mutualFriendsCount = inputData.mutualFriendsCount ? inputData.mutualFriendsCount : this.mutualFriendsCount;

        this._stats = inputData.stats ? new PliziUserStats(inputData.stats) : this.stats;
        this._privacySettings = inputData.privacySettings ? new PliziUserPrivacySettings(inputData.privacySettings) :
            this.privacySettings;
    }

    /**
     *
     * @returns {string}
     */
    get id(){
        return this._id;
    }

    /**
     * @returns {boolean}
     */
    get isOnline(){
        return this._isOnline;
    }

    set isOnline(olStatus){
        this._isOnline = !!olStatus;
    }

    /**
     * @returns {boolean}
     */
    get isOwner(){
        return this._isOwner;
    }

    /**
     * @returns {Date}
     */
    get lastActivity(){
        return this._lastActivity;
    }

    get lastActivityUnixTime(){
        return (this._lastActivity.getTime() / 1000) >>> 0;
    }

    /**
     * @param {number|string|Date} lastActivityDT
     */
    set lastActivity(lastActivityDT){
        this._lastActivity = convertToDate(lastActivityDT);
    }

    /**
     * @returns {PliziProfile}
     */
    get profile(){
        return this._profile;
    }

    get mutualFriendsCount(){
        return this._mutualFriendsCount;
    }

    /**
     * @returns {string}
     */
    get firstName(){
        return this.profile.firstName;
    }

    /**
     * @returns {string}
     */
    get lastName(){
        return this.profile.lastName;
    }

    /**
     *
     * @returns {string}
     */
    get fullName(){
        return `${this.profile.firstName} ${this.profile.lastName}`;
    }

    /**
     * для вызова в функциях сравнения
     * @returns {string}
     */
    get compareName(){
        return `${this.profile.lastName.toLowerCase()}-${this.profile.firstName.toLowerCase()}`;
    }

    /**
     * @returns {string}
     */
    get sex() {
        return this.profile.sex;
    }

    /**
     * @returns {Date}
     */
    get birthday(){
        return this.profile.birthday;
    }

    /**
     * @returns {*}
     */
    get location(){
        return this.profile.location;
    }

    /**
     * location юзера в текстовом виде (если он есть)
     * @returns {string}
     */
    get locationText(){
        if (this.location && this.city.title && this.country.title)
            return this.city.title.ru +', '+  this.country.title.ru;

        return 'Не указано';
    }

    /**
     * @returns {Object|null}
     */
    get city(){
        const loc = this.profile.location;

        if (!loc)
            return null;

        return {
            id: loc.id,
            title: loc.title,
        };
    }

    /**
     * @returns {Object|null}
     */
    get region(){
        const loc = this.profile.location;

        if (!loc && !loc.region)
            return null;

        return {
            id: loc.region.id,
            title: loc.region.title,
        };
    }

    /**
     * @returns {Object|null}
     */
    get country(){
        const loc = this.profile.location;

        if (!loc)
            return null;

        return {
            id: loc.country.id,
            title: loc.country.title,
        };
    }

    /**
     * @returns {number}
     */
    get relationshipId(){
        return this.profile.relationshipId;
    }

    get family(){
        return this.profile.family;
    }

    /**
     * @returns {string}
     */
    get userPic(){
        return this.profile.userPic;
    }

    /**
     * устанавливает юзерский аватар
     * @param {string} picPath
     */
    set userPic(picPath) {
        this.profile.userPic = picPath;
    }

    /**
     * @returns {PliziAvatar|null}
     */
    get avatar() {
        return this.profile.avatar;
    }

    /**
     * @param {PliziAvatar} avatar
     */
    set avatar(avatar)
    {
        this.profile.avatar = avatar;
    }

    /**
     * @returns {string}
     */
    toString(){
        return JSON.stringify( this.toJSON() );
    }

    /**
     * ссылка на статистику
     * @returns {PliziUserStats}
     */
    get stats(){
        return this._stats;
    }

    /**
     * @returns {PliziUserPrivacySettings}
     */
    get privacySettings() {
        return this._privacySettings;
    }

    /**
     * возвращает данные юзера в том виде как их воазващает api/user/search
     * @returns {Object}
     */
    toJSON() {
        return {
            id: this.id,
            isOwner: this.isOwner,
            isOnline: this.isOnline,
            lastActivity: (this.lastActivity) ? this.lastActivity.valueOf() / 1000 : this.lastActivity,
            profile: (this.profile) ? this.profile.toJSON() : null,
            stats: (this.stats) ? this.stats.toJSON() : null,
            privacySettings: this.privacySettings ? this.privacySettings.toJSON() : null,
            mutualFriendsCount: this.mutualFriendsCount
        };
    }
}

export {PliziUser as default}
