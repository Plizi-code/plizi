<template>
    <div id="accountSettingsSideMenu" class="bg-white-br20">
        <div class="list-group border-0 py-3">
            <router-link tag="a" :to="menuItem.path"
                         v-for="(menuItem, menuIndex) in menuItems"
                         :key="menuIndex"
                         class="list-group-item list-group-item-action border-0 py-2"
                         :class="{'active' : menuItem.isActive}"
                         @click.native="anchorHashCheck">
                {{menuItem.title}}
            </router-link>
<!--            <hr>-->
            <router-link tag="a" :to="{name: 'CommunityPage', params: {id: id}}"
                         class="list-group-item list-group-item-action border-0 py-2">
                <i class="fa fa-share mr-2"> </i>Назад
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'CommunityRequestsSideMenu',
        props: {
            id: Number | String
        },
        data() {
            return {
                menuItems: [
                    // {path: '#communitySettingsMain', title: 'Основная информация', isActive: true},
                    // {path: '#communitySettingsAdditional', title: 'Дополнительная информация', isActive: false},
                ],
            }
        },

        methods: {
            anchorHashCheck() {
                if (window.location.hash === this.$route.hash) {
                    const el = document.getElementById(this.$route.hash.slice(1));

                    if (el) {
                        window.scrollTo(0, el.offsetTop);

                        this.menuItems.map((menuItem) => {
                            menuItem.isActive = menuItem.path === this.$route.hash;
                        });
                    }
                }
            },
        }

    }
</script>
