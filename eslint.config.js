import js from '@eslint/js';
import globals from 'globals';

export default [
  {
    ignores: ['assets/dist/**', 'node_modules/**', 'vendor/**'],
  },
  js.configs.recommended,
  {
    files: ['assets/src/js/**/*.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        // Loaded by WordPress/WooCommerce on the pages that use it; modules check before using it.
        jQuery: 'readonly',
      },
    },
    rules: {
      'no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
      eqeqeq: ['error', 'always'],
      'prefer-const': 'error',
      'no-var': 'error',
    },
  },
  {
    files: ['vite.config.js', 'eslint.config.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: globals.node,
    },
  },
];
