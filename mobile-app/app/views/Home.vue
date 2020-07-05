<template>
    <Page actionBarHidden="true">
        <BottomNavigation @loaded="onLoad">
            <TabStrip>
                <TabStripItem class="navigation__item">
                    <Label text="Servers"></Label>
                    <Image src.decode="font://&#xf022;" class="fas t-36"></Image>
                </TabStripItem>
                <TabStripItem class="navigation__item">
                    <Label text="Messages"></Label>
                    <Image src.decode="font://&#xf4ad;" class="fas t-36"></Image>
                </TabStripItem>
                <TabStripItem class="navigation__item">
                    <Label text="Members"></Label>
                    <Image src.decode="font://&#xf0c0;" class="fas t-36"></Image>
                </TabStripItem>
            </TabStrip>

            <TabContentItem>
                <Frame id="servers">
                    <Servers/>
                </Frame>
            </TabContentItem>

            <TabContentItem>
                <Frame id="messages">
                    <Messages/>
                </Frame>
            </TabContentItem>

            <TabContentItem>
                <Frame id="members">
                    <Members/>
                </Frame>
            </TabContentItem>

        </BottomNavigation>
    </Page>
</template>

<script>
    import Servers from "./Servers";
    import Messages from "./Messages";
    import Members from "./Members";

    export default {
        name: "Home",
        components: {
            Servers,
            Messages,
            Members
        },
        methods: {
            onLoad(args) {
                global.bottomNav = args.object;
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
