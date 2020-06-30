<template lang="html">
    <BottomNavigation>
        <TabStrip>
            <TabStripItem class="navigation__item">
                <Label text="Servers"></Label>
                <Image src.decode="font://&#xf022;" class="fas t-36"></Image>
            </TabStripItem>
            <TabStripItem class="navigation__item">
                <Label text="Channel"></Label>
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
</template>

<script>
    import Servers from "./Servers";
    import Messages from "./Messages";
    import Members from "./Members";

    export default {
        components: {
            Servers,
            Messages,
            Members
        }
    };
</script>

<style lang="scss">

</style>
