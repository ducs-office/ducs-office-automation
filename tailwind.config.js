module.exports = {
    theme: {
        customForms: theme => ({
            default: {
                "checkbox, radio": {
                    color: theme("colors.magenta.600")
                },
                "input, select, multiselect, textarea, checkbox, radio": {
                    "&:disabled": {
                        backgroundColor: theme("colors.gray.200"),
                        cursor: "not-allowed"
                    },
                    "&:hover, &:focus, &:focus-within": {
                        borderColor: theme("colors.magenta.600")
                    },
                    "&:focus, &:focus-within": {
                        boxShadow: "0 0 3px 1px rgba(118, 0, 118, 0.4)"
                    }
                }
            }
        }),
        container: {
            center: true,
            padding: "2rem"
        },
        extend: {
            colors: {
                white: {
                    default: "#fff",
                    "10": "rgba(255, 255, 255, .1)",
                    "20": "rgba(255, 255, 255, .2)",
                    "30": "rgba(255, 255, 255, .3)",
                    "40": "rgba(255, 255, 255, .4)",
                    "50": "rgba(255, 255, 255, .5)",
                    "60": "rgba(255, 255, 255, .6)",
                    "70": "rgba(255, 255, 255, .7)",
                    "80": "rgba(255, 255, 255, .8)",
                    "90": "rgba(255, 255, 255, .9)"
                },
                black: {
                    default: "#000",
                    "10": "rgba(0, 0, 0, .1)",
                    "20": "rgba(0, 0, 0, .2)",
                    "30": "rgba(0, 0, 0, .3)",
                    "40": "rgba(0, 0, 0, .4)",
                    "50": "rgba(0, 0, 0, .5)",
                    "60": "rgba(0, 0, 0, .6)",
                    "70": "rgba(0, 0, 0, .7)",
                    "80": "rgba(0, 0, 0, .8)",
                    "90": "rgba(0, 0, 0, .9)"
                },
                magenta: {
                    900: "#3f0f3f",
                    800: "#4e004e",
                    700: "#760076",
                    600: "#b100b1",
                    500: "#eb00eb",
                    400: "#ff27ff",
                    300: "#ff76ff",
                    200: "#ffc4ff",
                    100: "#ffebff"
                }
            },
            width: {
                "72": "18rem",
                "80": "20rem",
                "96": "24rem"
            },
            maxWidth: {
                "16": "4rem",
                "32": "8rem",
                "64": "16rem"
            },
            minWidth: {
                "32": "8rem",
                "48": "12rem",
                "64": "16rem",
                "1/2": "50%",
                "1/3": "33.333333%",
                "2/3": "66.666666%",
            },
            height: {
                current: "1em"
            },
            minHeight: {
                "12": "3rem"
            },
            maxHeight: {
                "24": "6rem",
                "32": "8rem",
                "40": "10rem",
                "48": "12rem",
                "56": "14rem",
                "64": "16rem",
                "screen-1/2": "50vh"
            }
        }
    },
    variants: {
        textColor: ["responsive", "focus", "hover", "group-hover"],
        backgroundColor: [
            "responsive",
            "focus",
            "hover",
            "group-hover",
            "first",
            "last"
        ],
        borderWidth: ["responsive", "hover", "first", "last"],
        borderColor: ["responsive", "hover", "first", "last"],
        borderRadius: ["responsive", "hover", "first", "last"],
        display: ["responsive", "focus", "hover", "group-hover"]
    },
    plugins: [require("@tailwindcss/custom-forms")]
};
