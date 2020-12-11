import Vuex from 'vuex';
import Vue from 'vue';

Vue.use(Vuex);

/**
 * @TGA: 2020-05-01 пока мы Vuex не используем
 */
const store__ = new Vuex.Store({
    state: {
        gwToken : ``,
        chatChannel : ``,
        lastSearch : ``,
        activeDialog: -1,
    },

    getters : {
        isAuth : state => {
            return state.isAuth;
        },

        activeDialog : state => {
            let acId = state.activeDialog;

            if (!acId  ||  acId<=0) {
                acId = window.localStorage.getItem('pliziActiveDialog')+'';
                acId = acId >>> 0;
            }

            if (acId >= 0) {
                state.activeDialog = acId;
                return acId;
            }

            return state.activeDialog;
        },


        /**
         * @param state
         * @returns {string}
         */
        gwToken : state => {
            let gwt = state.gwToken;

            if (!gwt  ||  ``===gwt) {
                gwt = window.localStorage.getItem('pliziJWToken');
            }

            if (gwt !== ``) {
                state.gwToken = gwt;
                return gwt;
            }

            return state.gwToken;
        },

        lastSearch : state => {
            let lst = state.lastSearch;

            if (!lst  ||  ``===lst) {
                lst = window.localStorage.getItem('pliziLastSearch');
            }

            if (lst  &&  lst !== ``) {
                state.lastSearch = lst;
                return lst;
            }

            return state.lastSearch;
        },


        chatChannel : state => {
            let cnl = state.chatChannel;

            if (!cnl  ||  ``===cnl) {
                cnl = window.localStorage.getItem('pliziChatChannel');
            }

            if (cnl !== ``) {
                state.chatChannel = cnl;
                return cnl;
            }

            return state.chatChannel;
        }

    },

    // setters
    mutations: {
        SET_GWT : (state, payload) => {
            state.gwToken = payload;
            window.localStorage.setItem('pliziJWToken', payload);
        },

        SET_LAST_SEARCH : (state, payload) => {
            state.lastSearch = payload;
            window.localStorage.setItem('pliziLastSearch', payload);
        },

        SET_CHAT_CHANNEL: (state, payload) => {
            state.chatChannel = payload;
            window.localStorage.setItem('pliziChatChannel', payload);
        },

        SET_ACTIVE_DIALOG: (state, payload) => {
            if (payload) {
                state.activeDialog = payload;
                window.localStorage.setItem('pliziActiveDialog', payload);
            }
        },
    },

    actions: {
        SET_GWT : (context, payload) => {
            context.commit('SET_GWT', payload);
        },

        SET_LAST_SEARCH : (context, payload) => {
            context.commit('SET_LAST_SEARCH', payload);
        },

        SET_CHAT_CHANNEL : (context, payload) => {
            context.commit('SET_CHAT_CHANNEL', payload);
        },

        SET_ACTIVE_DIALOG : (context, payload) => {
            context.commit('SET_ACTIVE_DIALOG', payload);
        },
    },
});

export default store__;
