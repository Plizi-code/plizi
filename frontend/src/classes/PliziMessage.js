import PliziAttachment from './PliziAttachment.js';
import { convertToDate } from '../utils/DateUtils.js';

class PliziMessage{
    /**
     * путь к дефолтной аватарке, которую показываем если нет своей
     * @type {string}
     * @private
     */
    __defaultAvatarPath = `/images/noavatar-256.png`;

    /**
     * ID сообщения
     * @type {number}
     * @private
     */
    _id = null;

    /**
     * ID юзера автора
     * @type {number}
     * @private
     */
    _userId = null;

    /**
     * имя отправителя сообщения
     * @type {string}
     * @private
     */
    _firstName = null;

    /**
     * фамилия отправителя сообщения
     * @type {string}
     * @private
     */
    _lastName = null;

    /**
     * аватарка отправителя сообщения
     * @type {string}
     * @private
     */
    _userPic = null;

    /**
     * пол отправителя сообщения
     * @type {string}
     * @private
     */
    _sex = null;

    /**
     * текст сообщения
     * @type {string}
     * @private
     */
    _body = null;

    /**
     * флаг: моё || не моё сообщение
     * @type {boolean}
     * @private
     */
    _isMine = null;

    /**
     * флаг прочитано ли сообщение
     * @type {boolean}
     * @private
     */
    _isRead = null;

    /**
     * флаг, было ли сообщение отредактировано
     * @type {boolean}
     * @private
     */
    _isEdited = null;

    /**
     * метка времени, когда было создано сообщение
     * @type {Date|number}
     * @private
     */
    _createdAt = null;

    /**
     * метка времени, когда сообщение было изменено
     * @type {Date|number}
     * @private
     */
    _updatedAt = null;

    /**
     * ссылка на сообщение, которое было процитировано
     * @type {PliziMessage}
     * @private
     */
    _replyOn = null;

    /**
     * флаг, что сообщение было переотправлено (форвардинг)
     * @type {boolean}
     * @private
     */
    _isForward = null;

    /**
     * объект с аттачментами
     * @type {object}
     * @private
     */
    _attachments = null;

    /**
     * ID чата-исходника (только для ReplyOn, тут потому что Babel падает из-за циклической связи
     * @type {number}
     * @private
     */
    _chatId = null;

    constructor(msgData){
        this._id = msgData.id;
        this._userId = msgData.userId;
        this._chatId = msgData.chatId;
        this._firstName = msgData.firstName;
        this._lastName = msgData.lastName;
        this._userPic = msgData.userPic;
        this._sex  = msgData.sex;
        this._body = msgData.body;
        this._isMine = !! msgData.isMine;
        this._isRead = !! msgData.isRead;
        this._isEdited = !! msgData.isEdited;
        this._createdAt = convertToDate(msgData.createdAt);
        this._updatedAt = convertToDate(msgData.updatedAt);
        this._replyOn = (msgData.replyOn) ? new PliziMessage(msgData.replyOn): null;
        this._isForward = !! msgData.isForward;

        this._attachments = [];

        if (msgData.attachments &&  msgData.attachments.list /*  &&  msgData.attachments.list>0*/) {
            msgData.attachments.list.map((aItem) => {
                this._attachments.push( new PliziAttachment(aItem) );
            });
        }
    }

    get id(){
        return this._id;
    }

    get userId(){
        return this._userId;
    }

    /**
     * ID чата-исходника
     * только для ReplyOn
     * @returns {number}
     */
    get chatId(){
        return this._chatId;
    }

    get firstName(){
        return this._firstName;
    }

    get lastName(){
        return this._lastName;
    }

    get fullName(){
        return this._firstName +' '+ this._lastName;
    }

    get userPic(){
        if (this._userPic!==``)
            return this._userPic;

        return this.__defaultAvatarPath;
    }

    get sex(){
        return this._sex;
    }

    get body(){
        return this._body;
    }

    get isMine(){
        return this._isMine;
    }

    get isRead(){
        return this._isRead;
    }

    get isEdited(){
        return this._isEdited;
    }

    get createdAt(){
        return this._createdAt;
    }

    /**
     * время сообщения в формате UnixTime
     * @returns {number}
     */
    get messageUnix(){
        return this._createdAt.valueOf();
    }

    get updatedAt(){
        return this._updatedAt;
    }

    /**
     * @returns {PliziMessage|null}
     */
    get replyOn(){
        return this._replyOn;
    }

    get isReply(){
        return !!this._replyOn;
    }

    get isForward(){
        return this._isForward;
    }

    get attachments(){
        return this._attachments;
    }

    get isAttachments(){
        return !!this._attachments  &&  this._attachments.length && this._attachments.length>0;
    }

    get attachmentsNumber(){
        return (!!this._attachments) ? this._attachments.length : 0;
    }

    toJSON(){
        let atts = [];
        this._attachments.map( aItem => {
            atts.push( aItem.toJSON() )
        });

        return {
            id: this.id,
            userId: this.userId,
            chatId: this.chatId,
            firstName: this.firstName,
            lastName: this.lastName,
            userPic: this.userPic,
            sex: this.sex,
            body: this.body,
            isMine: this.isMine,
            isRead: this.isRead,
            isEdited: this.isEdited,
            // делим на 1000 потому, что тут JS считает в миллисекундах
            createdAt: +(+this.createdAt.valueOf() / 1000).toFixed(0),
            updatedAt: +(+this.updatedAt.valueOf() / 1000).toFixed(0),
            attachments: { list : atts },
            replyOn: this.isReply ? this.replyOn.toJSON() : null,
            isForward: this.isForward
        };
    }
}

export { PliziMessage as default}
