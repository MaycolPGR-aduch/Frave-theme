import { resolve } from 'node:path';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

const themeRoot = import.meta.dirname;

export default defineConfig({
  base: './',
  plugins: [tailwindcss()],
  publicDir: false,
  server: {
    host: '127.0.0.1',
    port: 5173,
    strictPort: true,
    origin: 'http://127.0.0.1:5173',
    cors: {
      origin: /^http:\/\/(127\.0\.0\.1|localhost)(:\d+)?$/,
    },
  },
  build: {
    outDir: resolve(themeRoot, 'assets/dist'),
    emptyOutDir: true,
    manifest: 'manifest.json',
    sourcemap: false,
    rollupOptions: {
      input: resolve(themeRoot, 'assets/src/js/app.js'),
      output: {
        entryFileNames: 'js/[name]-[hash].js',
        chunkFileNames: 'js/[name]-[hash].js',
        assetFileNames: ({ name }) => {
          if (name?.endsWith('.css')) return 'css/[name]-[hash][extname]';
          if (/\.(woff2?|ttf|otf)$/i.test(name ?? '')) return 'fonts/[name]-[hash][extname]';
          return 'media/[name]-[hash][extname]';
        },
      },
    },
  },
});
