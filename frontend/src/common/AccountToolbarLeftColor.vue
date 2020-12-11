<template>
    <div id="hotCommunities" class="hot-communities d-flex d-md-block">
        <button v-if="isCanAddToFavorites()" type="button" v-bind:key="'btnAddToHot'+$root.$communitiesKeyUpdater"
                @click="onAddCommunityToHot"
                class="add-new btn btn-link text-center mx-sm-auto mr-1 mb-4 px-0 py-0">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>

        <HotCommunities v-if="isDataReady"
                        v-bind:communities="hotCommunitiesList(keyUpdater)"
                        v-bind:key="'hotCommunitiesBlock-'+keyUpdater"
                        v-bind:keyUpdater="keyUpdater"
                        @AddCommunityToHot="onAddCommunityToHot"
                        @RemoveCommunityFromHots="onRemoveCommunityFromHots"
                        ref="hotCommunitiesBlock"></HotCommunities>
        <Spinner v-else clazz="plz-favorit-friends-spinner d-flex flex-column align-items-center"></Spinner>
    </div>
</template>

<script>
import Spinner from '../common/Spinner.vue';
import HotCommunities from './HotCommunities.vue';

import HotCommunitiesMixin from '../mixins/HotCommunitiesMixin.js';

/**
 * TODO: @TGA после MVP переимновать этот компонент в HotCommunities
 */
export default {
name: 'AccountToolbarLeftColor',
components : { HotCommunities, Spinner},
mixins: [HotCommunitiesMixin],

data() {
    return {
        isDataReady : false,
        keyUpdater: 0
    }
},

methods: {
    isCanAddToFavorites(){
        const isCan = this.$root.$auth.cm.isCanAddToFavorites(this.$route.params.id);

        return this.isDataReady &&  (this.$root.$router.currentRoute.name === 'CommunityPage')  && isCan;
    },

    hotCommunitiesList(parasm){
        return this.$root.$auth.cm.asArray().slice();
    },

    onRemoveCommunityFromHots(evData){
        this.keyUpdater++;
        this.removeCommunityFromFavorites( evData.id, evData.community );

        if (this.$refs  &&  this.$refs.hotCommunitiesBlock) {
            this.$refs.hotCommunitiesBlock.$forceUpdate();
        }
    },

    onAddCommunityToHot(){
        this.$root.$emit('NeedAddCommunityToHot', { communityId : this.$route.params.id });
    },

    afterFavoritsLoad(param1){
        this.$root.$communitiesKeyUpdater++;
        this.keyUpdater++;
        this.showFavoritesBlock = (this.$root.$auth.cm.size > 0);
        this.isDataReady = true;
    }
},

created(){
    if (this.$root.$auth.cm.isLoad) {
        this.afterFavoritsLoad(`this.$root.$auth.cm.isLoad`);
    }

    this.$root.$on(this.$root.$auth.cm.loadEventName, ()=>{
        this.afterFavoritsLoad(this.$root.$auth.cm.loadEventName);
    });

    this.$root.$on(this.$root.$auth.cm.restoreEventName, ()=>{
        this.afterFavoritsLoad(this.$root.$auth.cm.restoreEventName);
    });

    this.$root.$on(this.$root.$auth.cm.updateEventName, ()=>{
        if (this.$refs  &&  this.$refs.hotCommunitiesBlock) {
            this.$root.$communitiesKeyUpdater++;
            this.keyUpdater++;
            this.$refs.hotCommunitiesBlock.$forceUpdate();
        }
    });
},

beforeDestroy() {
    this.$root.$off(this.$root.$auth.cm.loadEventName, ()=>{});
    this.$root.$off(this.$root.$auth.cm.restoreEventName, ()=>{});
    this.$root.$off(this.$root.$auth.cm.updateEventName, ()=>{});
}


}
</script>
