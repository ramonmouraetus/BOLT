import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': '/src',
    },
  },
  esbuild: {
    minify: false
  },
  build: {
    minify: false,
    outDir: '../../includes/assets/admin-config',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: './src/main.js',
      },
      output: {
        entryFileNames: 'translate/js/app.js',
        assetFileNames: 'translate/css/app.[ext]',
      },
      external: ['debounce'],
    },
    sourcemap: true,
  },
  server: false
});