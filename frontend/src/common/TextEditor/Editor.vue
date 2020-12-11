<template>
    <div class="editor" :class="{'border-danger': isError}">

        <editor-content class="editor-content"
                        :editor="editor"
                        ref="editor"
                        @keydown.native="onEditorKeyDown"
                        @keyup.native="onEditorKeyUp"></editor-content>

        <span v-if="isShowPlaceHolder" @click="setFocusEditor" class="placeholder">
            {{ placeholder }}
        </span>
        <span class="editor-counter">{{ textLength }} / {{ maximumCharacterLimit }}</span>
    </div>
</template>

<script>
/** @link https://github.com/scrumpy/tiptap **/
import {Editor, EditorContent, EditorMenuBar} from 'tiptap';
import {
    //HardBreak,
    HorizontalRule,
    //Bold,
    //Italic,
    Link,
    History,
} from 'tiptap-extensions';

export default {
name: 'Editor',
components: {
    EditorContent,
    EditorMenuBar,
},
props: {
    emoji: {
        type: String,
        default: null,
    },
    placeholder: {
        type: String,
        default: null,
    },
    inputEditorText: String,
    maximumCharacterLimit: {
        type: Number,
        default: 10000,
    },
    isError: {
        type: Boolean,
        default: false,
    },
},

data() {
    return {
        editor: new Editor({
            extensions: [
                //new HardBreak(),
                new HorizontalRule(),
                new Link(),
                //new Bold(),
                //new Italic(),
                new History(),
            ],
            onFocus: this.onFocus,
            onBlur: this.onBlur,
            onUpdate: this.onUpdate,
            content: this.inputEditorText ? this.inputEditorText : null,
        }),
        isFocusedEditor: false,
        cursorPosition: 1,
        textLength: 0,
    }
},

computed: {
    calcEditorH(){
        return 'height: '+this.height+'px';
    },

    isShowPlaceHolder(){
        return !(this.isFocusedEditor || this.editor.getHTML() !== '<p></p>');
    }
},

methods: {
    setFocusEditor(){
        this.$refs.editor.editor.focus();
    },

    addEmoji( emoji ){
        let currText = this.editor.getHTML();
        currText = (currText.toLowerCase() === `<p></p>`) ? '' : currText;

        if ( '' === currText ){
            this.editor.setContent( `<p class="big-emoji">${emoji}</p>` );
        }

        currText = currText.substr( 0, currText.length - 4 ) + `<span class="emoji">${emoji}</span>`;

        this.editor.setContent( currText );
        this.editor.focus();
    },

    onEditorKeyDown( ev ){
        this.$emit( 'editorKeyDown', ev );
        this.$parent.checkUpdatedChatContainerHeight();

        if ( 13 === ev.keyCode && ev.ctrlKey === true ){
            let editorText = this.editor.getHTML();
            let str = editorText.replace( /<p>|<\/p>/g, '' ).trim();

            if ( !(!!str.replace( /[\u{1F300}-\u{1F6FF}]/gu, '' ).trim()) ){
                let matches = str.match( /[\u{1F300}-\u{1F6FF}]/gu );

                if ( matches && matches.length === 1 ){
                    editorText = `<p class="big-emoji">${str}</p>`;
                }
            }

            this.editor.setContent( '' );
            this.$emit( 'editorPost', { postText : editorText } );
            this.clearTextLength();
        }
    },

    onEditorKeyUp(){
        this.$parent.checkUpdatedChatContainerHeight();
    },

    onFocus( event ){
        this.isFocusedEditor = true;
    },

    onBlur( event ){
        let str = this.editor.getHTML().replace( /<\/?[^>]+>/g, '' ).trim();
        this.cursorPosition = event.view.state.selection.$anchor.pos;

        if ( !(!!str) ){
            this.isFocusedEditor = false;
        }
    },

    clearTextLength() {
        this.textLength = 0;
    },

    onUpdate( event ){
        let str = this.editor.getHTML().replace( /<\/?[^>]+>/g, '' ).trim();
        this.cursorPosition = event.state.selection.$anchor.pos;
        this.textLength = str.length;

        this.$emit( 'onMaximumCharacterLimit', str );
        this.$emit( 'onUpdate' );
    },

    setContent( newContent ){
        this.editor.setContent( newContent );
    },

    getContent(){
        return this.editor.getHTML();
    },

    focus(){
        this.editor.focus();
    },
},

beforeDestroy(){
    this.editor.destroy();
}
}
</script>

