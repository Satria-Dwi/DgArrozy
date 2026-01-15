import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
  },
  server: {
    strictPort: true,
  },
})
