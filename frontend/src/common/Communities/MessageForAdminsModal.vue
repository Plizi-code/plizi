<template>
    <div class="modal show" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true"
         style="display: block; background-color: rgba(0, 0, 0, .7);" @click.stop="hideAdminMsgModal">
        <div class="modal-dialog modal-dialog-centered" role="document" @click.stop="">
            <div class="modal-content bg-white-br20">

                <div class="modal-header">
                    <h4 class="modal-title p-2">Для администрации</h4>
                </div>

                <div class="modal-body">
                    <div class="user-friend d-flex">
                        <div class="user-friend-pic mr-3 ">
                            <img class="user-friend-img rounded-circle overflow-hidden" v-bind:src="community.primaryImage" v-bind:alt="community.name" />
                        </div>

                        <div class=" user-friend-body m-0 col-12 pr-5 ">
                            <div class="user-friend-body-top d-flex align-items-end justify-content-between">
                                <h6 class="user-friend-name my-0">{{ community.name }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="form mt-3">
                        <TextEditor :id="`messageToCommunityAdmins`"
                                    ref="messageToCommunityAdmins"
                                    workMode="adminMessage"
                                    :showAvatar="false"
                                    :dropToDown="true"
                                    :clazz="`row plz-text-editor mb-4 pl-2 h-auto align-items-start`"
                                    :inModal="true"
                                    @editorPost="startAdminsMessage">
                        </TextEditor>
                    </div>

                    <div class="form-group row mb-0 pt-3 border-top">
                        <div class="col-12">
                            <button type="button" class="btn plz-btn plz-btn-primary" @click.prevent="startAdminsMessage()">Отправить</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextEditor from '../../common/TextEditor.vue';

import DialogMixin from '../../mixins/DialogMixin.js';

import PliziCommunity from '../../classes/PliziCommunity.js';

export default {
name: 'MessageForAdminsModal',
props: {
    community: PliziCommunity
},
components: { TextEditor },
mixins: [DialogMixin],

methods: {
    hideAdminMsgModal() {
        this.$emit('HideAdminMsgModal', {});
    },

    startAdminsMessage(evData){
        if (!evData) {
            evData = this.$refs.messageToCommunityAdmins.getContent();
        }

        if (evData.postText===''  ||  evData.postText==='<p></p>')
            return;

        this.$emit('SendAdminMessage', {
            message: evData,
        });
    },
},

mounted(){
    setTimeout(()=>{
        this.$refs.messageToCommunityAdmins.focus();
    }, 100);
}

}
</script>
