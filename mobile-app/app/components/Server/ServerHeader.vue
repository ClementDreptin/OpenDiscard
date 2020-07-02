<template>
    <StackLayout>
        <FlexboxLayout justifyContent="space-between">
            <Label :text="$store.state.currentServer.name"/>
            <Image src.decode="font://&#xf013;" stretch="none" fontSize="18" class="fas" opacity="0.7"/>
        </FlexboxLayout>
        <StackLayout class="hr"/>
    </StackLayout>
</template>

<script>
    export default {
        name: "ServerHeader"
    }
</script>

<style scoped lang="scss">
    Label {
        font-size: 18;
        color: white;
    }

    FlexboxLayout {
        padding: 18;
    }
</style>
