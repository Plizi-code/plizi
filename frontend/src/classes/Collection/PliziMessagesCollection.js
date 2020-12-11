import PliziMessage from '../PliziMessage.js';
import PliziLinkedList from '../PliziLinkedList.js';

/**
 * класс для работы со списком диалогов в чате
 */
class PliziMessagesCollection extends PliziLinkedList {

    /**
     * имя эвента, который мы кидаем когда данные в коллекции обновлены
     * @type {string}
     * @public
     */
    updateEventName = 'MessagesIsUpdated';

    /**
     * метод сравнения для сортировки, сообщения ВСЕГДА сортируем по дате
     * сортируем в обратке !
     * @param {PliziMessage} d1
     * @param {PliziMessage} d2
     * @returns {number}
     */
    compare(d1, d2){
        if (d1.messageUnix === d2.messageUnix)
            return 0;

        return d1.messageUnix < d2.messageUnix ? -1 : 1;
    }


    new(data){
        return new PliziMessage(data);
    }


    /**
     * поиск сообщения по его ID
     * @param {string} ID - ID сообщения
     * @returns {PliziMessage} - диалог, или UNDEFINED если не нашли
     */
    get(ID){
        return this.collection.get(ID);
    }


    /**
     * @returns {PliziMessage[]}
     */
    asArray(){
        let arr = [ ...this.collection.values() ];
        arr.sort( this.compare );

        return arr;
    }


    /**
     *
     * @param {Object} condition
     * @returns {Object[]}
     */
    filter(condition){
        let retArr = this.asArray();

        // с фильтром
        let rangeStart, rangeEnd;

        if (condition.range && condition.range.start && condition.range.end) {
            rangeStart = condition.range.start;
            rangeEnd = condition.range.end;

            this.rangeStart = rangeStart;
            this.rangeEnd = rangeEnd;
        }

        // есть и текст и дата
        if (condition.text && condition.range && rangeStart && rangeEnd) {
            const ft = condition.text.toLocaleLowerCase();

            if (ft.length > 2) {
                return retArr.filter((msgItem) => {
                    return msgItem.body.toLowerCase().includes(ft) &&
                        (msgItem.createdAt > rangeStart) && (msgItem.createdAt < rangeEnd);
                });
            }
        }

        // есть только текст
        if (condition.text) {
            const ft = condition.text.toLocaleLowerCase();

            if (ft.length > 2) {
                return retArr.filter((msgItem) => {
                    return msgItem.body.toLowerCase().includes(ft);
                });
            }

            return retArr;
        }

        // только дата
        if (condition.range && rangeStart && rangeEnd) {
            return retArr.filter((msgItem) => {
                return (msgItem.createdAt >= rangeStart) && (msgItem.createdAt <= rangeEnd);
            });
        }

        return retArr;
    }

}

export { PliziMessagesCollection as default }
