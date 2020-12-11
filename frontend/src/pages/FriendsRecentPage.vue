<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-11 col-lg-9 col-xl-10 px-0 px-md-3 ">

                <FriendsListHeader></FriendsListHeader>

                <div class="d-flex flex-wrap align-items-start">
                    <div class="col-12 --order-1 --order-md-0 col-md-7 col-lg-8 col-xl-8 bg-white-br20">
                        <div v-if="isDataReady" class="plizi-search-results-list">
                            <div class="p-3">
                                <div class="alert alert-info text-center mb-0">
                                    Тут будет красивый список тех, с кем мы недавно подружились.
                                </div>
                            </div>
                        </div>
                        <Spinner v-else v-bind:clazz="`d-flex flex-row`"></Spinner>
                    </div>

                    <div class="col-12 col-md-5 col-lg-4 col-xl-4">
                        <PotentialFriends v-if="possibleFriends && possibleFriends.length"
                                          :blockName="`Возможные друзья`"
                                          :friends="shuffle(possibleFriends)"></PotentialFriends>
                        <PotentialFriends v-if="recommendedFriends && recommendedFriends.length"
                                          :blockName="`Рекомендуемые друзья`"
                                          :friends="shuffle(recommendedFriends)"></PotentialFriends>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-xl-1 d-none d-lg-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
import FriendsListMixin from '../mixins/FriendsListMixin.js';

import InvitationItem from '../components/InvitationItem.vue';

export default {
name: 'FriendsRecentPage',
components: {
    InvitationItem
},
mixins : [FriendsListMixin],
data() {
    return {
        isDataReady : true
    }
},

}
</script>
