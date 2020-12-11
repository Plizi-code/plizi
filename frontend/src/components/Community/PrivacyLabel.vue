<template>
    <p class="plizi-community-privacy-label mb-0"
       :class="{'text-red' : privacyLabel == 'Закрытое',
                'text-blue' : privacyLabel == 'Частное', }">
        {{privacyLabel}} сообщество
    </p>
</template>

<script>
    import PliziCommunity from '../../classes/PliziCommunity.js';
    import communityUtils from "../../utils/CommunityUtils"

    export default {
        name: "PrivacyLabel",
        props: {
            community: PliziCommunity
        },
        computed: {
            privacyLabel() {
                return communityUtils.getPrivacyLabel(this.community?.privacy);
            },
        }
    }
</script>
