import PliziUser from './PliziUser.js';
import PliziAttachment from "./PliziAttachment.js";

class PliziComment {
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = '/images/plizi-noPrimaryImage-256.png';

    /**
     * @type {null}
     * @private
     */
    _id = null;

    /**
     * @type {null}
     * @private
     */
    _body = null;

    /**
     * кол-во лайков
     * @type {Number}
     * @private
     */
    _likes = 0;

    /**
     * Лайкнутый ли этот пост текущим пользователем.
     * @type {boolean}
     * @private
     */
    _alreadyLiked = false;

    /**
     * @type {Array}
     * @private
     */
    _usersLikes = [];

    /**
     * Автор комента.
     * @type {PliziUser|null}
     * @private
     */
    _author = null;

    /**
     * @type {Date}
     * @private
     */
    _createdAt = null;

    /**
     * объект с аттачментами
     * @type {object[]}
     * @private
     */
    _attachments = [];

    /**
     * Children comments
     * @type {PliziComment[]}
     * @private
     */
    _thread = [];

    get id() {
        return this._id;
    }

    get body() {
        return this._body;
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

    /**
     * @returns {PliziUser}
     */
    get author() {
        return this._author;
    }

    get createdAt() {
        return this._createdAt;
    }

    get attachments(){
        return this._attachments;
    }

    get thread() {
        return this._thread;
    }

    get imageList() {
        return this._attachments.filter(attachment => attachment.isImage);
    }

    get defaultAvatarPath() {
        return this.__defaultAvatarPath;
    }

    editComment(newComment) {
        return this._thread = this._thread.map(comment => comment.id === newComment.id ? comment.update(newComment) : comment);
    }

    removeComment(commentId) {
        return this._thread = this._thread.filter(comment => comment.id !== commentId);
    }

    set id(value) {
        this._id = value;
    }

    set body(value) {
        this._body = value;
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

    set author(value) {
        this._author = value;
    }

    set createdAt(value) {
        this._createdAt = new Date(value * 1000);
    }

    set attachments(value) {
        this._attachments = value;
    }

    set thread(value) {
        this._thread = value;
    }

    /**
     * @param {object} comment
     */
    constructor(comment) {
        this.update(comment);
    }

    /**
     * Обновление комментариев
     * @param {Object} comment
     */
    update(comment) {
        this.body = comment.body;
        this.id = comment.id;
        this.likes = comment.likes || 0;
        this.author = comment.author;
        this.alreadyLiked = comment.alreadyLiked || false;
        this.usersLikes = comment.usersLikes ? comment.usersLikes.list.map((user) => {
            return new PliziUser(user);
        }) : [];
        this.createdAt = comment.createdAt;
        this.attachments = [];

        if (comment.attachments && comment.attachments.list && comment.attachments.list.length > 0) {
            comment.attachments.list.map((aItem) => {
                this.attachments.push(new PliziAttachment(aItem));
            });
        }

        if (comment.thread && comment.thread.list && comment.thread.list.length > 0) {
            comment.thread.list.map((aItem) => {
                this.thread.push(new PliziComment(aItem));
            });
        }

        this.author = comment.author ? new PliziUser(comment.author) : null;

        return this;
    };
}

export { PliziComment as default }
