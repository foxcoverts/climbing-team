import { AsYouType, validatePhoneNumberLength } from "libphonenumber-js";

export default (el) => {
    return (input) => {
        if (input.length) {
            switch (validatePhoneNumberLength(input, "GB")) {
                case "NOT_A_NUMBER":
                case "INVALID_LENGTH":
                    el.setCustomValidity("Please enter a valid phone number.");
                    break;
                case "INVALID_COUNTRY":
                    el.setCustomValidity("Country is not recognised.");
                    break;
                case "TOO_SHORT":
                    el.setCustomValidity(
                        "The entered phone number is too short."
                    );
                    break;
                case "TOO_LONG":
                    el.setCustomValidity(
                        "The entered phone number is too long."
                    );
                    break;
                default:
                    el.setCustomValidity("");
            }
        } else {
            el.setCustomValidity("");
        }

        const type = new AsYouType("GB");
        type.input(input);
        return type.getTemplate().replaceAll("x", "9");
    };
};
