<template>
    <div :id="fieldId" :class="blockClass" ref="editorContainer">
        <div class="flex-column w-100 position-relative">
            <div class="row w-100 ml-0">
                <div v-if="showAvatar" class="plz-editor-avatar col-1 align-items-center text-center pt-2">
                    <img class="chat-companion-user-pic rounded-circle my-0 mx-auto"
                         v-bind:src="userPic" v-bind:alt="userFullName" />
                </div>

                <div class="plz-editor-body pl-0"
                     :class="{ 'plz-editor-body-wza': showAvatar, 'forward-message-width': !showAvatar }">

                    <div class="form pl-2">
                        <div class="form-row align-items-center">
                            <div class="col-12 d-flex justify-content-between p-0">

                                <Editor class="plz-text-editor-form form-control px-2 py-1"
                                        @editorPost="onEditorNewPost"
                                        @editorKeyDown="onEditorKeyDown"
                                        @onMaximumCharacterLimit="onMaximumCharacterLimit"
                                        @onUpdate="onUpdate"
                                        :placeholder="editorPlaceholder"
                                        :inputEditorText="inputEditorText"
                                        :maximumCharacterLimit="maximumCharacterLimit"
                                        :isError="isMaximumCharacterLimit"
                                        ref="editor" />

                                <button @click.stop="onSendPostClick"
                                        :disabled="isLoading"
                                        class="btn btn-link">
                                    <IconSend style="height: 20px" />
                                </button>
                            </div>

                            <div v-if="isMaximumCharacterLimit" class="col-12">
                                <p class="text-danger">Превышено максимально допустимое количество символов.</p>
                            </div>
                            <div v-if="bodyError" class="col-12">
                                <p class="text-danger">{{ bodyError[0] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="plz-editor-btns d-flex flex-column flex-md-row justify-content-between"  >

                    <button type="button" @click.stop="onAttachBtnClick($event)"
                        :class="{'attach-file--disallow cursor-non-drop' : isDisallowUpload}"
    class="attach-file btn-add-file w-100 d-flex align-items-center justify-content-center btn btn-link my-0 mx-0 mr-md-2 px-1 position-relative">
                        <IconAddFile />
                        <input type="file" class="plz-text-editor-file-picker"
                               :disabled="isDisallowUpload" @change="onSelectFile()" ref="editorFiler" multiple />
                    </button>

                    <!--<label class="attach-file d-flex align-items-center  btn btn-link my-0 ml-0 mr-2 px-1 btn-add-camera position-relative">
                        <IconAddCamera />
                        <input type="file" @change="onSelectImage($event)" ref="editorImager" multiple />
                    </label>-->

                    <button class="btn btn-link w-100 mx-0 p-0 btn-add-smile position-relative" type="button">
                        <EmojiPicker @addEmoji="onAddEmoji" v-bind:transform="emojiTransform" refs="emojiPicker"></EmojiPicker>
                    </button>
                </div>
            </div>

            <div v-if="attachFiles  &&  attachFiles.length>0" class="row mt-3">
                <div class="plz-attachment-images pl-4" :class="{'offset-1 col-9 ' : showAvatar, 'col-10': !showAvatar }" >
                    <ul class="plz-attachment-images-list list-unstyled d-flex flex-row mb-0 flex-wrap"
                        ref="attachList">
                        <AttachmentItem v-for="atFile in attachFiles"
                            @RemoveAttachment="onRemoveAttachment"
                            @AttachmentLoaded="onAttachmentLoaded"
                            @ZipAttachmentDisplayed="onAttachmentLoaded"
                            v-bind:attach="atFile"
                            v-bind:key="atFile.id">
                        </AttachmentItem>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {isEmoji} from '../utils/StringUtils.js';

import IconAddFile from '../icons/IconAddFile.vue';
import IconAddCamera from '../icons/IconAddCamera.vue';
import IconSend from "../icons/IconSend.vue";

import Editor from './TextEditor/Editor.vue';
import EmojiPicker from './TextEditor/EmojiPicker.vue';
import AttachmentItem from './TextEditor/AttachmentItem.vue';

import LinkMixin from '../mixins/LinkMixin.js';

import { checkMimeType, checkExtension } from '../utils/FileUtils.js';
import { docsExtensions, imagesExtensions } from '../enums/FileExtensionEnums.js';

import PliziAttachmentItem from '../classes/PliziAttachmentItem.js';
import PliziAttachment from '../classes/PliziAttachment.js';
import PliziCollection from '../classes/PliziCollection.js';

export default {
name: 'TextEditor',
components: {
    IconSend,
    IconAddCamera,
    IconAddFile,
    Editor,
    EmojiPicker,
    AttachmentItem
},
props : {
    fieldId : String,
    showAvatar : Boolean,
    clazz : String,
    editorPlaceholder : String,
    dropToDown : Boolean,
    workMode : {
        type : String,
        required : true,
    },
    inputEditorText : String,
    inputEditorAttachment : Array,
    maxFilesCount : {
        type : Number,
        default : 10,
    },
    maximumCharacterLimit : {
        type : Number,
        default : 10000,
    },
    errors : {
        type : Object,
        default : null,
    },
},

mixins: [LinkMixin],

data() {
    /** @TGA и почему инициализация этого тут, а не в created() ?? **/
    let inputFiles = [];

    if (this.inputEditorAttachment) {
        inputFiles = this.inputEditorAttachment.map((file) => {
            const attachment = new PliziAttachmentItem(false, file.isImage, file.originalName);
            attachment.attachment = file;

            return attachment;
        });
    }

    return {
        isLoading: false,
        attachFiles : inputFiles,
        attachmentsData: (new PliziCollection()),
        defaultClasses: `bg-white w-100 border-top position-relative mt-auto`,
        editorContainerHeight: 32,
        isMaximumCharacterLimit: false,
        valueToContainerHeight: 0
    }
},

computed: {
    emojiTransform(){
        if (this.dropToDown)
            return 'transform: translate(-40%, 40px)';

        // Если -84px не подходит, нужно прокинуть событие выше родителю, -84px для ChatFooter.vue
        return 'transform: translate(-84%, -100%)';
    },

    userPic() {
        return this.$root.$auth.user.userPic;
    },

    userFullName() {
        return this.$root.$auth.user.fullName;
    },

    blockClass(){
        return this.clazz || this.defaultClasses;
    },

    isDisallowUpload() {
        return this.attachFiles.length >= this.maxFilesCount;
    },

    bodyError() {
        return this.errors && this.errors.body ? this.errors.body : null;
    },
},

methods: {
    focus(){
        if (this.$refs.emojiPicker) {
            this.$refs.emojiPicker.hidePicker();
        }

        if (this.$refs.editor) {
            this.$refs.editor.focus();
        }
    },

    getContent() {
        return {
            postText: this.$refs.editor.getContent(),
            attachments: this.getAttachmentsIDs(),
            attachmentsData: this.attachmentsData.asArray(),
            videoLink: null,
            workMode: this.workMode,
        }
    },

    getAttachmentsIDs() {
        if (this.attachFiles && this.attachFiles.length > 0)
            return this.attachFiles.map((aItem) => {
                return aItem.attachment.id;
            });

        return [];
    },

    onRemoveAttachment(evData) {
        this.attachFiles = this.attachFiles.filter((aItem) => {
            return aItem.attachment.id !== evData.attach.id;
        });

        this.attachmentsData.delete(evData.attach.id);

        setTimeout(() => {
            this.checkUpdatedChatContainerHeight();
        }, 200);

        this.$emit('onRemoveAttachment', evData.attach.id);
    },

    onAttachmentLoaded() {
        this.checkUpdatedChatContainerHeight();
    },

    onSendPostClick(){
        const cont = this.$refs.editor.getContent();
        let str = cont.replace(/<p>|<\/p>/g, '').trim();
        let attachmentsIds = this.getAttachmentsIDs();

        if (!str.length && !(attachmentsIds && attachmentsIds.length))
            return;

        this.$refs.editor.setContent('');
        this.$refs.editor.focus();

        this.checkUpdatedChatContainerHeight();

        this.onEditorNewPost({
            postText: cont
        });
        this.$refs.editor.clearTextLength();
    },

    onEditorNewPost(evData) {
        if (this.isMaximumCharacterLimit) {
            this.isMaximumCharacterLimit = false;
            return;
        }

        let str = evData.postText.replace(/<\/?[^>]+>/g, ' ').trim();

        if ((str.length === 1 ||  str.length === 2)  &&  isEmoji(str))
        {
            const be = `<p class="big-emoji">${str}</p>`;
            this.emitPost(be, attachmentsIds, attachmentsData, null);
            this.attachFiles = [];
            this.attachmentsData.clear();
            return;
        }

        let youtubeLinksMatch = this.detectYoutubeLinks(str);
        let attachmentsIds = this.getAttachmentsIDs();
        let attachmentsData = this.attachmentsData.asArray();
        let postText = this.deleteYoutubeLinksFromStr(str);

        if (youtubeLinksMatch && youtubeLinksMatch.length) {
            youtubeLinksMatch.forEach((youtubeLink) => {
                this.emitPost(postText, null, null, youtubeLink);

                if (attachmentsIds.length >= 1) {
                    this.emitPost('<p></p>', attachmentsIds, attachmentsData, null);
                }

                postText = '';
            });
        }
        else {
            this.emitPost(postText, attachmentsIds, attachmentsData, null);
        }

        this.attachFiles = [];
        this.attachmentsData.clear();
    },

    emitPost(postText = null, attachments = null, attachmentsData = null, videoLink = null) {
        this.$emit('editorPost', {
            postText: postText,
            attachments: attachments,
            attachmentsData: attachmentsData,
            videoLink: videoLink,
            workMode: this.workMode
        });
    },

    onEditorKeyDown(ev) {
        this.$emit('editorKeyDown', ev);
    },

    onSelectFile(ev) {
        this.addUploadAttachment([...this.$refs.editorFiler.files]);
    },

    onSelectImage(ev) {
        this.addUploadAttachment([...this.$refs.editorImager.files]);
    },

    onAddEmoji(evData) {
        if (evData.keys.ctrlKey) { // был нажат Ctrl
            this.$refs.editor.focus();

            let txt = this.$refs.editor.getContent();

            if (`<p></p>` === txt.toLowerCase()) { // поле ввода пустое - значит отправляем только увеличенный эмоджи
                const sendSmile = `<p class="big-emoji">${evData.emoji}</p>`;
                this.$emit('editorPost', {postText: sendSmile});
            }
            else { // просто добавляем эмоджи
                this.$refs.editor.addEmoji(evData.emoji);
            }
        }
        else { // просто добавляем эмоджи
            this.$refs.editor.addEmoji(evData.emoji);
        }
    },

    onEditorNewHeight(evData) {
        this.$emit('editorNewHeight', {
            newHeight: evData
        });
    },

    getStartContainerHeight () {
        this.editorContainerHeight = this.$refs.editorContainer.offsetHeight ;
    },

    checkUpdatedChatContainerHeight() {
        const updatedChatContainerHeight = this.$refs.editorContainer.offsetHeight + this.valueToContainerHeight;

        if (this.editorContainerHeight !== updatedChatContainerHeight) {
            this.editorContainerHeight = updatedChatContainerHeight;
        }

        this.onEditorNewHeight(this.editorContainerHeight);
    },

    onMaximumCharacterLimit(str) {
        this.isMaximumCharacterLimit = str.length > this.maximumCharacterLimit;
    },

    onUpdate() {
        this.$emit('onUpdateEditor');
    },

    onAttachBtnClick(ev){
        /** FIXME: @TGA после MVP тут надо переделать
         * иначе не получается открыть диалог выбора файлов в "привязанном" чате PLZ-420 **/
        let $btn = null;
        if (ev.target.tagName.toUpperCase() === 'BUTTON') {
            $btn = $(ev.target);
        }
        else {
            $btn = $(ev.target).closest('button.attach-file');
        }

        const $file = $btn.find('input.plz-text-editor-file-picker');
        $file.click();
    },

    addValueToContainerHeight() {

        let workMode = this.getContent().workMode;

        if( workMode == 'chat' ) {
            this.valueToContainerHeight = 20;
        };
    },

    async addUploadAttachment(picsArr) {
        this.$refs.editor.focus();

        const filesCount = picsArr.length + this.attachFiles.length;

        if (filesCount > this.maxFilesCount) {
            this.$alert(`
                <h4 class="text-white">Ошибка</h4>
                <div class="alert alert-danger">
                Превышен лимит загрузки файлов
                <br />
                Допустимый максимальный лимит файлов: <b class="text-success">${this.maxFilesCount}</b>
                </div>`, `bg-danger`, 30
            );
            return;
        }

        const allowExtensions = [...imagesExtensions, ...docsExtensions];

        for (const file of picsArr) {
            if (!checkExtension(file, allowExtensions) || !checkMimeType(file)) {
                this.$alert(`
                    <h4 class="text-white">Ошибка</h4>
                    <div class="alert alert-danger">
                        Недопустимое расширение у файла <b>${file.name}</b>
                        <br />
                        Допустимые расширения файлов: <b class="text-success">${allowExtensions.join( ', ' )}</b>
                    </div>`,
                    `bg-danger`,
                    30
                );

                picsArr = picsArr.filter(foundFile => foundFile.name !== file.name);
            }
        }

        if (picsArr.length === 0)
            return;

        for (let file of picsArr) {
            const reader = new FileReader();
            reader.onload = () => {
                const attachment = new PliziAttachmentItem(true, checkExtension(file, imagesExtensions), file.name);
                attachment.isBlob = true;
                this.isLoading = attachment.isBlob;
                attachment.fileBlob = reader.result;
                this.attachFiles.push(attachment);
            };

            reader.readAsDataURL(file);

            let apiResponse = [];

            /** TODO: @TGA надо потом перенести отсюда загрузку аттачей **/
            const delay = ms => new Promise(resolve => setTimeout(resolve, ms));
            await delay(50);
            switch (this.workMode) {
                case 'chat':
                    apiResponse = await this.$root.$api.$chat.attachment([file]);
                    break;

                case 'comment':
                    apiResponse = await this.$root.$api.$post.addAttachmentsToComment([file]);
                    break;

                case 'post':
                    apiResponse = await this.$root.$api.$post.storePostAttachments([file]);
                    break;

                default:
                    console.warn('TextEditor::addUploadAttachment - No matches in switch.');
            }

                apiResponse.map((attItem) => {
                    const newAtt = new PliziAttachment(attItem);

                    this.attachFiles = this.attachFiles.map(foundFile => {
                        if (foundFile.originalName === newAtt.originalName) {
                            foundFile.attachment = newAtt;
                            foundFile.isBlob = false;
                            this.isLoading = foundFile.isBlob;
                            foundFile.fileBlob = null;
                        }

                        return foundFile;
                    });

                    this.attachmentsData.add(attItem);

                    this.$emit('newAttach', {attach: newAtt});
                })
        }
    },
},
mounted() {
    this.getStartContainerHeight();
    this.addValueToContainerHeight();
}
}

</script>
