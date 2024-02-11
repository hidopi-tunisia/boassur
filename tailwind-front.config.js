const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: [
    "./resources/views/front/**/*.blade.php",
    "./resources/js/front/**/*.js",
    "./resources/js/front/**/*.jsx",
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    theme: {
      extend: {
        colors: {
          presence: "#015b99",
        },
        fontFamily: {
          sans: ["Red Hat Display", ...defaultTheme.fontFamily.sans],
        },
      },
    },
  },
  variants: {
    extend: {
      opacity: ["disabled"],
    },
  },
  plugins: [require("@tailwindcss/forms"), require("@tailwindcss/typography")],
};
