import VueRouter from 'vue-router';

import HomePage from '../pages/HomePage.vue';
import LoginPage from '../pages/LoginPage.vue';
import UpdatePasswordPage from '../pages/UpdatePasswordPage';
import LogoutPage from '../pages/LogoutPage.vue';

import NewsPage from '../pages/NewsPage.vue';
import ProfilePage from '../pages/ProfilePage.vue';
import PersonalPage from '../pages/PersonalPage.vue';
import AccountPage from '../pages/AccountPage.vue';
import BlackListPage from "../pages/BlackListPage.vue";
import ChatsListPage from '../pages/ChatsListPage.vue';
import SearchResultsPage from '../pages/SearchResultsPage.vue';
import FriendsListPage from '../pages/FriendsListPage.vue';
import InvitationsPage from '../pages/InvitationsPage.vue';
import FriendsRecentPage from '../pages/FriendsRecentPage.vue';
import NotificationsPage from '../pages/NotificationsPage.vue';

import CommunitiesListPage from '../pages/CommunitiesListPage.vue';
import CommunitiesManagePage from '../pages/CommunitiesManagePage.vue';
import CommunitiesPopularPage from '../pages/CommunitiesPopularPage.vue';
import CommunityPage from '../pages/CommunityPage.vue';
import Error404Page from '../pages/Error404Page.vue';
import Guest404Page from '../pages/Guest404Page.vue';
import CommunityRulesPage from "../pages/CommunityRulesPage.vue";
import PhotoalbumsListPage from "../pages/PhotoalbumsListPage.vue";
import PhotoalbumPage from "../pages/PhotoalbumPage.vue";
import VideosPage from "../pages/VideosPage.vue";
import CommunityFriendsPage from "../pages/CommunityFriendsPage.vue";
import CommunitySettingsPage from "../pages/CommunitySettingsPage.vue";
import CommunityRequestsPage from "../pages/CommunityRequestsPage.vue";
//import AboutPage from "../pages/AboutPage.vue";
//import RulesPage from "../pages/RulesPage.vue";
//import AdvertisementPage from "../pages/AdvertisementPage.vue";
//import ForDevelopersPage from "../pages/ForDevelopersPage.vue";
import FollowListPage from "../pages/FollowListPage.vue";
import ActiveSessionsPage from "../pages/ActiveSessionsPage.vue";
import GuestSearchResultsPage from "../pages/GuestSearchResultsPage";
import CommunityMembersPage from "../pages/CommunityMembersPage.vue";
import CommunityVideosPage from "../pages/CommunityVideosPage.vue";

import UserFriendsAllList from "../components/UserFriendsAllList.vue";
import ProfilePhotos from "../components/ProfilePhotos.vue";
import UserPosts from "../components/UserPosts.vue";
import UserPhotoalbumsList from "../components/UserPhotoalbumsList.vue";
import UserPhotoalbum from "../components/UserPhotoalbum.vue";
import CommunityTemplate from "../components/CommunityTemplate.vue";
import CommunityVideoList from "../components/CommunityVideoList.vue";
import FollowersList from "../components/FollowersList.vue";

const routes = [
    {path: '/', redirect: '/login', isGuest: true},
    {path: '/', component: HomePage, name: 'HomePage', meta: {title: 'Plizi: Стартовая', isGuest: true}},
    {path: '/guest-search-results', component: GuestSearchResultsPage, name: 'GuestSearchResultsPage', meta: {title: 'Plizi: Результаты поиска', isGuest: true}},
    {path: '/login', component: LoginPage, name: 'LoginPage', meta: {title: 'Plizi: Авторизация', isGuest: true}},
    {path: '/logout', component: LogoutPage, name: 'LogoutPage', meta: {title: 'Plizi: Выход', isGuest: true}},
    {path: '/password/update', component: UpdatePasswordPage, name: 'UpdatePasswordPage', meta: {title: 'Plizi: Обновление пароля', props: true, isGuest: true}},
    // {path: '/about', component: AboutPage, name: 'AboutPage', meta: {title: 'Plizi: О проекте', isGuest: true}},
    // {path: '/rules', component: RulesPage, name: 'RulesPage', meta: {title: 'Plizi: Правила', isGuest: true}},
    // {path: '/advertisement', component: AdvertisementPage, name: 'AdvertisementPage', meta: {title: 'Plizi: Реклама', isGuest: true}},
    // {path: '/for-developers', component: ForDevelopersPage, name: 'ForDevelopersPage', meta: {title: 'Plizi: Для разработчиков', isGuest: true}},

/** Auth ******************************************************************************************************** **/
    {path: '/account', component: AccountPage, name: 'AccountPage', meta: {title: 'Plizi: Настройки аккаунта'}, props: true },
    {path: '/black-list', component: BlackListPage, name: 'BlackList', meta: {title: 'Plizi: Черный список'}, props: true },
    {path: '/profile', component: ProfilePage, name: 'ProfilePage', meta: {title: 'Plizi: Домашняя'}, props: true},
    {path: '/chats', component: ChatsListPage, name: 'ChatsListPage', meta: {title: 'Plizi: Чаты'}, props: true},
    {path: '/search-results', component: SearchResultsPage, name: 'SearchResultsPage', meta: {title: 'Plizi: Результаты поиска'}, props: true },

    {path: '/user-:id', component: resolve => resolve(PersonalPage), name: 'PersonalPage', meta: {title: 'Plizi:'}, props: true,
        children: [
            {
                path: '', meta: {title: 'Plizi: '}, name: 'PersonalPage', components: {
                    userLastPhotos: ProfilePhotos,
                    userLastPost: UserPosts,
                },
            } ,
            { path: 'friends', components: { userFriendsList : UserFriendsAllList },  meta: {title: 'Plizi: '} },
            { path: 'communities', components: { userCommunities : CommunityTemplate }, name: 'userCommunities', meta: {title: 'Plizi: '} },
            { path: 'albums', components: { userPhotoalbums : UserPhotoalbumsList }, name: 'userPhotoalbums', meta: {title: 'Plizi: '} },
            { path: '/user-:id/album-:albumId', components: { userPhotoalbum : UserPhotoalbum }, name: 'userPhotoalbum', meta: {title: 'Plizi: '} },
            { path: 'videos', components: { userVideoList : CommunityVideoList }, name: 'userVideoList', meta: {title: 'Plizi: '} },
            { path: 'followers', components: { followersList : FollowersList }, name: 'followersList', meta: {title: 'Plizi: '} },
        ]},

    {path: '/friends', component: FriendsListPage, name: 'FriendsListPage', meta: {title: 'Plizi: мои друзья'}, props: true },
    {path: '/invitations', component: InvitationsPage, name: 'InvitationsPage', meta: {title: 'Plizi: приглашения дружбы'}, props: true },
    {path: '/friends-recent', component: FriendsRecentPage, name: 'FriendsRecentPage', meta: {title: 'Plizi: новые друзья'}, props: true },
    {path: '/follow-list', component: FollowListPage, name: 'FollowListPage', meta: {title: 'Plizi: на кого я подписан'}, props: true },
    {path: '/notifications', component: NotificationsPage, name: 'NotificationsPage', meta: {title: 'Plizi: напоминания'}, props: true },
    {path: '/news', component: NewsPage, name: 'NewsPage', meta: {title: 'Plizi: Новости', props: true}},

    {path: '/communities', component: CommunitiesListPage, name: 'CommunitiesListPage', meta: {title: 'Plizi: Мои сообщества'}, props: true },
    {path: '/community-rules', component: CommunityRulesPage, name: 'CommunityRulesPage', meta: {title: 'Plizi: Правила сообществ'}, props: true },
    {path: '/manage-communities', component: CommunitiesManagePage, name: 'CommunitiesManagePage', meta: {title: 'Plizi: Управление сообществами'}, props: true },
    {path: '/community-settings-:id', component: CommunitySettingsPage, name: 'CommunitySettingsPage', meta: {title: 'Plizi: Управление сообществом'}, props: true },
    {path: '/community-videos-:id', component: CommunityVideosPage, name: 'CommunityVideosPage', meta: {title: 'Plizi: Видео сообщества'}, props: true },
    {path: '/members-:id', component: CommunityMembersPage, name: 'CommunityMembersPage', meta: {title: 'Plizi: Участники сообщества'}, props: true },
    {path: '/popular-communities', component: CommunitiesPopularPage, name: 'CommunitiesPopularPage', meta: {title: 'Plizi: Популярные сообщества'}, props: true },
    {path: '/community-friends-:id', component: CommunityFriendsPage, name: 'CommunityFriendsPage', meta: {title: 'Plizi: Популярные сообщества'}, props: true },
    {path: '/community-requests-:id', component: CommunityRequestsPage, name: 'CommunityRequestsPage', meta: {title: 'Plizi: Запросы на вытупление в сообщество'}, props: true },
    {path: '/community-:id', component: CommunityPage, name: 'CommunityPage', meta: {title: 'Plizi: Популярные сообщества'}, props: true },
    {path: '*', component: Error404Page, name: 'Error404Page', meta: {title: 'Page not found', isNotFound: true} },
    {path: '/404', component: Guest404Page, name: 'Guest404Page', meta: {title: 'Page not found', isNotFound: true} },
    {path: '/photoalbums-list', component: PhotoalbumsListPage, name: 'PhotoalbumsListPage', meta: {title: 'Plizi: Фотоальбомы'}, props: true},
    {path: '/photoalbum-:id', component: PhotoalbumPage, name: 'PhotoalbumPage', meta: {title: 'Plizi: Фотоальбом'}, props: true},
    {path: '/videos', component: VideosPage, name: 'VideosPage', meta: {title: 'Plizi: Видео'}, props: true },
    {path: '/active-sessions', component: ActiveSessionsPage, name: 'ActiveSessionsPage', meta: {title: 'Plizi: Активные сессии'}, props: true },
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: routes
});


function routerForcedLogout(next, to) {
    window.localStorage.removeItem('pliziJWToken');
    window.localStorage.removeItem('pliziUser');
    window.localStorage.removeItem('pliziChatChannel');
    window.localStorage.removeItem('pliziLastSearch');
    window.localStorage.removeItem('pliziFriends');
    window.localStorage.removeItem('pliziFavorites');
    window.localStorage.removeItem('pliziDialogs');
    window.localStorage.removeItem('pliziInvitations');
    window.localStorage.removeItem('pliziNotifications');

    window.localStorage.setItem('pliziRedirectTo', to.path);

    document.body.className = 'LoginPage';

    next({path: '/login', component: LoginPage, name: 'LoginPage'});
}


/**
 *
 * @param {Route} to
 * @param next
 */
function updateTitle(to, next){
    document.title = to.meta.title;
    next();

    document.body.className = to.name;
}


/**
 * @param {Route} to
 * @param {Route} from
 * @param next
 * @returns {Promise<void>}
 */
async function checkRouteAuth(to, from, next) {
    if (to.meta.isGuest)
        return updateTitle(to, next);

    await Vue.nextTick(); /** @TGA иначе загрузка из localStorage не срабатывает **/

    const gwt = window.localStorage.getItem('pliziJWToken');

    if ((gwt + '') !== 'null' && gwt !== '') {
        const tstUserData = window.app.$root.$auth.restoreData();

        if (tstUserData) {
            window.app.$root.$emit('AfterUserRestore', {
                user: tstUserData,
                token: gwt,
                save: true
            });
        }
        else {
            let tryToLoadUser = null;
            try {
                tryToLoadUser = await window.app.$root.$api.$users.getUser();
            }
            catch (e) {
                window.app.$root.$alert(`<p class="text-white">Извините, но на сервере произошла ошибка и войти не получится.<br />Попробуйте через некоторое время.</p>`, 'bg-danger', 10);

                return routerForcedLogout(next, to);
            }

            if (tryToLoadUser) {
                window.app.$root.$emit('AfterUserLoad', {
                    user: tryToLoadUser,
                    token: gwt,
                    save: true
                });
            }
            else {
                return routerForcedLogout(next, to);
            }
        }
    }
    else {
        if (!to.meta.isNotFound) {
            return routerForcedLogout(next, to);
        }
    }

    updateTitle(to, next);
}


router.beforeEach(checkRouteAuth);

export default router;
