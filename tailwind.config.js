/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./resources/**/*.{vue,js,ts,jsx,tsx}"
  ],
  theme: {
    extend: {
      fontFamily: {
        'nunito': ['Nunito', 'sans-serif']
      },
      colors: {
        'sidebar-bg': '#FBFBFB',
        'custom-black': '#212121',
        'custom-blue': '#5080C7',
        'custom-red': '#FD0000',
        'custom-green': '#31C48D',
        'custom-gray': '#4c4c4c',
        'custom-light-blue': '#EFF3F6',
        'custom-light-blue-bg': '#E8F0FE',
        'custom-dark-blue1': '#172340',
        'custom-dark-blue2': '#744A6D',
        'custom-orange': '#F59C4A',
        'custom-yellow': '#F5F06E',
        'custom-bg': '#F5F5F5',
        'custom-light': '#DEDEDE',
        'custom-hover-gray': '#ececec',
        'custom-light-gray': '#F9F9F9'
      },
      boxShadow: {
      'newdrop': 'rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;',
      },
      animation: {
        'shadow-pop': 'shadow-pop-tr 0.3s cubic-bezier(0.470, 0.000, 0.745, 0.715) both',
        'vibrate-animate': 'vibrate-kf 0.3s linear infinite both'
      },
      keyframes: {
        'shadow-pop-tr': {
          '0%': {
            'box-shadow': '0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e',
            transform: 'translateX(0) translateY(0)'
          },
          "100%": {
            'box-shadow': '1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px -5px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e',
            transform: 'translateX(-8px) translateY(8px)'
          }
        },
        'vibrate-kf': {
          "0%": {
            transform: 'translate(0)'
          },
          "20%": {
            transform: 'translate(-2px, 2px)'
          },
          '40%': {
            transform: 'translate(-2px, -2px)'
          },
          '60%': {
            transform: 'translate(2px, 2px)'
          },
          '80%': {
            transform: 'translate(2px, -2px)'
          },
          '100%': {
            transform: 'translate(0)'
          }
        }
      }
    },
    screens: {
      'sm': '640px',
      // => @media (min-width: 640px) { ... }

      'md': '768px',
      // => @media (min-width: 768px) { ... }

      'lg': '1024px',
      // => @media (min-width: 1024px) { ... }

      'xl': '1280px',
      // => @media (min-width: 1280px) { ... }

      '2xl': '1536px',
      // => @media (min-width: 1536px) { ... }
    },
  },
  plugins: [
    require("@tailwindcss/forms")
  ],
}