import PliziAttachmentThumb from './PliziAttachmentThumb.js';

class PliziAttachmentThumbsObj {

    /**
     * оригинальная картинка
     * @type {PliziAttachmentThumb}
     * @private
     */
    _original = null;

    /**
     * нормальный размер
     * @type {PliziAttachmentThumb}
     * @private
     */
    _normal = null;

    /**
     * средний размер
     * @type {PliziAttachmentThumb}
     * @private
     */
    _medium = null;

    /**
     * иконка
     * @type {PliziAttachmentThumb}
     * @private
     */
    _thumb = null;


    constructor( inputData ){
        this._original = inputData.original ? new PliziAttachmentThumb(inputData.original) : null;
        this._normal = inputData.normal ? new PliziAttachmentThumb(inputData.normal) : null;
        this._medium = inputData.medium ? new PliziAttachmentThumb(inputData.medium) : null;
        this._thumb = inputData.thumb ? new PliziAttachmentThumb(inputData.thumb) : null;
    }

    toJSON(){
        return {
            original: this._original ? this._original.toJSON() : null,
            normal: this._normal ? this._normal.toJSON() : null,
            medium: this._medium ? this._medium.toJSON() : null,
            thumb: this._thumb ? this._thumb.toJSON() : null,
        }
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get original(){
        return this._original;
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get normal(){
        return this._normal;
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get medium(){
        return this._medium;
    }

    /**
     * @returns {PliziAttachmentThumb}
     */
    get thumb(){
        return this._thumb;
    }
}

export { PliziAttachmentThumbsObj as default}
