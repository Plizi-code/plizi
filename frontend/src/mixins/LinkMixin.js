const LinkMixin = {
    data() {
        return {
            youtubeLinksRegExp: /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|www.youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig,
            youtubeIdRegExp: /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/,
            urlRegExp: /(https?:\/\/[^\s]+)/g,
        }
    },
    methods: {
        /**
         * Находим все ссылки на youtube видео в строке.
         *
         * @param str
         * @returns {array|null}
         */
        detectYoutubeLinks(str) {
            return str.match(this.youtubeLinksRegExp);
        },

        /**
         * Находим все id видео в ссылках на youtube видео.
         *
         * @params str
         * @returns {array|null}
         */
        detectYoutubeIds(str) {
            let youtubeIdsMatch = str.match(this.youtubeIdRegExp);

            return (youtubeIdsMatch && youtubeIdsMatch[7].length === 11) ? youtubeIdsMatch[7] : false;
        },

        /**
         * Определяем есть ли ссылки в встроке.
         *
         * @param str
         * @return {array|null}
         */
        detectLink(str) {
            return str.match(this.urlRegExp);
        },

        /**
         * Заменяем ссылки в строке.
         *
         * @param str
         * @return {string|null}
         */
        replaceLink(str) {
            return str.replace(this.urlRegExp, function (url) {
                return '<a href="' + url + '" target="_blank">' + url + '</a>';
            });
        },

        /**
         * Заменяем youtube ссылки на пустоту.
         *
         * @param str
         * @return {string|null}
         */
        deleteYoutubeLinksFromStr(str) {
            return str.replace(this.youtubeLinksRegExp, '');
        },

        transformStrWithLinks(str, imgName = 'mqdefault') {
            let youtubeLinks = this.detectYoutubeLinks(str);
            let links = this.detectLink(str);
            //TODO Лучьше сразу установить значение 'mqdefault' без переменной
            if (imgName == 'hqdefault') {
                imgName = 'mqdefault';
            }

            if (youtubeLinks && youtubeLinks.length) {
                let youtubeIds = [];

                youtubeLinks.forEach((youtubeLink) => {
                    youtubeIds.push(this.detectYoutubeIds(youtubeLink));
                });

                let textWithoutYoutubeLinks = str.replace(this.youtubeLinksRegExp, '');
                let textTransformToLinks = this.replaceLink(textWithoutYoutubeLinks);

                return {
                    videoLinks: `<img src="//img.youtube.com/vi/${youtubeIds[0]}/${imgName}.jpg"
                             alt="" />`,
                    text: `<p>${textTransformToLinks}</p>`,
                };
            } else if (links && links.length) {
                return {
                    text: this.replaceLink(str),
                };
            } else {
                return str;
            }
        },

        getParameterByName(name, url) {
            name = name.replace(/[\[\]]/g, '\\$&');

            let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);

            if (!results) return null;
            if (!results[2]) return '';

            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
    }
};

export default LinkMixin;
