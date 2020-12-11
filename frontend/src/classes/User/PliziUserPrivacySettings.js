class PliziUserPrivacySettings {
    /**
     * Тип страницы.
     * @type {number|null}
     * @private
     */
    _pageType = null;

    /**
     * Разрешение на отправку сообщений пользователю.
     * @type {number|null}
     * @private
     */
    _writeMessagesPermissions = null;

    /**
     * Разрешение публиковать посты на стене пользователя.
     * @type {number|null}
     * @private
     */
    _postWallPermissions = null;

    /**
     * Разрешение на просмотр стены пользователя.
     * @type {number|null}
     * @private
     */
    _viewWallPermissions = null;

    /**
     * Разрешение на просмотр друзей пользователя.
     * @type {number|null}
     * @private
     */
    _viewFriendsPermissions = null;

    /**
     * Двухфакторная аутентификация.
     * @type {number|null}
     * @private
     */
    _twoFactorAuthEnabled = null;

    /**
     * СМС подтверждение.
     * @type {number|null}
     * @private
     */
    _smsConfirm = null;

    get pageType() {
        return this._pageType;
    }

    get writeMessagesPermissions() {
        return this._writeMessagesPermissions;
    }

    get postWallPermissions() {
        return this._postWallPermissions;
    }

    get viewWallPermissions() {
        return this._viewWallPermissions;
    }

    get viewFriendsPermissions() {
        return this._viewFriendsPermissions;
    }

    get twoFactorAuthEnabled() {
        return this._twoFactorAuthEnabled;
    }

    get smsConfirm() {
        return this._smsConfirm;
    }

    /**
     * @param {object} permissions
     */
    constructor(permissions) {
        if (permissions) {
            this.update(permissions);
        }
    }

    update(permissions){
        this._pageType = permissions.pageType;
        this._writeMessagesPermissions = permissions.writeMessagesPermissions;
        this._postWallPermissions = permissions.postWallPermissions;
        this._viewWallPermissions = permissions.viewWallPermissions ;
        this._viewFriendsPermissions = permissions.viewFriendsPermissions;
        this._twoFactorAuthEnabled = permissions.twoFactorAuthEnabled;
        this._smsConfirm = permissions.smsConfirm;
    }

    toJSON() {
        return {
            pageType: this.pageType,
            writeMessagesPermissions: this.writeMessagesPermissions,
            postWallPermissions: this.postWallPermissions,
            viewWallPermissions: this.viewWallPermissions,
            viewFriendsPermissions: this.viewFriendsPermissions,
            twoFactorAuthEnabled: this.twoFactorAuthEnabled,
            smsConfirm: this.smsConfirm,
        }
    }
}

export default PliziUserPrivacySettings;
