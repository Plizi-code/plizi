import PliziFriend from '../classes/PliziFriend.js';
import moment from "moment";

const FriendItemMixin = {

props : {
    friend : PliziFriend,
},

data(){
    return {
        isInRedirecting: false
    }
},

methods: {
    async goToDialogWithFriend(){
        this.isInRedirecting = true;
        await this.openDialogWithFriend( this.friend );
        this.$root.$router.push('/chats');
    },

    lastFriendActivity(fItem){
        const sexTitle = this.getSexTitle(fItem.sex);

        let now = moment();
        let yesterday = moment().subtract(1, 'days');
        let lmt = moment.unix(fItem.lastActivity / 1000); // @TGA деление на 1000 тут не ошибка

        // если был сегодня
        if (now.format('YYYY-MM-DD')===lmt.format('YYYY-MM-DD')) {
            return sexTitle+' сегодня в '+lmt.format('HH:mm');
        }

        // если был вчера
        if (yesterday.format('YYYY-MM-DD')===lmt.format('YYYY-MM-DD')) {
            return sexTitle+' вчера в '+lmt.format('HH:mm');
        }

        // сообщение было в течение последних 7 дней
        let lastWeek = moment().subtract(7, 'days');
        if ( +lmt.format('X') >= +lastWeek.format('X')) {
            const dow = lmt.format('dddd').toLowerCase();
            let dowF = '';
            switch (dow){
                case 'понедельник': dowF = 'Понедельник'; break;
                case 'вторник': dowF = 'Вторник'; break;
                case 'среда': dowF = 'Среду'; break;
                case 'четверг': dowF = 'Четверг'; break;
                case 'пятница': dowF = 'Пятницу'; break;
                case 'суббота': dowF = 'Субботу'; break;
                case 'воскресенье': dowF = 'Воскресенье'; break;
                default: dowF = dow.charAt(0).toUpperCase() + dow.slice(1); break;
            }

            return sexTitle+' в '+dowF;
        }

        return sexTitle+' '+lmt.format('DD.MM.YY');
    },

    getSexTitle(sex){
        if ( `m` === sex )
            return `был`;

        if ( `f` === sex )
            return `была`;

        return `был(а)`;
    }
}

};

export {FriendItemMixin as default}
