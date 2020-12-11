<template>
    <router-link tag="a" :to="`community-`+community.id"
                 class="d-inline-block d-sm-block text-center mx-2 mx-md-auto mb-2 mb-md-4 ">

        <button class="btn-remove-from-hot" aria-label="удалить" @click.stop.prevent="onRemoveCommunityFromHot(community.id)">
            <i class="fa fa-plus"></i>
        </button>

        <img class="mx-auto" :src="community.primaryImage" :alt="community.name" />
    </router-link>
</template>

<script>
import PliziCommunity from '../../classes/PliziCommunity.js';

export default {
name : 'HotCommunityItem',
props : {
    community : PliziCommunity
},

methods: {
    onRemoveCommunityFromHot(communityId){
        this.$emit('RemoveCommunityFromHots', {
            id: communityId,
            community: this.community
        });
    }
}

}
</script>
