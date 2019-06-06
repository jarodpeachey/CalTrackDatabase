module.exports = {
  env: {
    browser: true,
    es6: true
  },
  extends: "airbnb",
  globals: {
    Atomics: "readonly",
    SharedArrayBuffer: "readonly"
  },
  parserOptions: {
    ecmaFeatures: {
      jsx: true
    },
    ecmaVersion: 2018,
    sourceType: "module"
  },
  plugins: ["react"],
  rules: {
    "array-bracket-spacing": [1, "never"],
    "camelcase": [1],
    "class-methods-use-this": 0,
    "jsx-a11y/alt-text": 0,
    "jsx-a11y/anchor-has-content": 1,
    "jsx-a11y/anchor-is-valid": 1,
    "jsx-a11y/click-events-have-key-events": 0,
    "jsx-a11y/heading-has-content": 1,
    "jsx-a11y/iframe-has-title": 1,
    "jsx-a11y/label-has-associated-control": 1,
    "jsx-a11y/label-has-for": 0,
    "jsx-a11y/mouse-events-have-key-events": 1,
    "jsx-a11y/no-autofocus": 1,
    "jsx-a11y/no-noninteractive-element-interactions": 1,
    "jsx-a11y/no-static-element-interactions": 0,
    "jsx-quotes": 2,
    "max-len": [0, 80, 4],
    "no-console": [0],
    "no-else-return": [0],
    "no-multi-spaces": [0],
    "no-multiple-empty-lines": [0],
    "no-plusplus": [0],
    "no-underscore-dangle": [0],
    "object-curly-newline": 0,
    "object-curly-spacing": [1,
      "always", {
        "arraysInObjects": false,
        "objectsInObjects": true
      }
    ],
    "operator-linebreak": [1, "after"],
    "padded-blocks": [1],
    "prefer-destructuring": 1,
    "quotes": [1, "single", "avoid-escape"],
    "radix": 0,
    "react/button-has-type": 1,
    "react/destructuring-assignment": 0,
    "react/forbid-prop-types": 0,
    "react/indent-prop": 0,
    "react/jsx-first-prop-new-line": 0,
    "react/jsx-indent-props": 0,
    "react/jsx-no-bind": 1,
    "react/no-access-state-in-setstate": 1,
    "react/no-array-index-key": 1,
    "react/no-children-prop": 1,
    "react/no-did-mount-set-state": 0,
    "react/no-did-update-set-state": 0,
    "react/no-string-refs": 1,
    "react/no-unused-prop-types": 1,
    "react/no-unused-state": 1,
    "react/prefer-stateless-function": 0,
    "react/prop-types": 1,
    "react/require-default-props": 0,
    "react/sort-comp": 1,
    "space-before-function-paren": [1, {"anonymous": "always", "named": "always"}],
    "space-in-parens": [1],
    "template-curly-spacing": ["warn", "never"]
  },
  parser: "babel-eslint"
};
