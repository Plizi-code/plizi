import PliziAvatarImage from './PliziAvatarImage.js';

class PliziAvatar {

    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {string}
     * @private
     */
    _originalName = null;

    /**
     * @type {string}
     * @private
     */
    _url = null;

    /**
     * @type {string}
     * @private
     */
    _path = null;

    /**
     * @type {string}
     * @private
     */
    _mimeType = null;

    /**
     * @type {PliziAvatarImage}
     * @private
     */
    _image = null;

    /**
     * @type {number}
     * @private
     */
    _size = null;

    /**
     * @returns {number}
     */
    get id() {
        return this._id;
    }

    /**
     * @returns {string}
     */
    get originalName() {
        return this._originalName;
    }

    /**
     * @returns {string}
     */
    get url() {
        return this._url;
    }

    /**
     * @returns {string}
     */
    get path() {
        return this._path;
    }

    /**
     * @returns {string}
     */
    get mimeType() {
        return this._mimeType;
    }

    /**
     * @returns {number}
     */
    get size() {
        return this._size;
    }

    /**
     * @returns {PliziAvatarImage|null}
     */
    get image() {
        return this._image;
    }

    constructor(inputData) {
        this._id = inputData.id;
        this._originalName = inputData.originalName;
        this._url = inputData.url;
        this._path = inputData.path;
        this._mimeType = inputData.mimeType;
        this._size = inputData.size;
        this._image = inputData.image ? new PliziAvatarImage(inputData.image) : null;
    }

    toJSON() {
        return {
            id: this.id,
            originalName: this.originalName,
            url: this.url,
            path: this.path,
            mimeType: this.mimeType,
            size: this.size,
            image: this.image ? this.image.toJSON() : null
        }
    }
}

export {PliziAvatar as default}
