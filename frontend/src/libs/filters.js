/**
 * @link https://momentjs.com/docs/#/parsing/string-format/
 * @link http://numeraljs.com/
 * @link https://habr.com/ru/post/163321/
 */

import Vue from 'vue';
import moment from 'moment';
import numeral from 'numeral';

moment.locale('ru');

Vue.filter('toHM', (dateValue) => {
    return moment(dateValue).format('HH:mm');
});

Vue.filter('toLongDate', (dateValue) => {
    return moment(dateValue).format('DD MMMM YYYY г.');
});

Vue.filter('toFullDateTime', (dateValue) => {
    return moment(dateValue).format('YYYY-MM-DD HH:mm:ss');
});

Vue.filter('toDMYHM', (dateValue) => {
    return moment(dateValue).format('DD.MM.YYYY HH:mm');
});

Vue.filter('toDMY', (dateValue) => {
    return moment(dateValue).format('DD.MM.YYYY');
});

Vue.filter('calcAge', (bDate) => {
    const now = moment();
    const birthDate = moment(bDate);
    return now.diff(birthDate, 'years');
});


/**
 * это  фильтр для юзеров
 */
Vue.filter('lastEventTime', (messageDT) => {
    let now = moment();
    let yesterday = moment().subtract(1, 'days');
    let lmt = moment(messageDT);

    // если сообщение было сегодня или вчера
    if (now.format('YYYY-MM-DD')===lmt.format('YYYY-MM-DD')  ||  yesterday.format('YYYY-MM-DD')===lmt.format('YYYY-MM-DD')) {
        return lmt.format('HH:mm');
    }

    // сообщение было в течение последних 7 дней
    let lastWeek = moment().subtract(7, 'days');
    if ( +lmt.format('X') >= +lastWeek.format('X')) {
        let dow = lmt.format('dddd');
        return dow.charAt(0).toUpperCase() + dow.slice(1);
    }

    return lmt.format('DD.MM.YY');
});

/**
 * это фильтр для сообщений в чате
 */
Vue.filter('lastMessageTime', (messageDT) => {
    let now = moment();
    let yesterday = moment().subtract(1, 'days');
    let lmt = moment(messageDT);

    // если сообщение было сегодня или вчера
    if (now.format('YYYY-MM-DD')===lmt.format('YYYY-MM-DD')  ||  yesterday.format('YYYY-MM-DD')===lmt.format('YYYY-MM-DD')) {
        return lmt.format('HH:mm');
    }

    // сообщение было в течение последних 7 дней
    let lastWeek = moment().subtract(7, 'days');
    if ( +lmt.format('X') >= +lastWeek.format('X')) {
        let dow = lmt.format('ddd');
        return dow.charAt(0).toUpperCase() + dow.slice(1);
    }

    return lmt.format('DD.MM.YY');
});

/**
 * это для постов
 */
Vue.filter('lastPostTime', (messageDT) => {
    let now = moment();
    let yesterday = moment().subtract(1, 'days');
    let lmt = moment(messageDT);

    if (lmt.isSame(now, 'day')) {
        return lmt.format('HH:mm');
    }

    if (lmt.isSame(yesterday, 'day')) {
        return "Вчера";
    }

    if (lmt.isSame(now, 'week')) {
        return lmt.format('dddd');
    }

    return lmt.format('DD.MM.YYYY');
});

/**
 * если число больше minLimit, то превращает число вида `3483` в строку вида `3 K`
 */
Vue.filter('statsBeauty', (nValue, minLimit) => {
    minLimit = minLimit ||  2000;

    if (nValue >= minLimit) {
        return numeral(nValue).format('0 a').toUpperCase().replace(/\s/g, '&nbsp;');
    }

    return nValue;
});

/**
 * заменяет все переводы строк на тэг <br />
 */
Vue.filter('toBR', (text) => {
    return text.replace(/\n/g, '<br />');
});


/**
 * превращает число вида `34833483` в строку вида `34 833 483`
 */
Vue.filter('space1000', (dValue) => {
    return numeral(dValue).format('0,0').replace(/,/g,' ');
});

Vue.filter('toYMD', (value) => {
    return moment(value).format('YYYY-MM-DD');
});

Vue.filter('mutualFriendsText', (mfCount) => {
    if (+mfCount === 0)
        return `<span class="mutual-friends-number">нет</span> общих друзей`;

    const lastNum = (mfCount+``).substr(-1, 1);

    let ofText = `<span class="mutual-friends-number">есть</span> общие друзья`;

    if (mfCount < 10) {
        switch (mfCount){
            case 1:
                ofText = `<span class="mutual-friends-number">один</span> общий друг`; break;
            case 2:
            case 3:
            case 4:
                ofText = `<span class="mutual-friends-number">${mfCount}</span> общих друга`; break;
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                ofText = `<span class="mutual-friends-number">${mfCount}</span> общих друзей`; break;
        }

        return ofText;
    }

    if (mfCount>=10 && mfCount<=20)
        return `<span class="mutual-friends-number">${mfCount}</span> общих друзей`;

    if (mfCount>=21) {
        switch (lastNum){
            case '1':
                ofText = `<span class="mutual-friends-number">${mfCount}</span> общий друг`; break;
            case '2':
            case '3':
            case '4':
                ofText = `<span class="mutual-friends-number">${mfCount}</span> общих друга`; break;
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
            case '0':
                ofText = `<span class="mutual-friends-number">${mfCount}</span> общих друзей`; break;
        }

        return ofText;
    }

    return ofText;

});


/** @see https://locutus.io/php/strings/strip_tags/ **/
Vue.filter('stripTags', (text, allowed) => {
    allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

    let tags = /<\/?([a-z0-9]*)\b[^>]*>?/gi;
    let commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

    let after = text + ``;
    after = (after.substring(after.length - 1) === '<') ? after.substring(0, after.length - 1) : after;

    while (true) {
        let before = after
        after = before.replace(commentsAndPhpTags, '').replace(tags, ($0, $1) => {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        })

        if (before === after)
            return after
    }

});
