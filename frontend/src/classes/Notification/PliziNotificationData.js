import PliziNotificationSender from './PliziNotificationSender.js';
import PliziNotificationCommunity from './PliziNotificationCommunity.js';

class PliziNotificationData {
    /**
     * текст нотификации (точнее шаблон для формирования сообщения)
     * @type {string}
     * @private
     */
    _body = ``;

    /**
     * @type {PliziNotificationSender}
     * @private
     */
    _sender = null;

    /**
     * @type {PliziNotificationCommunity}
     * @private
     */
    _community = null;

    /**
     * тип нотификации
     * @type {string}
     * @private
     */
    _notificationType = null;

    /**
     * @deprecated
     * @type {string[]}
     * @private
     */
    _types = [
        `friendships.sent`,
        `friendships.accepted`,
        `friendships.denied`,
        `user.post.created`,
        `user.profile.image.updated`,
        `community.post.created`
    ];

    constructor(notifData){
        this._body = notifData.body;
        this._sender = (notifData.sender) ? new PliziNotificationSender(notifData.sender) : notifData.sender;
        this._community = (notifData.community) ? new PliziNotificationCommunity(notifData.community): notifData.community;

        if (!notifData.sender &&  !notifData.community) {
            window.console.error(`Нет ни notifData.sender ни notifData.community`);
        }

        this._notificationType = notifData.notificationType;
    }

    isCommunity(){
        return !! this._community;
    }

    isHuman(){
        return !! this._sender;
    }

    isUser(){
        return this.isHuman();
    }

    get body() {
        return this._body;
    }

    get sender(){
        return this._sender;
    }

    get community(){
        return this._community;
    }

    get notificationType(){
        return this._notificationType;
    }

    get message(){
        if ('friendships.sent' === this.notificationType) {
            switch (this.sender.sex){
                case 'f': return `${this.sender.fullName} хочет с Вам подружиться`;
                case 'm': return `${this.sender.fullName} хочет с Вам подружиться`;
                case 'n': return `Пользователь ${this.sender.fullName} хочет с Вам подружиться`;
            }
        }

        if ('friendships.accepted' === this.notificationType) {
            switch (this.sender.sex){
                case 'f': return `${this.sender.fullName} одобрила Ваш запрос на дружбу`;
                case 'm': return `${this.sender.fullName} одобрил Ваш запрос на дружбу`;
                case 'n': return `Пользователь ${this.sender.fullName} одобрил Ваш запрос на дружбу`;
            }
        }

        if ('friendships.denied' === this.notificationType) {
            switch (this.sender.sex){
                case 'f': return `${this.sender.fullName} отказалась дружить с Вами`;
                case 'm': return `${this.sender.fullName} отказался дружить с Вами`;
                case 'n': return `Пользователь ${this.sender.fullName} отклонил Вашу дружбу`;
            }
        }

        if ('user.post.created' === this.notificationType) {
            switch (this.sender.sex){
                case 'f': return `Ваш друг ${this.sender.fullName} опубликовала новость`;
                case 'm': return `Ваш друг ${this.sender.fullName} опубликовал новость`;
                case 'n': return `Ваш друг пользователь ${this.sender.fullName} опубликовал новость`;
            }
        }

        if ('user.profile.image.updated' === this.notificationType) {
            switch (this.sender.sex){
                case 'f': return `Ваш друг ${this.sender.fullName} обновила фото своего профиля`;
                case 'm': return `Ваш друг ${this.sender.fullName} обновил фото своего профиля`;
                case 'n': return `Ваш друг пользователь ${this.sender.fullName} обновил фото своего профиля`;
            }
        }

        if ('user.profile.image.created' === this.notificationType) {
            switch (this.sender.sex) {
                case 'f': return `Ваш друг ${this.sender.fullName} добавила новое фото`;
                case 'm': return `Ваш друг ${this.sender.fullName} добавил новое фото`;
                case 'n': return `Ваш друг пользователь ${this.sender.fullName} добавил новое фото`;
            }
        }

        if ('friendships.cancelled' === this.notificationType) {
            switch (this.sender.sex) {
                case 'f' : return `${this.sender.fullName} отклонила Ваш запрос на дружбу`;
                case 'm' : return `${this.sender.fullName} отклонил Ваш запрос на дружбу`;
                case 'n' : return `Пользователь ${this.sender.fullName} отклонил Ваш запрос на дружбу`;
            }
        }

        if ('post.liked' === this.notificationType) {
            switch (this.sender.sex) {
                case 'f' : return `${this.sender.fullName} лайкнула Ваш пост`;
                case 'm' : return `${this.sender.fullName} лайкнул Ваш пост`;
                case 'n' : return `Пользователь ${this.sender.fullName} лайкнул Ваш пост`;
            }
        }

        if ('community.post.created' === this.notificationType) {
            return `В сообществе "${this.community.name}" опубликован новый пост "${this.community.postName}"`;
        }

        if ("community.request.accepted" === this.notificationType) {
            return `В сообществе «${this.community.name}» одобрили Ваше вступление в сообщество`;
        }

        if ("community.request.rejected" === this.notificationType) {
            return `В сообществе «${this.community.name}»  отклонили Ваше вступление в сообщество`;
        }

        return this.body;
    }

    toJSON(){
        let ret = {
            body : this.body,
            notificationType : this.notificationType
        };

        if (this.isHuman()) {
            ret.sender = this.sender.toJSON();
        }

        if (this.isCommunity()) {
            ret.community = this.community.toJSON();
        }

        return ret;
    }
}

export { PliziNotificationData as default}
