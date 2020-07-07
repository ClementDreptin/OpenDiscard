<template>
    <StackLayout id="text-channel-header">
        <Label :text="$store.state.currentTextChannel.name"/>
        <StackLayout class="hr"/>
    </StackLayout>
</template>

<script>
    export default {
        name: "TextChannelHeader"
    }
</script>

<style scoped lang="scss">
    Label {
        font-size: 18;
        color: white;
        text-align: center;
        margin-bottom: 8;
    }

    #text-channel-header {
        padding-top: 8;
    }

    .hr {
        margin-bottom: 0;
    }
</style>
