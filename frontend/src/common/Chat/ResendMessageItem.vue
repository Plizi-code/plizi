<template>
    <div class="message-resend pl-3 ml-1 mt-3">
        <div class="message-user-data  d-flex align-items-center mb-2">
            <div class="media-pic border rounded-circle  mr-3">
                <img :src="message.userPic" :alt="message.firstName" />
            </div>
            <div class="media-body">
                <h6 class="chatHeader-title w-75 align-self-start mt-2 pb-0 mb-0 pull-left text-body"
                    style="line-height: 20px;">{{ message.firstName }}</h6>

                <p class="chatHeader-subtitle p-0 mb-0 mt-1 w-100 d-block">
                    {{ message.createdAt | lastMessageTime }}
                </p>
            </div>
        </div>
        <p v-html="message.body"></p>
    </div>
</template>

<script>
import PliziMessage from '../../classes/PliziMessage.js';

export default {
name : 'ResendMessageItem',
props: {
    message: PliziMessage
},
}
</script>
