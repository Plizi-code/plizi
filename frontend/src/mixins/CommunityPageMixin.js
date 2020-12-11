import CommunityFriendsInformer from "../common/Communities/CommunityFriendsInformer.vue";
import CommunityShortMembers from "../common/Communities/CommunityShortMembers.vue";
import CommunityUserActionBlock from "../common/Communities/CommunityUserActionBlock.vue";
import PostVideoModal from "../common/Post/PostVideoModal.vue";
import Spinner from "../common/Spinner.vue";
import SmallSpinner from "../common/SmallSpinner.vue";

import CommunityHeader from "../components/Community/CommunityHeader.vue";
import CommunityVideoBlock from "../components/Community/CommunityVideoBlock.vue";

const CommunityPageMixin = {
    components: {
        PostVideoModal,
        CommunityVideoBlock,
        CommunityShortMembers,
        CommunityFriendsInformer,
        CommunityUserActionBlock,
        CommunityHeader,
        Spinner,
        SmallSpinner,
    },
    data() {
        return {
            isDataReady: false,
            communityData: null,
            postVideoModal: {
                isVisible: false,
                content: {
                    videoLink: null,
                },
            },
        }
    },
    methods: {
        openVideoModal(evData) {
            if (evData.videoLink) {
                this.postVideoModal.isVisible = true;
                this.postVideoModal.content.videoLink = evData.videoLink;
            }
        },
        hideVideoModal() {
            this.postVideoModal.isVisible = false;
        },
    }
};

export {CommunityPageMixin as default}
