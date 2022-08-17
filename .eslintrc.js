module.exports = {
    env: {
        browser: true,
        es2021: true,
        node: true,
    },
    extends: [
        "plugin:vue/vue3-recommended",
        "prettier",
        "@vue/typescript/recommended",
    ],
    parserOptions: {
        ecmaVersion: 13,
        sourceType: "module",
    },
    plugins: ["vue", "html", "prettier"],
    rules: {
        "prettier/prettier": "error",
        "vue/multi-word-component-names": 0,
        "vue/require-default-prop": 0,
        "vue/no-reserved-component-names": 0,
    },
};
