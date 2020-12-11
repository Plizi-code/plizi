<template>
    <div id="postFilter" class="col-12 col-xl-9 mb-4 mb-xl-0 ">
        <div class="row mr-xl-0 bg-white-br20 align-items-center justify-content-between">

            <nav class="col-lg-8 nav profile-filter-links align-items-center  pl-3 mb-lg-0" role="tablist">
                <div class="nav-link py-0 px-1 mr-2 mr-lg-4 position-relative active">
                    <button
                        class="btn dropdown-menu-btn py-2 py-sm-3"
                        id="dropdownNews"
                        type="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        data-offset="-5,0"
                        aria-expanded="false">
                        Новости
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-left py-3 px-3" aria-labelledby="dropdownNews">

                        <div class="nav-item ">
                            <label for="ownNews" class="radio mb-3 mb-md-0">
                                <input class="mb-0"
                                       type="checkbox"
                                       name="ownNews"
                                       @click="selectAll" v-model="allSelected"
                                       id="ownNews"/>
                                <span class="mb-0">Все </span>
                            </label>
                        </div>
                        <div class="nav-item" v-for="part in parts" :key="part.key">
                            <label class="radio mb-3 mb-md-0">
                                <input class="mb-0"
                                       type="checkbox"
                                       :value="part.key"
                                       v-model="checked"
                                       @change="doEvent"
                                       @click="select"/>
                                <span class="mb-0">{{part.title}}</span>
                            </label>
                        </div>
                    </div>

                </div>

                <span class="nav-link py-2 py-sm-3 px-1 mr-2 mr-lg-4" :class="{'active': liked}" @click="likedClick">Понравилось</span>
                <!-- TODO: @YZ восстановить после MVP -->
                <span class="nav-link py-3 px-1 mr-2 mr-lg-4 ml-auto ml-lg-4 d-none #d-md-inline-block">
                    <button class="btn px-2 py-0 ">
                        <IconSearch style="width: 15px; height: 16px;"/>
                    </button>
                </span>
<!--                <div class="d-flex align-items-center form-inline mb-3 pl-0 pr-0 position-relative overflow-hidden rounded-pill mt-4 ml-auto">-->
<!--                    <div class="form-inline  position-relative w-100"-->
<!--                         :class="{'isFocused' : isFocused}">-->
<!--                        <input v-model="lastSearch"-->
<!--                               id="txtNewsListSearch"-->
<!--                               ref="txtNewsListSearch"-->
<!--                               @keydown.stop="searchKeyDownCheck($event)"-->
<!--                               @blur="onBlur"-->
<!--                               @focus="onFocus"-->
<!--                               class="top-search form-control form-control  w-100"-->
<!--                               type="text" placeholder="Поиск" aria-label="Поиск" />-->
<!--                        <button class="btn btn-search h-100" type="button" @click="startSearch()">-->
<!--                            <IconSearch style="width: 15px; height: 15px;"/>-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </div>-->
            </nav>

            <div class="newsViewModes col-lg-4 justify-content-end d-none ">
                <span>Вид:</span>
                <button class="btn bg-transparent p-0">
                    <IconMultipleViewMode style="width: 16px; height: 16px;"/>
                </button>
                <button class="btn bg-transparent p-0">
                    <IconSingleViewMode style="width: 16px; height: 16px;"/>
                </button>

            </div>

        </div>
    </div>
</template>

<script>
import IconMultipleViewMode from '../../icons/IconMultipleViewMode.vue';
import IconSearch from '../../icons/IconSearch.vue';
import IconSingleViewMode from '../../icons/IconSingleViewMode.vue';

export default {
name : 'PostFilter',
components : { IconMultipleViewMode, IconSearch, IconSingleViewMode },
props: {
    filter: Object,
},
data() {
    return {
        isFocused: false,
        lastSearch : '',
        liked: false,
        parts: [
            {key: 'own', title: 'Свои'},
            {key: 'friends', title: 'Друзья'},
            {key: 'communities', title: 'Сообщества'},
        ],
        checked: ['own', 'friends', 'communities'],
        allSelected: true,
    }
},

methods: {
    onFocus() {
        this.isFocused = true
    },
    onBlur() {
        this.isFocused = false
    },
    searchKeyDownCheck(ev) {
        if (13 === ev.keyCode) {
            return this.startSearch();
        }
    },
    startSearch() {
        this.$emit('lastSearchChange', this.lastSearch);
    },
    likedClick() {
        this.liked = !this.liked;
        this.$emit('likedClick', this.liked);
    },
    selectAll() {
        this.checked = [];

        if (!this.allSelected) {
            this.parts.map((part) => {
                this.checked.push(part.key);
            });
            this.doEvent();
        }
    },
    select() {
        this.allSelected = false;
    },
    doEvent() {
        this.$nextTick(() => {
            this.$emit('partsChange', this.checked);
        });
    },
},
mounted() {
    this.liked = this.filter.liked;
}

}
</script>

