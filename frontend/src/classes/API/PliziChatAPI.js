import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziChatAPI extends PliziBaseAPI{

    /**
     * загружает список диалогов (чатов) у юзера
     * @public
     * @returns {object[]|null} - список диалогов юзера, или NULL если их нет
     */
    async dialogs() {
        let response = await this.axios.get('api/chat/dialogs', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.dialogs`);
                throw new PliziAPIError(`$chat.dialogs`, error.response);
            });

        if (response.status === 200) {
            return response.data.list;
        }

        return null;
    }


    /**
     * загружает список диалогов (чатов) у юзера с поиском в собеседниках по имени
     * @param {string} sText - текст для поиска
     * @returns {object[]|null} - список диалогов, или NULL если не найдегл
     */
    async dialogSearchByName(sText) {
        let response = await this.axios.get(`api/chat/dialogs?search=` + sText, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.dialogSearchByName`);
                throw new PliziAPIError(`$chat.dialogSearchByName`, error.response);
            });

        if (response.status === 200) {
            return response.data.list;
        }

        return null;
    }


    /**
     * создаёт новый пустой диалог с юзерами, если диалог с ними уже есть - просто возвращает ID существующего диалога
     * @param {string} chatName - название чата
     * @param {string[]} users - список ID-шников юзеров-собеседников
     * @returns {object} - ID диалога
     */
    async dialogOpen(chatName, users) {
        const sendData = {
            name: chatName || ``,
            userIds: users
        };

        let response = await this.axios.post('api/chat/open', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.dialogOpen`);
                throw new PliziAPIError(`$chat.dialogOpen`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }


    /**
     * удаляет диалог
     * @link http://vm1095330.hl.had.pm:8082/docs/#/Chats/deleteChat
     * @param {number} chatId - ID-шников чата (диалога)
     * @returns {object} - ID диалога
     */
    async dialogRemove(chatId) {
        let response = await this.axios.delete(`api/chat/${chatId}`, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.dialogRemove`);
                throw new PliziAPIError(`$chat.dialogRemove`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * добавляем собеседника в чат
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Chats/addUserToChat
     * @param {string} chatId - ID чата (диалога)
     * @param {string} userId - ID юзера, которого хотим добавить
     * @returns {object}
     * @throws PliziAPIError
     */
    async addAttendee(chatId, userId) {
        const sendData = {
            chatId: chatId,
            userId: userId
        };

        let response = await this.axios.post('api/chat/dialogs/attendees/append', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.addAttendee`);
                throw new PliziAPIError(`$chat.addAttendee`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }


    /**
     * удаляем собеседника из чата
     * @see http://vm1095330.hl.had.pm:8082/docs/#/Chats/addUserToChat
     * @param {string} chatId - ID чата (диалога)
     * @param {string} userId - ID юзера, которого хотим удалить из чата
     * @returns {object}
     * @throws PliziAPIError
     */
    async removeAttendee(chatId, userId) {
        const sendData = {
            chatId: chatId,
            userId: userId
        };

        let response = await this.axios.post('api/chat/dialogs/attendees/remove', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.removeAttendee`);
                throw new PliziAPIError(`$chat.removeAttendee`, error.response);
            });

        if (response.status === 200) {
            return response.data.data;
        }

        return null;
    }


    /**
     * загружает список сообщений (переписку) в определённом диалоге чата
     * @param {number} dialogID - ID диалога
     * @param {number} offset - смещение
     * @param {number} limit - лимит
     * @returns {object[]|null} - список сообщений в диалоге, или NULL если была ошибка
     */
    async messages(dialogID, offset = null, limit= null) {
        let apiPath = 'api/chat/messages/' + dialogID;

        if (offset  &&  limit) {
            apiPath += `?offset=${offset}&limit=${limit}`;
        }

        let response = await this.axios.get(apiPath, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.messages`);
                throw new PliziAPIError(`$chat.messages`, error.response);
            });

        if (response.status === 200) {
            return response.data.list;
        }

        return null;
    }


    /**
     * отправляет сообщение в чат
     * @param {number} dialogID - ID чата (диалога)
     * @param {string} message - текст сообщения
     * @param {number[]} attachments - ID-шники аттачментов
     * @returns {object} - объект с данными как в PliziMessage
     * @throws PliziAPIError
     */
    async messageSend(dialogID, message, attachments) {
        const sendData = {
            chatId: dialogID,
            body: message,
            attachments: attachments
        };

        let response = await this.axios.post('api/chat/send', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.messageSend`);
                throw new PliziAPIError(`$chat.messageSend`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * отправляет сообщение пользователю и создаёт новый чат (диалог)
     * @param {number} userID - ID которому шлём
     * @param {string} message - текст сообщения
     * @param {number[]} attachments - ID-шники аттачментов
     * @returns {object} - объект с данными как в PliziMessage (также есть поле с chatId)
     * @throws PliziAPIError
     */
    async privateMessageSend(userID, message, attachments) {
        const sendData = {
            body: message,
            userId: userID,
            attachments: attachments
        };

        let response = await this.axios.post('api/chat/message/user', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.privateMessageSend`);
                throw new PliziAPIError(`$chat.privateMessageSend`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * удаление сообщения юзера
     * @param {number} messageID - ID сообщения
     * @returns {boolean} - true если удаление успешное
     */
    async messageDelete(messageID) {
        let response = await this.axios.delete(`api/chat/message/${messageID}`, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.messageDelete`);
                throw new PliziAPIError(`$chat.messageDelete`, error.response);
            });

        if (response.status === 200) {
            return true;
        }

        return null;
    }


    /**
     * @link http://vm1095330.hl.had.pm:8082/docs/#/Chats/sendMessage
     * @param {object} config - объект с полями в соответствии с докой
     * @param {object} forwardData - объект с полями в соответствии с докой
     * @returns {object} - объект с данными как в PliziMessage (также есть поле с chatId)
     * @throws PliziAPIError
     */
    async messageForward(config, forwardData) {
        window.console.log(`messageForward`);
        let apiPath;

        if (config.chatId) {
            forwardData.chatId = config.chatId;
            apiPath = `api/chat/send`;
        }
        else {
            forwardData.userId = config.userId;
            apiPath = `api/chat/message/user`;
        }

        let response = await this.axios.post(apiPath, forwardData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.messageForward`);
                throw new PliziAPIError(`$chat.messageForward`, error.response);
            });

        if (response.status === 200) {
            return response.data;
        }

        return null;
    }


    /**
     * добавляет аттачмент к сообщению пользователя
     * @param sendFiles - текст сообщения
     * @returns {object} - массив со списками ID-шников аттачментов
     * @throws PliziAPIError
     */
    async attachment(sendFiles) {
        const formData = new FormData();

        /**  @TGA: sendFiles.map не работает :( **/
        for(let i=0; i< sendFiles.length; i++){
            formData.append('files[]', sendFiles[i]);
        }

        let response = await this.axios.post('api/chat/message/attachments', formData, this.authFileHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, `$chat.attachment`);
                throw new PliziAPIError(`$chat.attachment`, error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


}

export { PliziChatAPI as default}
