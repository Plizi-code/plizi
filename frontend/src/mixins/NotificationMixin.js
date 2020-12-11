import {uuidv4} from '../utils/StringUtils.js';

const NotificationMixin = {
    data() {
        return {
            notifications: [],
            timeoutNotification: 25, // sec
            limitNotifications: 5,
        };
    },
    computed: {
        userData() {
            return this.$root.$auth.user;
        },
    },
    methods: {
        addNotification(inputNotification) {
            const uuid = uuidv4();

            let notification = {
                id: uuid,
                userPic: this.senderUserPic(inputNotification),
                firstName: (inputNotification.data.sender) ? inputNotification.data.sender.firstName : null,
                lastName: (inputNotification.data.sender) ? inputNotification.data.sender.lastName : null,
                name: (inputNotification.data.name) ? inputNotification.data.name : null,
                isHuman: true
            };

            if (inputNotification.data.notificationType === 'user.profile.image.created') {
                notification.body = this.senderFullName(inputNotification) +
                    (inputNotification.data.sender.sex === 'f' ? 'сменила аватарку' : 'сменил аватарку');
            }

            if (inputNotification.data.notificationType === 'user.profile.image.updated') {
                notification.body = this.senderFullName(inputNotification) +
                    (inputNotification.data.sender.sex === 'f' ? 'сменила аватарку' : 'сменил аватарку');
            }

            if (inputNotification.data.notificationType === 'friendships.accepted') {
                notification.body = this.senderFullName(inputNotification) +
                    ('f' === inputNotification.data.sender.sex ? 'одобрила Вашу заявку в друзья' : 'одобрил Вашу заявку в друзья');
            }

            if (inputNotification.data.notificationType === 'friendships.sent') {
                notification.body = this.senderFullName(inputNotification) +
                    ('f' === inputNotification.data.sender.sex ? 'отправила Вам заявку в друзья' : 'отправил Вам заявку в друзья');
            }

            if (inputNotification.data.notificationType === 'friendships.denied') {
                notification.body = this.senderFullName(inputNotification) +
                    ('f' === inputNotification.data.sender.sex ? 'отклонила Вашу заявку в друзья' : 'отклонил Вашу заявку в друзья');
            }

            if (inputNotification.data.notificationType === 'friendships.cancelled') {
                notification.body = this.senderFullName(inputNotification) +
                    ('f' === inputNotification.data.sender.sex ? 'удалила Вас из друзей' : 'удалил Вас из друзей');
            }

            if (inputNotification.data.notificationType === 'community.post.created') {
                notification.isHuman = false;
                notification.body = `Сообщество <b class="community-name">${inputNotification.data.community.name}</b> опубликовало новый пост`;
                notification.primaryImage = inputNotification.data.community.primaryImage;
            }

            if (inputNotification.data.notificationType === 'community.request.rejected') {
                notification.isHuman = false;
                notification.body = `Сообщество <b class="community-name">${inputNotification.data.community.name}</b> отклонило Ваш запрос`;
                notification.primaryImage = inputNotification.data.community.primaryImage;
            }

            if (inputNotification.data.notificationType === 'community.request.accepted') {
                notification.isHuman = false;
                notification.body = `Сообщество <b class="community-name">${inputNotification.data.community.name}</b> одобрило Ваш запрос`;
                notification.primaryImage = inputNotification.data.community.primaryImage;
            }

            if (inputNotification.data.notificationType === 'user.post.created') {
                notification.body = this.senderFullName(inputNotification) +
                    ('f' === inputNotification.data.sender.sex ? 'опубликовала новость' : 'опубликовал новость');
            }

            if (inputNotification.data.notificationType === 'chat.created') {
                if (inputNotification.attendees.length >= 3) {
                    notification.isHuman = false;
                    notification.primaryImage = "/images/noavatar-256.png";
                    notification.body = `Создан групповой чат ${inputNotification.data.name} с Вами`;
                } else {
                    notification.body = this.senderFullName(inputNotification) +
                        ('f' === inputNotification.data.sender.sex ? 'создала чат с Вами' : 'создал чат с Вами');
                }
            }

            if (inputNotification.data.notificationType === 'chat.removed') {
                if (inputNotification.attendees.length >= 3) {
                    notification.isHuman = false;
                    notification.primaryImage = "/images/noavatar-256.png";
                    notification.body = `Вас удалили из группового чата ${inputNotification.data.name}`;
                } else {
                    notification.body = this.senderFullName(inputNotification) +
                        ('f' === inputNotification.data.sender.sex ? 'удалила чат с Вами' : 'удалил чат с Вами');
                }
            }

            if (inputNotification.data.notificationType === 'chat.attendee.removed') {
                if (inputNotification.attendees.length >= 3) {
                    notification.isHuman = false;
                    notification.primaryImage = "/images/noavatar-256.png";
                    notification.body = `Вас удалили из группового чата ${inputNotification.data.name}`;
                } else {
                    notification.body = this.senderFullName(inputNotification) +
                        ('f' === inputNotification.data.sender.sex ? 'удалила чат с Вами' : 'удалил чат с Вами');
                }
            }

            if (inputNotification.data.notificationType === 'chat.attendee.appended') {
                if (inputNotification.attendees.length >= 3) {
                    notification.isHuman = false;
                    notification.primaryImage = "/images/noavatar-256.png";
                    notification.body = `Вас добавили в групповой чат ${inputNotification.data.name}`;
                } else {
                    notification.body = this.senderFullName(inputNotification) +
                        ('f' === inputNotification.data.sender.sex ? 'добавила Вас в групповой чат' : 'добавил Вас в групповой чат');
                }
            }

            if (inputNotification.data.notificationType === 'message.new') {
                notification.body = inputNotification.data.sender.message;
            }

            if (inputNotification.data.notificationType === 'app.notification') {
                notification.body = inputNotification.data.sender.message;
            }

            this.notifications.push({
                ...notification,
                uuid,
            });

            const lengthNotifications = this.notifications.length;

            if (lengthNotifications > this.limitNotifications) {
                this.notifications = this.notifications.slice(lengthNotifications - this.limitNotifications);
            }

            if (this.timeoutNotification > 0) {
                setTimeout(() => {
                    this.removeNotification({uuid});
                }, this.timeoutNotification * 1000);
            }
        },

        senderUserPic(inputNotification) {
            if (inputNotification.data.sender && inputNotification.data.sender.userPic) {
                return inputNotification.data.sender.userPic;
            } else {
                return this.$root.$defaultAvatarPath;
            }
        },

        senderFullName(inputNotification) {
            return `${inputNotification.data.sender.firstName} ${inputNotification.data.sender.lastName} `;
        },

        removeNotification({uuid}) {
            this.notifications = this.notifications.filter(foundNotification => foundNotification.uuid !== uuid);
        },

        transformMessageToNotification(data) {
            return {
                data: {
                    notificationType: data.type,
                    sender: {
                        userPic: data.message.userPic,
                        firstName: data.message.firstName ? data.message.firstName : null,
                        lastName: data.message.lastName ? data.message.lastName : null,
                        message: data.message.body
                    }
                }
            }
        },

        transformNotifyToNotification(data) {
            const userOwner = this.$root.$auth.user;
            return {
                data: {
                    notificationType: data.type,
                    sender: {
                        // userPic: userOwner.userPic ? userOwner.userPic : null,
                        userPic: 'images/plizi-icon-64.png',
                        message: data.message ? data.message : null
                    }
                }
            }
        },

        transformForChatRemovedToNotification(data, notifType) {
            if (this.isGroupChatforChatRemove(data, notifType)) {
                return {
                    attendees: Array.from(data.attendees),
                    data: {
                        notificationType: notifType,
                        name: data.name ? data.name : null,
                    }
                }
            }

            return {
                attendees: data.attendees,
                data: {
                    notificationType: notifType || data.type,
                    name: data.name ? data.name : null,
                    sender: {
                        userPic: data.attendees[0].userPic ? data.attendees[0].userPic : null,
                        firstName: data.attendees[0].firstName ? data.attendees[0].firstName : null,
                        lastName: data.attendees[0].lastName ? data.attendees[0].lastName : null,
                        sex: data.attendees[0].sex ? data.attendees[0].sex : null
                    }
                }
            }
        },

        transformDialogToNotification(data, notifType = null) {
            if (this.isGroupChat(data, notifType)) {
                return {
                    attendees: data.dialog.attendees,
                    data: {
                        notificationType: data.type,
                        name: data.dialog.name ? data.dialog.name : null,
                    }
                }
            }

            let user = data.dialog.attendees.filter(item => item.id !== this.userData.id);

            return {
                attendees: data.dialog.attendees,
                data: {
                    notificationType: notifType || data.type,
                    name: data.name ? data.name : null,
                    sender: {
                        userPic: user[0].userPic ? user[0].userPic : null,
                        firstName: user[0].firstName ? user[0].firstName : null,
                        lastName: user[0].lastName ? user[0].lastName : null,
                        sex: user[0].sex ? user[0].sex : null
                    }
                }
            }
        },

        isGroupChat(data, notifType = null) {
            const nType = notifType || data.type;
            if ((nType === 'chat.removed' && nType === 'chat.attendee.removed') && (data.dialog.attendees.length >= 2))
                return true;
            return (data.dialog.attendees.length >= 3);
        },

        isGroupChatforChatRemove(data, notifType = null) {
            const nType = notifType || data.type;
            if ((nType === 'chat.removed' && nType === 'chat.attendee.removed') && (data.attendees.length >= 2))
                return true;
            return (data.attendees.length >= 3);
        }
    }

};

export {NotificationMixin as default}
