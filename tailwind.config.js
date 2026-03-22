/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
theme: {
  extend: {
    colors: {
      'rc-dark': '#1A1A1A',     // Preto quase puro para textos e botões principais
      'rc-green': '#C2F2D9',    // O verde da sua marca
      'rc-blue': '#2D4483',     // O azul para destaques e links
      'rc-hover': '#000000',    // Preto total para o hover
    },
  },
},
  plugins: [],
}