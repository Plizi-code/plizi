<template>
    <div class="container-fluid pl-md-0">
        <div class="row">
            <div class="col-12 col-md-1 px-0 px-md-3 ">
                <AccountToolbarLeft></AccountToolbarLeft>
            </div>

            <div class="col-12 col-md-9 col-xl-8  px-0 px-md-3">
                <AccountSettingsMain></AccountSettingsMain>
                <AccountSettingsPrivacy></AccountSettingsPrivacy>
                <AccountSettingsSecurity></AccountSettingsSecurity>
            </div>

            <div class="d-none d-xl-block col-sm-2 col-md-2 col-lg-2 col-xl-2  px-0 px-md-3">
                <AccountSettingsSideMenu></AccountSettingsSideMenu>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block pr-0  px-0 px-md-3">
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

export default {
    name: 'AccountPage',
    components: {
        AccountToolbarLeft,
        AccountSettingsMain, AccountSettingsPrivacy, AccountSettingsSecurity,
        AccountSettingsSideMenu,
        FavoriteFriends
    },
    data() {
        return {}
    },
    methods: {},
}
</script>

