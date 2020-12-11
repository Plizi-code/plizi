import PliziUser from '../PliziUser.js';

class PliziCommunityRequest {

    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {number}
     * @private
     */
    _created_at = null;

    /**
     * @type {string}
     * @private
     */
    _description = null;

    /**
     * @type {PliziUser}
     * @private
     */
    _user = null;

    /**
     * @returns {number}
     */
    get id() {
        return this._id;
    }

    /**
     * @returns {number}
     */
    get createdAt() {
        return this._created_at;
    }

    /**
     * @returns {string}
     */
    get description() {
        return this._description;
    }

    /**
     * @returns {PliziUser|null}
     */
    get user() {
        return this._user;
    }

    constructor(inputData) {
        this._id = inputData.id;
        this._created_at = inputData.created_at;
        this._description = inputData.description;
        this._user = inputData.user ? new PliziUser(inputData.user) : null;
    }

    toJSON() {
        return {
            id: this.id,
            created_at: this.createdAt,
            description: this.description,
            user: this.user ? this.user.toJSON() : null
        }
    }
}

export {PliziCommunityRequest as default}
