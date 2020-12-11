/**
 * расширяет стандартный класс ошибки для упрощения обработки ошибок в PliziAPI
 * @see PliziAPI
 */
class PliziAPIError extends Error {

    /**
     * код HTTP статуса который вернул Axios
     * @type {number}
     */
    status = -1;

    /**
     * текст HTTP статуса который вернул Axios
     * @type {string}
     */
    statusText = ``;

    /**
     * название API метода, который вбросил ошибку
     * @type {string}
     */
    method = ``;

    /**
     * сообщение сервера в виде текста (как его прислал сервер)
     * @type {string}
     */
    serverMessage = ``;

    /**
     * сообщение сервера превращённое в статус для упрощения обработки
     * перевод в верхний регистр и пробелы заменяются на подчёркивания
     * "Token is Expired" -> "TOKEN_IS_EXPIRED"
     * @type {string}
     */
    serverAnswer = ``;

    /**
     * массив с сообщениями сервера
     * @type {string[]}
     */
    serverMessages = [];

    /**
     * расширенное сообщение об ошибке
     * @type {string}
     */
    detailMessage = ``;

    /**
     * раздел data, так как его вернул axios
     * @type {object}
     */
    data = {};

    constructor(method, errResponse) {
        const sts = errResponse  && errResponse.status ? errResponse.status : -1;
        const sText = errResponse  &&  errResponse.statusText ? errResponse.statusText : `Unknown status`;
        const eName = `PliziAPIError`;
        const msg = `PliziAPI->${method}: ${sts} ${sText}`;

        super(msg);

        this.method = method;
        this.name = eName;
        this.status = sts;
        this.statusText = sText;

        if (errResponse  &&  errResponse.data) {
            this.data = errResponse.data;

            if (errResponse.data.message) {
                this.serverMessage = (errResponse.data.message+'').trim();
                this.serverMessages = [this.serverMessage];
                this.serverAnswer = this.serverMessage.trim().toUpperCase().replace(/\s/g, '_')
            }

            if (errResponse.data.messages  &&  Array.isArray(errResponse.data.messages) && errResponse.data.messages.length>0) {
                errResponse.data.messages.map( mItem => this.serverMessages.push(mItem) );
                this.serverMessage = this.serverMessages.join('\r\n').trim();
            }
        }

        this.detailMessage = `PliziAPI->${this.method}: ${this.status} ${this.statusText}` + (this.message !== '' ? ' :'+this.message: '');

        if (Error.captureStackTrace) {
            Error.captureStackTrace(this, PliziAPIError);
        }
        else {
            this.stack = (new Error()).stack;
        }
    }
}

export { PliziAPIError as default}
