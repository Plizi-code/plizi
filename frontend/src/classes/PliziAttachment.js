import PliziAttachmentThumbsObj from './Attachment/PliziAttachmentThumbsObj.js';
import PliziAttachmentThumb from './Attachment/PliziAttachmentThumb.js';
import PliziUser from "./PliziUser";

class PliziAttachment{
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = '/images/plizi-noPrimaryImage-256.png';

    /**
     * ID аттачмента
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * оригинальное имя файла
     * @type {string}
     * @private
     */
    _originalName = null;

    /**
     * путь на S3
     * @type {string}
     * @private
     */
    _url = null;

    /**
     * MIME-TYPE
     * @type {string}
     * @private
     */
    _mimeType = null;

    /**
     * размер в байтах
     * @type {number}
     * @private
     */
    _size = null;

    /**
     * миниатюры, если аттачмент картинка
     * @type {PliziAttachmentThumbsObj|null}
     * @private
     */
    _image = null;

    /**
     * Количество лайков.
     *
     * @type {number}
     * @private
     */
    _likes = null;

    /**
     * Лайкнул ли атачмент текущий пользователь.
     *
     * @type {boolean}
     * @private
     */
    _alreadyLiked = false;

    /**
     * Пользователи лайкнувшие атачмент.
     *
     * @type {Array}
     * @private
     */
    _usersLikes = [];

    constructor( inputData ){
        this._id = inputData.id;
        this._originalName = inputData.originalName;
        this._url = inputData.url;
        this._mimeType = inputData.mimeType;
        this._size = inputData.size;
        this._image = (inputData.image) ? new PliziAttachmentThumbsObj(inputData.image) : null;
        this._likes = inputData.likes;
        this._alreadyLiked = inputData.alreadyLiked;
        this._usersLikes = inputData._usersLikes ? inputData._usersLikes.list.map((user) => {
            return new PliziUser(user);
        }) : [];
    }

    toJSON(){
        return {
            id: this._id,
            originalName: this._originalName,
            url: this._url,
            mimeType: this._mimeType,
            size: this._size,
            image : (this._image) ? this._image.toJSON() : null
        }
    }

    get id(){
        return this._id;
    }

    get originalName(){
        return this._originalName;
    }

    get url(){
        return this._url;
    }

    get mimeType(){
        return this._mimeType;
    }

    get size(){
        return this._size;
    }

    get image(){
        return this._image;
    }

    get isImage(){
        return !!this._image;
    }

    get isArchive() {
        const ext = this.originalName.split('.').pop().toLowerCase();

        return (`zip` ===  ext  || `rar` === ext);
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get original(){
        return (this._image) ? this.image.original : null;
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get normal(){
        return (this._image) ? this.image.normal : null;
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get medium(){
        return (this._image) ? this.image.medium : null;
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get thumb(){
        return (this._image) ? this.image.thumb : null;
    }

    get likes() {
        return this._likes;
    }

    get alreadyLiked() {
        return this._alreadyLiked;
    }

    get usersLikes() {
        return this._usersLikes;
    }

    set likes(value) {
        this._likes = value;
    }

    set alreadyLiked(value) {
        this._alreadyLiked = value;
    }

    set usersLikes(value) {
        this._usersLikes = value;
    }
}

export { PliziAttachment as default}
