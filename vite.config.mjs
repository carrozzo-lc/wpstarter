import { defineConfig } from 'vite';
import { resolve } from 'node:path';
import starterConfig from './starter.config.json';

const themeRoot = resolve('webroot/wp-content/themes', starterConfig.themeFolder);

export default defineConfig({
  server: {
    host: starterConfig.vite.host,
    port: starterConfig.vite.port,
    strictPort: true,
    origin: `http://${starterConfig.vite.publicHost}:${starterConfig.vite.port}`,
    cors: true,
  },
  css: {
    devSourcemap: true,
  },
  build: {
    manifest: true,
    outDir: resolve(themeRoot, 'assets'),
    emptyOutDir: true,
    rollupOptions: {
      input: resolve('source/theme/main.js'),
      output: {
        entryFileNames: 'js/[name]-[hash].js',
        chunkFileNames: 'js/[name]-[hash].js',
        assetFileNames: ({ names = [] }) => {
          const assetName = names[0] || '';

          if (assetName.endsWith('.css')) {
            return 'css/[name]-[hash][extname]';
          }

          return 'assets/[name]-[hash][extname]';
        },
      },
    },
  },
});
