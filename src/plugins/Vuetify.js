import Vue from 'vue'


Vue.use(Vuetify, {
    theme: {
        dark: false,
      },
      themes: {
        light: {
          primary: "#4682b4",
          secondary: "#b0bec5",
          accent: "#8c9eff",
          error: "#b71c1c",
        },
      },
})

const opts = {}

export default new Vuetify(opts)

// src/plugins/Vuetify.js
