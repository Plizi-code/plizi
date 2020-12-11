class PliziLocation {

    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {{ru: string, en: string, ua: string}}
     * @private
     */
    _title = {
        ru : '',
        ua : '',
        en : '',
    }

    constructor(inputData){
        this._id = inputData.id;
        this._title = inputData.title;
    }

    get id(){
        return this._id;
    }

    get title(){
        return this._title;
    }

    toJSON(){
        return {
            id: this.id,
            title: this.title
        }
    }
}

export {PliziLocation as default}
