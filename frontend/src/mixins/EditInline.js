import {debounce} from "../utils/Debonce.js";

/**
 * Пример использования смотри в "CommunitySettingsMain.vue"
 * @type {{methods: {startFieldEdit(*=): void, inputFieldEdit(*, *): void, getRef(*): (*|null), finishFieldEdit(*=): (undefined), formatFormData(*, *): {}, clickField: (function(...[*]=))}}}
 */

const EditInline = {
    methods: {
        getRef(refKey) {
            for (let [key, value] of Object.entries(this.$refs)) {
                if (refKey === key)
                    return value;
            }
            return null;
        },
        clickField: debounce(function (fieldName) {
            this.serverRegMessages[fieldName] = null;
            this.isEdit[fieldName] = !this.isEdit[fieldName];
            console.log(fieldName, this.isEdit, 'clickField');
            if (this.isEdit[fieldName]) {
                this.startFieldEdit(fieldName);
            } else {
                this.finishFieldEdit(fieldName);
            }
        }, 200),
        startFieldEdit(fieldName) {
            const inpRef = this.getRef(fieldName);

            if (inpRef) {
                inpRef.focus();
            } else {
                window.console.warn('Ошибка редактирования поля', 's', fieldName);
            }
        },
        finishFieldEdit (fieldName) {
            this.$v.model[fieldName].$touch();
            const inpRef = this.getRef(fieldName);
            if (this.$v.model[fieldName].$error) {
                this.isEdit[fieldName] =true;
                return;
            }
            setTimeout(() => {
                if (inpRef) {
                    inpRef.blur();
                    if (!this.isSend[fieldName]) {
                        this.accountStartSaveData(this.model[fieldName], fieldName);
                    }
                } else {
                    window.console.warn('Ошибка редактирования поля', 'f', fieldName);
                }
            }, 50);
        },
        formatFormData(newValue, fieldName) {
            let formData = {};
            formData[fieldName] = newValue;
            return formData;
        },
        inputFieldEdit($event, fieldName) {
            this.serverRegMessages[fieldName] = null;
            this.$v.model[fieldName].$touch();
        },
    }
};

export {EditInline as default}
