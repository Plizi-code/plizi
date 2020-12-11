import PliziRecipient from '../PliziRecipient.js';
import PliziCollection from '../PliziCollection.js';

/**
 * класс для работы со списком получателей пересылаемых сообщений
 */
class PliziRecipientsCollection extends PliziCollection{

    /**
     * метод сравнения для сортировки
     * @param {PliziRecipient} d1
     * @param {PliziRecipient} d2
     * @returns {number}
     */
    compare(d1, d2){
        return d1.fullName > d2.firstName ? 1 : -1;
    }


    /**
     * @param {Object} data
     * @param {number|null} chatId
     */
    add(data, chatId){
        if (data) {
            const newRecip = new PliziRecipient(data, chatId);

            this.collection.set(newRecip.id, newRecip);
        }
    }


    /**
     * @returns {PliziRecipient[]}
     */
    asArray(){
        return super.asArray();
    }

}

export { PliziRecipientsCollection as default }
