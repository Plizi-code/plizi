import PliziUzer from './PliziUser.js';

class PliziMember extends PliziUzer{
    /**
     * роль в этом сообществе (родитель этой сущности)
     * user - если просто подписчик, author - если он создал сообщество, иначе null
     * @type {null}
     * @private
     */
    _role = null;

    /**
     * @param {Object} usrData
     */
    constructor(usrData){
        super(usrData);
        this.role = usrData.role;
    }

    get role(){
        return this._role;
    }

    set role(value) {
        this._role = value;
    }

    get birthDate(){
        return (this.profile) ? this.profile.birthday : null;
    }

    toJSON(){
        return {
            id: this.id,
            isOnline: this.isOnline,
            role : this.role,
            profile: (this.profile) ? this.profile.toJSON() : null,
        };
    }
}

export { PliziMember as default}
