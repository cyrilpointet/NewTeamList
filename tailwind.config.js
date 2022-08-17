module.exports = {
  content: ["./index.html", "./resources/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: "var(--color-primary)",
        "primary-contrast": "var(--color-primary-contrast)",
        "primary-light": "var(--color-primary-light)",
        error: "var(--color-error)",
      },
    },
  },
  plugins: [],
};
