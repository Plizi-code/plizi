const ChatAdminMixin = {

computed: {
    meIsChatAdmin(){
        const admin = this.currentDialog.getAdmin();

        return !(!!admin);
    }
},

};

export {ChatAdminMixin as default}
