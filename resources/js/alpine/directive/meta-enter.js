export default (el, { modifiers, expression }, { cleanup, evaluate }) => {
    let handleKeydown = (event) => {
        if (event.key === "Enter" && (event.metaKey || event.ctrlKey)) {
            if (modifiers.includes("prevent")) {
                event.preventDefault();
            }
            if (modifiers.includes("stop")) {
                event.stopPropagation();
            }
            evaluate(expression);
        }
    };

    window.addEventListener("keydown", handleKeydown);

    cleanup(() => {
        window.removeEventListener("keydown", handleKeydown);
    });
};
