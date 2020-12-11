<template>
    <div class="form-inline mt-1 mt-md-1 position-relative overflow-hidden rounded-pill w-100 "
         :class="{'isFocused' : isFocused}">
        <input v-model="$root.$lastSearch" id="topSearch" ref="topSearch"
               @keydown.stop="topSearchKeyDownCheck($event)"
               @blur="onBlur"
               @focus="onFocus"
               class="top-search form-control form-control  w-100"
               type="text" placeholder="Поиск" aria-label="Поиск" />
        <button class="btn btn-search h-100 hot-communities" type="submit"  @click="initSearch()">
            <IconSearch style="width: 15px; height: 15px;" />
        </button>
    </div>
</template>

<script>
import IconSearch from '../../icons/IconSearch.vue';
import PliziAuthUser from '../../classes/PliziAuthUser.js';

export default {
name : 'NavBarSearch',
    components: {IconSearch},
    props: {
        userData: PliziAuthUser
    },
    data () {
    return {
        isFocused: false
    }
},

methods: {
    topSearchKeyDownCheck(ev) {
        const sText = this.$refs.topSearch.value.trim();

        if ( 13 === ev.keyCode   &&  sText!==``){
                return this.startSearch(sText);
        }
    },

    initSearch(){
        const sText = this.$refs.topSearch.value.trim();
        window.console.log(sText, `initSearch`);

        if (sText!==``){
            return this.startSearch(sText);
        }
    },

    startSearch(sText){
        window.localStorage.setItem('pliziLastSearch', sText);

        this.$root.$emit('searchStart', {
            searchText : sText,
            source: `topSearch`
        });

        if (this.$root.$isAuth) {
            if (this.$route.name !== `SearchResultsPage`) {
                this.$router.push({ path: '/search-results' });
            }
        }
        else {
            if (this.$route.name !== `GuestSearchResultsPage`) {
                this.$router.push({ path: '/guest-search-results' });
            }
        }
    },

    onFocus() {
        this.isFocused = true
    },
    onBlur() {
        this.isFocused = false
    }
},
}
</script>
