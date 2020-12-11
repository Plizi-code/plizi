class PliziNotificationCommunity {

    /**
     * @type {string}
     * @private
     */
    __defaultAvatarPath = `/images/plizi-noPrimaryImage-256.png`;

    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {string}
     * @private
     */
    _postName = '';

    /**
     * @type {number}
     * @private
     */
    _postId = null;

    /**
     * @type {string}
     * @private
     */
    _name = ``;

    /**
     * @type {string}
     * @private
     */
    _primaryImage = ``;


    /**
     * @param {object} inputData
     */
    constructor(inputData){
        this._id = inputData.id;
        this._postId = inputData.postId;
        this._postName = inputData.postName;
        this._name = inputData.name;
        this._primaryImage = inputData.primaryImage || '';
    }


    get id() {
        return this._id;
    }

    get postId() {
        return this._postId;
    }

    get postName() {
        return this._postName;
    }

    get name(){
        return this._name;
    }

    get primaryImage(){
        if (this._primaryImage)
            return this._primaryImage;

        return this.__defaultAvatarPath;
    }

    toString(){
        return JSON.stringify( this.toJSON() )
    }

    toJSON() {
        return {
            id: this.id,
            postId: this.postId,
            postName: this.postName,
            name: this.name,
            primaryImage: this._primaryImage
        };
    }


}

export { PliziNotificationCommunity as default}
