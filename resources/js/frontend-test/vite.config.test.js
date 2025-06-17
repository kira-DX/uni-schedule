import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'
import { resolve } from 'path'

export default defineConfig({
  plugins: [react()],
  root: './',
  base: '/react-test/', // ← 出力先が public/react-test なので、これに修正
  build: {
    outDir: '../../../public/react-test',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'TestApp.jsx'),
      },
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
});