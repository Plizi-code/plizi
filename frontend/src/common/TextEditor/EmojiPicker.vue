<template>
    <Picker @emoji="insert">

        <div slot="emoji-invoker"
             slot-scope="{ events: { click: clickEvent } }"
             @click.stop="clickEvent"
             class="picker-btn  ">
            <IconAddSmile />
        </div>

        <div slot="emoji-picker" slot-scope="{ emojis, insert, display}">
            <div v-if="isVisible" class="picker" :style="transform">
                <div v-for="(emojiGroup, category) in emojis" :key="category">
                    <!--<h5 class="picker-category">{{ category }}</h5>-->
                    <div>
                        <span v-for="(emojiItem, emojiName) in emojiGroup"
                              :key="emojiName"
                              @click="insert({event: $event, emoji: emojiItem})"
                              :title="emojiName"
                              v-html="parseEmoji(emojiItem)"
                              class="picker-emoji py-1">
                           <!-- {{ emojiItem }} -->
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </Picker>
</template>

<script>
/** @link https://github.com/DCzajkowski/vue-emoji-picker **/
/**
 * для кроссбраузерности эмоджиков в частности для хрома в win7
 * @link https://www.npmjs.com/package/vue-twemoji **/

import Picker from 'vue-emoji-picker';
import IconAddSmile from '../../icons/IconAddSmile.vue';

export default {
name: 'EmojiPicker',
components: { Picker, IconAddSmile },
props: {
    transform: {
        type: String,
        default: null,
    },
},
data() {
    return {
        isVisible: true,
    }
},
methods: {
    hidePicker(){
        this.isVisible= false;
    },

    insert(eventData) {
        this.$emit('addEmoji', {
            emoji : eventData.emoji,
            keys : {
                altKey: eventData.event.altKey,
                ctrlKey: eventData.event.ctrlKey,
                shiftKey: eventData.event.shiftKey
            }
        });

        if (eventData.event.ctrlKey) {
            this.isVisible = false;

            // TODO: @YZ исправить хак.
            setTimeout(() => {
                this.isVisible = true;
            }, 100);
        }
    },
    parseEmoji: function (string) {
        return this.$twemoji.parse(string);
    }
},
}
</script>

