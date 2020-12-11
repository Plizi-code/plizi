export default class PliziAttachmentItem {
    /**
     * @type {boolean}
     * @private
     */
    _isBlob = false;

    /**
     * @type {string|null}
     * @private
     */
    _fileBlob = null;

    /**
     * @type {boolean}
     * @private
     */
    _isImage = false;

    /**
     * @type {PliziAttachment}
     * @private
     */
    _attachment = null;

    /**
     * @type {string}
     * @private
     */
    _originalName = '';

    /**
     * @param isBlob {boolean}
     * @param isImage {boolean}
     * @param originalName {string}
     */
    constructor(isBlob, isImage, originalName) {
        this._isBlob = isBlob;
        this._isImage = isImage;
        this._originalName = originalName;

    }

    set attachment(attachment) {
        this._attachment = attachment;
    }

    set isBlob(loading) {
        this._isBlob = loading;
    }

    get isBlob() {
        return this._isBlob;
    }

    get attachment() {
        return this._attachment;
    }

    get isImage() {
        return this._isImage;
    }

    set isImage(isImage) {
        this._isImage = isImage;
    }

    get fileBlob() {
        return this._fileBlob;
    }

    set fileBlob(value) {
        this._fileBlob = value;
    }

    get originalName() {
        return this._originalName;
    }

    set originalName(value) {
        this._originalName = value;
    }

    get isArchive() {
        const ext = this.originalName.split('.').pop().toLowerCase();

        return (`zip` ===  ext  || `rar` === ext);
    }
}
