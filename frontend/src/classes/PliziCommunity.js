import PliziMember from './PliziMember.js';
import PliziCommunityAvatar from './Community/PliziCommunityAvatar.js';
import PliziCommunityHeaderImage from './Community/PliziCommunityHeaderImage.js';
import PliziLocation from './User/PliziLocation.js';

class PliziCommunity {
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = '/images/plizi-noPrimaryImage-256.png';

    /**
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * @type {string}
     * @private
     */
    _name = null;

    /**
     * описание сообщества
     * @type {string}
     * @private
     */
    _description = null;

    /**
     * это кратенькая подсказка под название сообщества в списке сообществ
     * @type {string}
     * @private
     */
    _notice = null;

    /**
     * @type {PliziLocation|null}
     * @private
     */
    _location = null;

    /**
     * @type {string}
     * @private
     */
    _primaryImage = null;

    /**
     * это кусок URL'а внутри Plizi
     * если тут значение "supergroup" то адрес сообщества будет https://plizi.com/supergroup
     * @type {string}
     * @private
     */
    _url = null;

    /**
     * адрес сайта - https://supergroup.com/ или другая фигня
     * @type {string}
     * @private
     */
    _website = null;

    /**
     * кол-во участников сообщества
     * @type {number}
     * @private
     */
    _totalMembers = null;

    /**
     * кол-во видео сообщества
     * @type {number}
     * @private
     */
    _totalVideos = null;

    /**
     * кол-во друзей в сообществе
     * @type {number}
     * @private
     */
    _totalFriends = null;

    /**
     * роль текущего (залогиненного юзера в этом сообществе)
     * user - если просто подписчик, author - если он создал сообщество, иначе null
     * @type {null}
     * @private
     */
    _role = null;

    /**
     * небольшой список участников
     * @type {PliziMember[]}
     * @private
     */
    _members = null;

    /**
     * админы сообщества
     * @type {PliziMember[]}
     * @private
     */
    _admins = null;

    /**
     * небольшой список друзей в сообществе
     * @type {PliziMember[]}
     * @private
     */
    _friends = null;

    /**
     * Аватарка
     * @type {PliziCommunityAvatar|null}
     * @private
     */
    _avatar = null;

    /**
     * Картинка для хеадера
     * @type {PliziCommunityHeaderImage|null}
     * @private
     */
    _headerImage = null;

    /**
     * @type {number|null}
     * @private
     */
    _privacy = null;

    /**
     * @type {number|null}
     * @private
     */
    _type = null;

    /**
     * @type {number|null}
     * @private
     */
    _themeId = null;

    /**
     * @type {number}
     * @private
     */
    _requestsCount = 0;

    /**
     * @type {boolean}
     * @private
     */
    _subscribed = false;

    constructor(inputData){
        this._id = inputData.id;
        this._name = inputData.name;
        this._description = inputData.description;
        this._location = inputData.location ? new PliziLocation(inputData.location) : null;
        this._notice = inputData.notice;
        this._primaryImage = inputData.primaryImage;
        this._url = inputData.url;
        this._website = inputData.website;
        this._privacy = inputData.privacy;
        this._type = inputData.type;
        this._themeId = inputData.themeId;

        this._avatar = inputData.avatar ? new PliziCommunityAvatar(inputData.avatar) : null;
        this._headerImage = inputData.headerImage ? new PliziCommunityHeaderImage(inputData.headerImage) : null;

        this._role = inputData.role;
        this._totalMembers = inputData.totalMembers;
        this._totalVideos = inputData.totalVideos;
        this._requestsCount = inputData.requestsCount;
        this._subscribed = inputData.subscribed;

        if (inputData.friends) {
            this._friends = [];

            this._totalFriends = inputData.friends.total;
            inputData.friends.list.map((mItem) => {
                this._friends.push(new PliziMember(mItem));
            })
        }

        if (inputData.members) {
            this._members = [];

            inputData.members.list.map( (mItem) => {
                this._members.push(new PliziMember(mItem));
            });
        }

        if (inputData.admins) {
            this._admins = [];

            inputData.admins.list.map( (mItem) => {
                this._admins.push(new PliziMember(mItem));
            });
        }
    }

    get id(){
        return this._id;
    }

    get name(){
        return this._name;
    }

    get description(){
        return this._description;
    }

    get location(){
        return this._location;
    }

    get notice(){
        return this._notice;
    }
    get noticeShort(){
        if (!this._notice)
            return '';

        if (this._notice.length <= 16) {
            return this._notice;
        }

        return this._notice.substr(0, 16).trim()+ '...';
    }

    get primaryImage(){
        if (this._primaryImage)
            return this._primaryImage;

        if (this._avatar  &&  this._avatar.image  &&  this._avatar.image.thumb  &&  this._avatar.image.thumb.path) {
            return this._avatar.image.thumb.path;
        }

        return this.__defaultAvatarPath;
    }

    get defaultAvatarPath() {
        return this.__defaultAvatarPath;
    }

    get url(){
        return this._url;
    }

    get website(){
        return this._website;
    }

    get totalMembers(){
        return this._totalMembers;
    }

    get totalVideos(){
        return this._totalVideos;
    }

    get role(){
        return this._role;
    }

    set role(value){
        this._role = value;
    }

    get admins(){
        return this._admins;
    }

    get adminsIds(){
        if (this._admins) {
            return this._admins.map( aItem=> aItem.id );
        }

        return [];
    }

    get members(){
        return this._members;
    }

    get totalFriends(){
        return this._totalFriends;
    }

    get friends(){
        return this._friends;
    }

    /**
     * @returns {PliziCommunityAvatar|null}
     */
    get avatar() {
        return this._avatar;
    }

    set avatar(value) {
        this._avatar = value;
    }

    /**
     * @returns {PliziCommunityHeaderImage|null}
     */
    get headerImage() {
        return this._headerImage;
    }

    /**
     *
     * @param {PliziCommunityHeaderImage} image
     */
    set headerImage(image) {
        this._headerImage = image;
    }

    get privacy() {
        return this._privacy;
    }
    set privacy(value) {
        this._privacy = value;
    }

    get type() {
        return this._type;
    }
    set type(value) {
        this._type = value;
    }

    get themeId() {
        return this._themeId;
    }
    set themeId(value) {
        this._themeId = value;
    }

    get requestsCount() {
        return this._requestsCount;
    }

    get subscribed() {
        return this._subscribed;
    }

    set subscribed(value) {
        this._subscribed = value;
    }

    toJSON(){
        let mmbrs = null;
        let friends = null;
        let admins = null;

        if (this.members) {
            mmbrs = {
                list: this.members.map( mItem => mItem.toJSON() )
            };
        }

        if (this.admins) {
            admins = {
                list: this.admins.map( mItem => mItem.toJSON() )
            };
        }

        if (this.friends) {
            friends = {
                total: this.totalFriends,
                list: this.friends.map(mItem => mItem.toJSON()),
            };
        }

        return {
            id: this.id,
            name: this.name,
            notice: this.notice,
            primaryImage: this._primaryImage,
            url: this.url,
            website: this.website,
            location: (this._location) ? this.location.toJSON() : null,
            role: this.role,
            privacy: this.privacy,
            type: this.type,
            themeId: this.themeId,
            totalMembers: this.totalMembers,
            requestsCount: this.requestsCount,
            avatar: this._avatar ? this._avatar.toJSON() : null,
            headerImage: this._headerImage ? this._headerImage.toJSON() : null,
            members: mmbrs,
            admins: admins,
            friends: friends,
            subscribed: this.subscribed,
        };
    }
}

export { PliziCommunity as default}
