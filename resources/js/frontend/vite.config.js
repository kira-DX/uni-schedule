// resources/js/frontend/vite.config.js
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [react()],
  root: './', // ← frontend ディレクトリがルート
  base: '/react/',
  build: {
    outDir: '../../../public/react', // Laravelのpublic/reactに出力
    emptyOutDir: true
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
})