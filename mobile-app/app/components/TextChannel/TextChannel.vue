<template>
    <FlexboxLayout justifyContent="space-between">
        <Label :text="textChannel.name"/>
        <Image src.decode="font://&#xf013;" stretch="none" fontSize="18" class="fas" opacity="0.5"/>
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
</style>
