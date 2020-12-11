/**
 * @param {Date|number|string} dValue
 * @returns {Date}
 */
export function convertToDate(dValue){
    /** @see https://developer.mozilla.org/ru/docs/Web/JavaScript/Reference/Global_Objects/Date **/
    if (dValue instanceof Date)
        return new Date( dValue.valueOf() ); // чтобы вернуть по значению, а не по ссылке

    if (typeof dValue === 'number')
        return new Date(dValue*1000);  // умножаем на 1000 потому, что тут JS считает в миллисекундах

    // дата в виде строки
    return new Date(dValue);
}
