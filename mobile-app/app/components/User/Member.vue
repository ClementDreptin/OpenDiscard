<template>
    <FlexboxLayout>
        <Image v-if="member.avatar_url"
               :src="`${axios.defaults.baseURL}${member.avatar_url}`"
               class="member-avatar"/>
        <Image v-else
               src="~/assets/default-profile-picture.png"
               class="member-avatar"/>
        <Label :text="member.username" flexGrow="1"/>
        <Image v-if="$store.state.currentServer && $store.state.currentServer.owner_id === $store.state.user.id && $store.state.currentServer.owner_id !== member.id"
               src.decode="font://&#xf00d;"
               stretch="none"
               fontSize="18"
               class="fas kick-button"/>
    </FlexboxLayout>
</template>

<script>
    export default {
        name: "Member",
        props: [
            'member'
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

    Label {
        font-size: 18;
        color: $white;
        margin-top: 12;
        margin-left: 18;
    }

    .kick-button {
        color: #c92142;
        opacity: 0.4;
        margin-top: 12;
    }

    .member-avatar {
        width: 48;
        height: 48;
        margin-right: 10;
        margin-bottom: 10;
    }
</style>
