import PliziBaseAPI from './PliziBaseAPI.js'
import PliziAPIError from './PliziAPIError.js';

class PliziNotificationsAPI extends PliziBaseAPI {

    /**
     * получаем список нотификаций
     * @returns {object[]|null}
     * @throws PliziAPIError
     */
    async list() {
        let response = await this.axios.get('api/user/notifications', this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$notifications.notificationsList');
                throw new PliziAPIError('$notifications.notificationsList', error.response);
            });

        if (response.status === 200) {
            return response.data.data.list;
        }

        return null;
    }


    /**
     * помечаем нотификации как прочитанные
     * @param {string[]} idsList - UUID-шники нотификация которые надо пометить
     * @returns {object[]|null} - список свежих нотификаций
     * @throws PliziAPIError
     */
    async markAsRead(idsList) {
        const sendData = {
            ids: idsList
        };

        let response = await this.axios.patch('api/user/notifications/mark/read', sendData, this.authHeaders)
            .catch((error) => {
                this.checkIsTokenExpires(error, '$notifications.markAsRead');
                throw new PliziAPIError('$notifications.markAsRead', error.response);
            });

        if (response.status === 200) {
            window.console.log(response.data, `response.data`);
            return response.data.data.list;
        }

        return null;
    }

}

export default PliziNotificationsAPI;
