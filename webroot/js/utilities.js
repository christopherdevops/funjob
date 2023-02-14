/**
 * Determina quale classe del grid system di bootstrap è quella corrente
 */
window.bootstrap_class = function() {
    width = window.innerWidth;

    if (width >= 1200) {
        return "lg";
    } else if (width >= 992) {
        return "md";
    } else if (width >= 768) {
        return "sm";
    } else {
        return "xs";
    }
};
