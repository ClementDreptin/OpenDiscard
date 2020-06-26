module.exports = {
    pluginOptions: {
        electronBuilder: {
            builderOptions: {
                linux: {
                    category: 'Chat',
                    target: ['deb', 'tar.gz']
                },
                win: {
                    target: 'msi'
                }
            }
        }
    }
}