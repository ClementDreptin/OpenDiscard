<template>
    <FlexboxLayout justifyContent="space-between">
        <Label width="80%"
               :text="textChannel.name"/>
        <Image v-if="$store.state.currentServer.owner_id === $store.state.user.id"
               src.decode="font://&#xf013;"
               stretch="none"
               fontSize="18"
               class="fas text-channel-settings-button"
               opacity="0.5"/>
    </FlexboxLayout>
</template>

<script>
    export default {
        name: "TextChannel",
        props: [
            'textChannel'
        ]
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables.scss";

    Label {
        font-size: 18;
        color: $white;
    }

    FlexboxLayout {
        margin-bottom: 12;
    }

    .text-channel-settings-button {
        color: $white;
    }
</style>
