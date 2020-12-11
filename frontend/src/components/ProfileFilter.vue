<template>
    <div id="profileFilter" class="row bg-white-br20 mb-4 pt-0 px-4">
        <div class="col-12 d-flex flex-wrap align-items-center justify-content-between flex-column-reverse flex-md-row px-0 ">
            <nav v-if="!firstName" class="nav profile-filter-links " role="tablist">
                <span class="nav-link py-3 px-1 mr-4" :class="{ 'active': wMode==='all' }" id="tabAllPosts" role="tab"
                      @click.stop="wallPostsSelect(`all`)">Все записи</span>
                <span class="nav-link py-3 px-1 mr-4" :class="{ 'active': wMode==='my' }" id="tabMyPosts" role="tab"
                      @click.stop="wallPostsSelect(`my`)">Мои записи</span>
                <span class="nav-link py-3 px-1 mr-4" :class="{ 'active': wMode==='archive' }" id="tabArchivePosts"
                      role="tab" @click.stop="wallPostsSelect(`archive`)">Архив</span>
            </nav>

            <nav v-else class="nav profile-filter-links" role="tablist">
                <span class="nav-link py-3 px-1 mr-4" :class="{ 'active': wMode==='all' }" id="tabAllPosts" role="tab"
                      @click.stop="wallPostsSelect(`all`)">Все записи</span>
                <span class="nav-link py-3 px-1 mr-4" :class="{ 'active': wMode==='user' }" id="tabMyPosts" role="tab"
                      @click.stop="wallPostsSelect(`user`)">Записи {{ firstName }}</span>
            </nav>

            <!-- TODO: @YZ реализовать после MVP -->
            <button class="btn btn-link mx-1 px-1 btn-add-file d-none" type="button">
                <IconSearch style="width: 15px; height: 16px;"/>
            </button>

            <div class="col-12 d-flex d-md-none  align-items-center form-inline mb-3 pl-0 pr-0 position-relative overflow-hidden rounded-pill mt-4">
                <div class="form-inline  position-relative w-100"
                     :class="{'isFocused' : isFocused}">
                    <input :value="lastSearch"
                           id="txtNewsListSearch"
                           ref="txtNewsListSearch"

                           @blur="onBlur"
                           @focus="onFocus"
                           class="top-search form-control form-control  w-100"
                           type="text" placeholder="Поиск" aria-label="Поиск" />

                    <button class="btn btn-search h-100" type="button">
                        <IconSearch style="width: 15px; height: 15px;"/>
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import IconSearch from '../icons/IconSearch.vue';

export default {
    name: 'ProfileFilter',
    components: {
        IconSearch
    },
    props: {
        firstName: {
            type: String,
            default: null,
        },
    },

    data() {
        return {
            wMode: `all`,
            isFocused: false,
            lastSearch : ''
        }
    },

    methods: {
        wallPostsSelect(wMode) {
            this.wMode = wMode;
            this.$emit('wallPostsSelect', {wMode: wMode});
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
