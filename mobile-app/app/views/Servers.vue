<template>
    <Page>
        <ActionBar>
            <Label text="Servers"></Label>
        </ActionBar>

        <FlexboxLayout>
            <ServersBar width="20%"/>
            <StackLayout width="80%" backgroundColor="red">
                <Label text="random stuff"/>
            </StackLayout>
        </FlexboxLayout>
    </Page>
</template>

<script>
    import ServersBar from "~/components/Server/ServersBar";

    export default {
        name: "Servers",
        components: {
            ServersBar
        }
    };
</script>

<style scoped lang="scss">

</style>
