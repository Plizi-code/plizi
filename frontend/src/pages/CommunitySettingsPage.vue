<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-xl-8 " v-if="isDataReady">
                <CommunitySettingsMain :community="communityData"></CommunitySettingsMain>
                <CommunitySettingsAdditional :community="communityData"></CommunitySettingsAdditional>
            </div>
            <div class="col-12 col-md-9 col-xl-8 " v-else>
                <Spinner></Spinner>
            </div>

            <div class="d-none d-xl-block col-sm-2 col-md-2 col-lg-2 col-xl-2">
                <CommunitySettingsSideMenu :id="id"></CommunitySettingsSideMenu>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0">
                <FavoriteFriends :isNarrow="true"></FavoriteFriends>
            </div>
        </div>
    </div>
</template>

<script>
    import AccountToolbarLeft from '../common/AccountToolbarLeft.vue';
    import FavoriteFriends from '../common/FavoriteFriends.vue';

    import AccountSettingsMain from '../components/AccountSettings/AccountSettingsMain.vue';
    import AccountSettingsPrivacy from '../components/AccountSettings/AccountSettingsPrivacy.vue';
    import AccountSettingsSecurity from '../components/AccountSettings/AccountSettingsSecurity.vue';

    import AccountSettingsSideMenu from '../components/AccountSettings/AccountSettingsSideMenu.vue';
    import PliziCommunity from "../classes/PliziCommunity";
    import CommunitySettingsMain from "../components/CommunitySettings/CommunitySettingsMain";
    import Spinner from "../common/Spinner";
    import CommunitySettingsSideMenu from "../components/CommunitySettings/CommunitySettingsSideMenu";
    import CommunitySettingsAdditional from "../components/CommunitySettings/CommunitySettingsAdditional";

    export default {
        name: 'CommunitySettingsPage',
        props: {
            id: Number | String
        },
        components: {
            CommunitySettingsAdditional,
            CommunitySettingsSideMenu,
            Spinner,
            CommunitySettingsMain,
            AccountToolbarLeft,
            AccountSettingsMain, AccountSettingsPrivacy, AccountSettingsSecurity,
            AccountSettingsSideMenu,
            FavoriteFriends
        },
        data() {
            return {
                isDataReady: false,
                communityData: null,
            }
        },
        methods: {
            async getCommunityInfo() {
                let apiResponse = null;
                this.isDataReady = false;

                try {
                    apiResponse = await this.$root.$api.$communities.getCommunity(this.id);
                } catch (e) {
                    window.console.warn(e.detailMessage);
                    throw e;
                }

                if (apiResponse) {
                    this.communityData = new PliziCommunity(apiResponse);
                    this.isDataReady = true;
                }
            },
        },
        mounted() {
            this.getCommunityInfo();
        }
    }
</script>

