<template>
    <div id="communitiesFilter" class="col-12 col-lg-8 col-xl-9 py-0">
        <div class="d-flex bg-white-br20 mb-4 pb-0 pt-3 pt-xl-0 px-4 flex-column-reverse flex-xl-row">
            <div class="col-12 col-xl-8 d-flex align-items-center justify-content-between px-0">
                <nav class="nav profile-filter-links mt-2 mt-md-0" role="tablist">
                    <router-link to="/communities" tag="span" class="nav-link py-2 py-sm-3 py-xl-4 px-1 mr-2 mr-xl-4" role="tab"
                                 :class="{ 'active': 'CommunitiesListPage'===this.$root.$router.currentRoute.name }">
                        Мои сообщества
                    </router-link>

                    <router-link to="/manage-communities" tag="span" class="nav-link py-2 py-sm-3 py-xl-4 px-1 mr-2 mr-xl-4"  role="tab"
                                 :class="{ 'active': 'CommunitiesManagePage'===this.$root.$router.currentRoute.name }">
                        Управление
                    </router-link>

                    <router-link to="/popular-communities" tag="span" class="nav-link py-2 py-sm-3 py-xl-4 px-1 mr-2 mr-xl-4" role="tab"
                                 :class="{ 'active': 'CommunitiesPopularPage'===this.$root.$router.currentRoute.name }">
                        Популярные сообщества
                    </router-link>
                </nav>
            </div>

            <div class="col-12 col-xl-4 d-flex align-items-center form-inline mb-3 mb-xl-0 pl-0 pl-xl-4 pr-0 position-relative  rounded-pill mt-4 mt-md-0 ">
                <div class="form-inline  position-relative w-100"
                     :class="{'isFocused' : isFocused}">
                    <!-- FIXME: @TGA - для таких вещей есть миксины, не нужно всё тащить в глобальный скоуп -->
                    <input v-model="lastCommunitiesSearch[list]"
                           @keydown.stop="communitySearchKeyDownCheck($event)"
                           id="txtCommunitiesListSearch"
                           ref="txtCommunitiesListSearch"

                           @blur="onBlur"
                           @focus="onFocus"
                           class="top-search form-control form-control  w-100"
                           type="text" placeholder="Поиск" aria-label="Поиск" />
                    <button class="btn btn-search h-100 " type="submit"  @click="initSearch()" >
                        <IconSearch style="width: 15px; height: 15px;" />
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import IconSearch from '../../icons/IconSearch.vue';
import {debounce} from "../../utils/Debonce.js";

export default {
name : 'CommunitiesListHeader',
components: {IconSearch},
props: {
    list: String,
},

data() {
    return {
        isFocused: false,
        lastCommunitiesSearch: {
            popular: '',
            my: '',
            owner: '',
        }
    }
},

mounted() {
    this.lastCommunitiesSearch[this.list] = window.localStorage.getItem('lastCommunitiesSearch_' + this.list);
},

methods: {
    communitySearchKeyDownCheck: debounce(function (ev) {
        const sText = this.$refs.txtCommunitiesListSearch.value.trim();
        if (13 === ev.keyCode) {
            return this.startSearch(sText);
        }
    }, 100),

    initSearch: debounce(function () {
        const sText = this.$refs.txtCommunitiesListSearch.value.trim();
        return this.startSearch(sText);
    }, 100),

    startSearch(sText) {
        window.localStorage.setItem('lastCommunitiesSearch_' + this.list, sText)
        this.$root.$emit('communitySearchStart', {
            searchText: sText,
            source: 'communitySearch',
            list: this.list,
        });
    },

    onFocus() {
        this.isFocused = true
    },

    onBlur() {
        this.isFocused = false
    }
}
}
</script>
