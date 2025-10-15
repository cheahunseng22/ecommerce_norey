/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      boxShadow: {
        'outline': '0 0 0 3px rgba(66, 153, 225, 0.5)', // Custom outline shadow
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}

