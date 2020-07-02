<template>
    <Page>
        <ActionBar>
            <Label text="Servers"></Label>
        </ActionBar>

        <FlexboxLayout>
            <ServersBar width="20%"/>
            <TextChannels width="80%"/>
        </FlexboxLayout>
    </Page>
</template>

<script>
    import ServersBar from "~/components/Server/ServersBar";
    import TextChannels from "~/components/TextChannel/TextChannels";

    export default {
        name: "Servers",
        components: {
            ServersBar,
            TextChannels
        }
    };
</script>

<style scoped lang="scss">

</style>
