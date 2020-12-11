<template>
    <div id="latestEntries" class="plz-latest-entries bg-white-br20" :style="calcStyle()">
        <h6 class="text-center text-sm-left">Вы недавно входили в аккаунт с этого компьютера</h6>

        <div class="plz-latest-entries-wrap ">
            <vue-custom-scrollbar class="plz-latest-entries-list d-flex justify-content-between justify-content-sm-start pb-3"
                                  :settings="customScrollbarSettings">
                <LastEntryItem v-for="leItem in getLastFiveEntries()"
                               v-bind:entry-item="leItem"
                               v-bind:key="'entry-' + leItem.id"
                               @click.native.prevent="onClickEntry(leItem)">
                </LastEntryItem>
            </vue-custom-scrollbar>
        </div>

        <LastEntryModal v-if="logInModal.isVisible"
                        :entry="logInModal.content.entry"
                        @onHide="onHideLastEntryModal"/>
    </div>
</template>

<script>
import LastEntryItem from './LastEntryItem.vue';
import PliziLastEntriesCollection from "../../classes/Collection/PliziLastEntriesCollection.js";
import LastEntryModal from "./LastEntryModal.vue";

/** @link https://binaryify.github.io/vue-custom-scrollbar/en/#why-custom-scrollbar **/
import vueCustomScrollbar from 'vue-custom-scrollbar';

export default {
name: 'LastEntries',
components: {
    LastEntryItem,
    LastEntryModal,
    vueCustomScrollbar,
},
data() {
    return {
        lastEntriesList: new PliziLastEntriesCollection(null),
        logInModal: {
            isVisible: false,
            content: {
                entry: null,
            },
        },
        customScrollbarSettings: {
            maxScrollbarLength: 60,
            suppressScrollY: true, // rm scroll x
            wheelPropagation: false
        },
    }
},

methods: {
    getLastFiveEntries() {
        if ( this.checkIsTarga() )
            return this.getLastFakeEntries();

        let lastEntriesList = this.lastEntriesList.asArray().slice();
        lastEntriesList.push({id: 0, src: '/images/icons/add-account.png', fullName: 'Добавить аккаунт', isUser: false});

        return lastEntriesList;
    },

    // TODO: удалить позже
    checkIsTarga(){
        return (typeof isTarga !== 'undefined'  &&  !!isTarga);
    },

    // TODO: удалить позже
    calcStyle(){
        if (this.checkIsTarga())
            return { 'height' : 'auto', 'padding-bottom': '2rem' };

        return {};
    },

    //TODO: @TGA удалить потом эти mock-данные
    getLastFakeEntries(){
        return [
            {id: 1, src: '/images/last-entries/ava-2.png', fullName: 'Мариана Кабанова', isUser: true, email: `test@gmail.com` },
            {id: 2, src: '/images/last-entries/ava-1.png', fullName: 'Виктория Мамонтовa', isUser: true, email: `user@mail.com`},
            {id: 3, src: '/images/last-entries/ava-3.png', fullName: 'Полина Дорожина', isUser: true, email: `admin@mail.com`},

            {id: 4, src: '/images/last-entries/ava-21.png', fullName: 'Иннокентий Савельев', isUser: true, email: `brandyn96@paucek.net`},
            {id: 5, src: '/images/last-entries/ava-4.png', fullName: 'Alex Targa', isUser: true, email: `targa@ua.fm`, password: `5eb415816486f`},
            {id: 6, src: '/images/last-entries/ava-7.png', fullName: 'Alex Dnipro', isUser: true, email: `targettius@gmail.com`, password: `5eb595c40a762`},

            {id: 7, src: '/images/last-entries/ava-22.png', fullName: 'Филипп Голубев', isUser: true, email: `homenick.amber@carroll.com`},
            {id: 8, src: '/images/last-entries/ava-23.png', fullName: 'Елизавета Зимина', isUser: true, email: `rruecker@mann.com`},
            {id: 9, src: '/images/last-entries/ava-24.png', fullName: 'Яна Шарапова', isUser: true, email: `ohintz@yahoo.com `},

            {id: 10, src: '/images/last-entries/ava-25.png', fullName: 'Михаил Шарапов', isUser: true, email: `vharris@robel.org`},
            {id: 11, src: '/images/last-entries/ava-26.png', fullName: 'Родион Корнилов', isUser: true, email: `kellen.williamson@gmail.com`},
            {id: 12, src: '/images/last-entries/ava-27.png', fullName: 'Олеся Александрова', isUser: true, email: `vstreich@powlowski.biz`},
        ];
    },
    onClickEntry(entry) {
        if (this.checkIsTarga() || entry.id === 0) {
            this.$emit('logInWithEntryItem', entry);

            return;
        }

        this.logInModal.content.entry = entry;
        this.logInModal.isVisible = true;
    },
    onHideLastEntryModal() {
        this.logInModal.isVisible = false;
        this.logInModal.content.entry = null;
    },
},
created() {
    this.lastEntriesList.restore();
}
}
</script>

