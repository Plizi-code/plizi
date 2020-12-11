<template>
    <div class="d-flex d-md-block">
        <HotCommunityItem v-for="hcItem in communities"
                          @RemoveCommunityFromHots="onRemoveCommunityFromHots"
                          v-bind:community="hcItem"
                          v-bind:key="'hcItem-'+hcItem.id+'-'+keyUpdater"></HotCommunityItem>
    </div>
</template>

<script>
import HotCommunityItem from './Communities/HotCommunityItem.vue'

export default {
name : 'HotCommunities',
components : { HotCommunityItem },
props : {
    communities : Array,
    keyUpdater: Number
},

methods: {
    onRemoveCommunityFromHots(evData){
        this.$emit('RemoveCommunityFromHots', evData);
    }
}

}
</script>
