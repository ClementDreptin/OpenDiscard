<template>
    <FlexboxLayout>
        <Image v-if="message.author.avatar_url"
               :src="`${axios.defaults.baseURL}${message.author.avatar_url}`"
               class="member-avatar"/>
        <Image v-else
               src="~/assets/default-profile-picture.png"
               class="member-avatar"/>
        <StackLayout flexGrow="1" class="message-content">
            <Label :text="message.author.username" class="author-name"/>
            <Label :text="message.content" textWrap="true"/>
            <Label :text="message.updated_at" class="date"/>
        </StackLayout>
    </FlexboxLayout>
</template>

<script>
    export default {
        name: "Message",
        props: [
            'message'
        ],
        data() {
            return {
                axios: global.axios
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables";

    .member-avatar {
        width: 48;
        height: 48;
        margin-right: 10;
    }

    .author-name {
        color: white;
        font-weight: bold;
    }

    .message-content {
        color: $white;
        margin-left: 9;
        margin-top: 9;

        Label {
            font-size: 15;
        }

        .date {
            color: #72767d;
        }
    }
</style>
