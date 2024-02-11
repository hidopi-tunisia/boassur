const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: [
    "./resources/views/admin/**/*.blade.php",
    "./resources/views/components/**/*.blade.php",
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      fontFamily: {
        sans: ["Nunito Sans", ...defaultTheme.fontFamily.sans],
        cse: ["Red Hat Display", ...defaultTheme.fontFamily.sans],
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
