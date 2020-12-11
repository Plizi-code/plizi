/**
 * @link https://cli.vuejs.org/ru/config/#гnобаnьная-конфигурация-cli
 */
module.exports = {
    pluginOptions: {
        sourceDir: 'src'
    },

    filenameHashing : true,
    lintOnSave : false,
    productionSourceMap: false,

    chainWebpack: config => {
        config.plugin("define").tap(args => {
            let _base = args[0]["process.env"];
            args[0]["process.env"] = {
                ..._base,
                "API_URL": JSON.stringify(process.env.API_URL),
                "WS_URL": JSON.stringify(process.env.WS_URL),
            };
            return args;
        });
    }
    // First prop for ngrok, second enable https protocol.
    // devServer: {
    //     disableHostCheck: true,
    //     https: true,
    // },
};
