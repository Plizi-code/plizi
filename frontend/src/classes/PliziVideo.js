import PliziUser from './PliziUser.js';
import PliziPost from './PliziPost.js';

class PliziVideo {
    /**
     * @type {number|null}
     * @private
     */
    _id = null;

    /**
     * @type {string|null}
     * @private
     */
    _link = null;

    /**
     * @type {PliziUser}
     * @private
     */
    _user = null;

    /**
     * @type {PliziPost}
     * @private
     */
    _post = null;

    /**
     * @type {Date}
     * @private
     */
    _createdAt = null;

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get link() {
        return this._link;
    }

    set link(value) {
        this._link = value;
    }

    get user() {
        return this._user;
    }

    set user(value) {
        this._user = value;
    }

    get post() {
        return this._post;
    }

    set post(value) {
        this._post = value;
    }

    get createdAt() {
        return this._createdAt;
    }

    set createdAt(value) {
        this._createdAt = value;
    }

    constructor(video) {
        this.id = video.id;
        this.link = video.link;
        this.user = video.user ? new PliziUser(video) : null;
        this.post = video.post ? new PliziPost(video.post) : null;
        this.createdAt = video.createdAt;
    }

    get isYoutubeLink() {
        let youtubeLinksRegExp = /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig;

        return !!this.link.match(youtubeLinksRegExp);
    }

    get youtubeId() {
        let youtubeIdRegExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        let youtubeIdsMatch = this.link.match(youtubeIdRegExp);

        return (youtubeIdsMatch && youtubeIdsMatch[7].length === 11) ? youtubeIdsMatch[7] : false;
    }
}

export default PliziVideo;
