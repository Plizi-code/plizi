<template>
    <div id="createCommunityBlock" class="col-md-12 col-lg-4 col-xl-3 mb-4  d-flex pl-lg-0">
        <div class="col-12  d-flex align-items-center align-items-center  bg-white-br20 ">
            <div class="w-100 py-3 py-lg-0">
                <button type="button"
                        @click="ShowCommunityCreateModal"
                        class="btn btn-create btn-block btn-primary rounded-pill ">
                    Создать сообщество
                </button>
                <CommunityCreateModal v-if="communityCreateModalShow"
                         @HideCommunityCreateModal="onHideCommunityCreateModal">
                </CommunityCreateModal>
            </div>
        </div>
    </div>
</template>

<script>
import CommunityCreateModal from './CommunityCreateModal.vue';

export default {
name: 'CommunityCreateBlock',
components: {
    CommunityCreateModal
},
data(){
    return {
        communityCreateModalShow : false
    }
},

methods: {
    ShowCommunityCreateModal(){
        this.communityCreateModalShow = true;
    },

    onHideCommunityCreateModal(){
        this.communityCreateModalShow = false;
    }
}

}
</script>
