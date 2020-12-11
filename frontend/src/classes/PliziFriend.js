import { convertToDate } from '../utils/DateUtils.js';

import PliziUzer from './PliziUser.js';

class PliziFriend extends PliziUzer{

    /**
     * метка времени, когда началась дружба с этим юзером
     * @type {Date}
     * @private
     */
    _friendshipSinceTime = null;

    constructor(inputData){
        super(inputData);

        this._friendshipSinceTime = inputData.friendshipSinceTime ? convertToDate(inputData.friendshipSinceTime) : this.friendshipSinceTime;
    }

    /**
     * метка времени, когда началась дружба с этим юзером
     * @returns {Date}
     */
    get friendshipSinceTime(){
        return this._friendshipSinceTime;
    }

    /**
     * время жизни дружбы в секундах
     * @returns {number}
     */
    get friendshipLivingTime(){
        return (((new Date()).valueOf() - this.friendshipSinceTime.valueOf()) / 1000) >>> 0;
    }

    toJSON() {
        let res = super.toJSON();
        res.friendshipSinceTime = (this.friendshipSinceTime) ? this.friendshipSinceTime.valueOf() / 1000 : this.friendshipSinceTime;

        return res;
    }
}

export { PliziFriend as default}
