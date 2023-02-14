$(function() {
    // Rimuove alert, e imposta cookie per non mostrarlo nelle visite successive
    $("body").on("click", ".alert-remember *[data-dismiss]", function(evt) {
        var $this  = $(this);
        var $alert = $this.prev(".alert");
        var cookie = $this.data("cookie");

        if (!cookie) {
            return false;
        }

        // Verificare esistenza cookie
        Cookies.set(cookie, false, {expires:10000});
    });
});
