import moment from 'moment';

export function isCorrectFullName(value) {
    return /^[0-8a-zа-яёїіи+\-\s]{2,100}$/i.test(value);
}

export function isCorrectHumanName(value) {
    if ((value+'') === '')
        return true;

    return /^[a-zа-яёїіи+\-\s]{1,100}$/i.test(value);
}

export function isCorrectSlug(value) {
    if ((value+'') === '') {
        return true;
    }

    return /^[a-z][a-z\-\_\d]*$/i.test(value);
}

export function isCorrectUrl(value) {
    if ((value+'') === '') {
        return true;
    }

    return /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi.test(value);
}

export function notHaveSpace(value) {
    return !(/\s/.test(value));
}

export function isFirstNameAndLastName(value) {
    if ((value+'') === '')
        return true;

    value = value.replace(/\s\s+/g, ' ');
    let parts = value.split(' ');

    return (parts.length===2  &&  parts[0].trim()!==''  &&  parts[1].trim()!=='');
}


export function isValidRegistrationBirthDay(dateVal){
    let birthday = moment(dateVal, 'YYYY-MM-DD', true);
    let now = moment();
    let last = moment('1950-01-01');

    if (birthday && birthday.isValid()) {
        return (birthday <= now) && (birthday >= last);
    }

    return false;
}

export function isValidYoutubeLink(value) {
    let youtubeLinksRegExp = /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|www.youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig;
    let youtubeLinks = value.trim().match(youtubeLinksRegExp);

    return !!(youtubeLinks && youtubeLinks.length);
}
