import PliziUzer from './PliziUser.js';

import PliziUserStats from './User/PliziUserStats.js';
import PliziUserPrivacySettings from './User/PliziUserPrivacySettings.js';

class PliziAuthUser extends PliziUzer{
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
     * @type {string}
     * @private
     */
    _email = ``;

    constructor(inputData){
        super(inputData);
    }

    /**
     * загружаем тут данные которые пришли от метода api/user или из localStorage
     * @param {Object} inputData
     */
    updateAuthUser(inputData){
        this.updateUserData(inputData);

        this._email = (inputData.email) ? (inputData.email+``).trim() : this.email;
        this._stats = inputData.stats ? new PliziUserStats(inputData.stats) : this.stats;
        this._privacySettings = inputData.privacySettings ? new PliziUserPrivacySettings(inputData.privacySettings) :
          this.privacySettings;
    }

    /**
     * @returns {string}
     */
    get email(){
        return this._email;
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

    toString(){
        return JSON.stringify( this.toJSON() );
    }

    toJSON() {
        let res = super.toJSON();
        delete res.mutualFriendsCount;
        res.email = this._email;
        res.privacySettings = this.privacySettings.toJSON();
        res.stats = this.stats.toJSON();

        return res;
    }
}

export { PliziAuthUser as default}
