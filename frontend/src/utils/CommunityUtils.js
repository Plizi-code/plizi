export default {
    privacyList: [
        {value: 1, title: 'Открытое'},
        {value: 2, title: 'Закрытое'},
        /**
         * @todo uncomment in future
         */
        // {value: 3, title: 'Частное'},
    ],
    types: [
        {value: 1, title: 'Бизнес'},
        {value: 2, title: 'Тематическое сообщество'},
        {value: 3, title: 'Бренд или организация'},
        {value: 4, title: 'Группа по интересам'},
        {value: 5, title: 'Публичная страница'},
        {value: 6, title: 'Мероприятие'},
    ],
    getPrivacyLabel(value) {
        const el = this.privacyList.find((privacy) => {
            return privacy.value === value;
        });
        if (el) {
            return el.title;
        }
        return 'Открытое';
    }
}
