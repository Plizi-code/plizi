import PliziUser from './PliziUser.js';
import PliziCommunity from './PliziCommunity.js';
import PliziAttachment from "./PliziAttachment.js";

class PliziPhotoAlbum {
    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {PliziUser}
     * @private
     */
    _author = null;

    /**
     * @type {string}
     * @private
     */
    _title = null;

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
     * @type {PliziCommunity}
     * @private
     */
    _community = null;

    /**
     * @type {Date}
     * @private
     */
    _createdAt = null;

    /**
     * @type {PliziAttachment[]}
     * @private
     */
    _images = null;

    /**
     * @type {PliziAttachment}
     * @private
     */
    _lastImage = null;

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get author() {
        return this._author;
    }

    set author(value) {
        this._author = value;
    }

    get title() {
        return this._title;
    }

    set title(value) {
        this._title = value;
    }

    get description() {
        return this._description;
    }

    set description(value) {
        this._description = value;
    }

    get user() {
        return this._user;
    }

    set user(value) {
        this._user = value;
    }

    get community() {
        return this._community;
    }

    set community(value) {
        this._community = value;
    }

    get createdAt() {
        return this._createdAt;
    }

    set createdAt(value) {
        this._createdAt = value;
    }

    get images() {
        return this._images;
    }

    set images(value) {
        this._images = value;
    }

    get lastImage() {
        return this._lastImage;
    }

    set lastImage(value) {
        this._lastImage = value;
    }

    constructor(photoAlbum) {
        this._id = photoAlbum.id;
        this._author = new PliziUser(photoAlbum.user);
        this._title = photoAlbum.title;
        this._description = photoAlbum.description;
        this._user = photoAlbum.user ? new PliziUser(photoAlbum.user) : null;
        this._community = photoAlbum.community ? new PliziCommunity(photoAlbum.community) : null;
        this._createdAt = photoAlbum.createdAt;

        if (photoAlbum.images) {
            this._images = [];

            photoAlbum.images.list.forEach((image) => {
                this._images.push(new PliziAttachment(image));
            })
        }

        this._lastImage = photoAlbum.lastImage ? new PliziAttachment(photoAlbum.lastImage) : null;
    }
}

export default PliziPhotoAlbum;
