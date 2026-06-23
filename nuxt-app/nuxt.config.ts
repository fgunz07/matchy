// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";
export default defineNuxtConfig({
  app: {
    head: {
      htmlAttrs: {
        'data-theme': 'black',
      },
    },
  },
  compatibilityDate: '2025-07-15',
  css: ['~/assets/css/main.css'],
  devtools: { enabled: true },
  routeRules: {
    '/': { redirect: '/login' },
  },
  vite: {
    plugins: [tailwindcss() as any],
  }
})
