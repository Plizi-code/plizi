<template>
    <div class="plz-import-socnet form-group text-center mt-4">
        <h6 class="mb-3">Импорт аккаунта</h6>
        <div class="plz-import-socnet-text  mb-3">Импортируйте свой аккаунт из списка следующих социальных сетей:</div>
        <div class="plz-import-socnet-btns d-flex justify-content-center">
            <div class="mx-3 d-none">
                <!-- TODO: @YZ доработать когда появятся api ключи -->
                <a href="#twitter"
                   title="Twitter"
                   class="plz-socnet-btn"
                   @click.prevent="loginWithSocial('twitter')">
                    <i class="fab fa-twitter fa-2x mt-2"></i>
                </a>
            </div>
            <div class="mx-3">
                <a href="#vk"
                   title="VKontakte"
                   class="plz-socnet-btn"
                   @click.prevent="loginWithSocial('vkontakte')">
                    <i class="fab fa-vk fa-2x mt-2"></i>
                </a>
            </div>
            <div class="mx-3">
                <a href="#fb"
                   title="Facebook"
                   class="plz-socnet-btn"
                   @click.prevent="loginWithSocial('facebook')">
                    <i class="fab fa-facebook-f fa-2x mt-2"></i>
                </a>
            </div>
            <div class="mx-3 d-none">
                <!-- TODO: @YZ нужно много времени -->
                <a href="#instagram"
                   title="Instagram"
                   class="plz-socnet-btn"
                   @click.prevent="loginWithSocial('instagram')">
                    <i class="fab fa-instagram fa-2x mt-2"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import client_ids from "../../libs/social_networks_client_ids";

    export default {
        name: "LoginSocialLinks",
        methods: {
            loginWithSocial(provider) {
                switch (provider) {
                    case 'facebook':
                        this.socialFacebook(provider);
                        break;
                    case 'instagram':
                        this.socialInstagram(provider);
                        break;
                    case 'vkontakte':
                        this.socialVK(provider);
                        break
                }
            },
            socialFacebook(provider) {
                let self = this;

                FB.getLoginStatus(function (response) {
                    if (!response.authResponse) {
                        FB.login(function (response) {
                            if (response.authResponse) {
                                self.saveToken(provider, response.authResponse.accessToken);
                            }
                        });
                    } else {
                        self.saveToken(provider, response.authResponse.accessToken);
                    }
                });
            },
            socialInstagram(provider) {
                let url = {
                    domain: "https://api.instagram.com/oauth/authorize",
                    client_id: `client_id=${client_ids.instagram}`,
                    scope: "scope=user_profile",
                    response_type: "response_type=code",
                    state: 'state=instagram',
                };

                window.location.href = `${url.domain}?${url.client_id}&redirect_uri=${client_ids.redirect_uri}&${url.scope}&${url.response_type}&${url.state}`;
            },
            socialVK(provider) {
                let url = {
                    domain: "https://oauth.vk.com/authorize",
                    client_id: `client_id=${client_ids.vk}`,
                    scope: `scope=offline`,
                    display: "display=page",
                    response_type: "response_type=token",
                    state: "state=vk",
                };

                window.location.href = `${url.domain}?${url.client_id}&${url.scope}&redirect_uri=${client_ids.redirect_uri}&${url.display}&${url.response_type}&${url.state}`;
            },
            getInstagramToken(params) {
                let code = params.get("code");

                if (code) {
                    this.saveToken('instagram', code);
                }
            },
            getVKToken(params) {
                let access_token = params.get("access_token");

                if (access_token) {
                    this.saveToken('vkontakte', access_token);
                }
            },
            async saveToken(provider, token) {
                let response;

                try {
                    response = await this.$root.$api.registerThroughSocialServices(provider, token);
                } catch (e) {
                    console.warn(e.detailMessage);
                }

              if (response && response && response.token) {
                    this.$root.$emit('afterSuccessLogin', {
                        token: response.token,
                        chatChannel: response.channel,
                        redirect: true
                    });
                }
            },
        },
        mounted() {
            let lochash = location.hash.substr(1);
            let params = new URLSearchParams(lochash);
            let state = params.get('state');

            if (!state) {
                params = new URLSearchParams(location.search);
                state = params.get('state');
            }

            switch (state) {
                case 'instagram':
                    this.getInstagramToken(params);
                    break;
                case 'vk':
                    this.getVKToken(params);
                    break;
            }
        },
    }
</script>

