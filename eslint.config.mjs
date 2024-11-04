import globals from "globals";
import js from "@eslint/js";
import prettier from "eslint-config-prettier";
export default [
    js.configs.all,
    prettier,
    {
        languageOptions: {
            ecmaVersion: "latest",
            globals: {
                ...globals.jquery,
                ...globals.browser,
                ...globals.node
            }
        },
        rules: {
            "no-console": "off",
            "no-alert": "off",
            "no-unused-vars": "warn",
            "sort-keys": "off",
            "one-var": "off",
            "no-use-before-define": "off",
            "func-style": ["error", "declaration"],
            "max-statements": ["error", 10, { "ignoreTopLevelFunctions": true }],
            "no-magic-numbers": ["error", {"ignoreArrayIndexes": true}],
            "sort-imports": "off"
        }
    }
];
