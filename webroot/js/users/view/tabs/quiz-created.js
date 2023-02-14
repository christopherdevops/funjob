$(function() {

    // Filtri:
    $("body").on("submit", ".quiz-created-form-filter", function(evt) {
        evt.preventDefault();
        var $form = $(this);

        var $req = $.ajax({
            method     : "POST",
            url        : this.action,
            data       : $form.serialize(),
            beforeSend : function() {
                $.blockUI();
            }
        });

        $req.done(function(response) {
            $(".user-profile-tabs .tab-pane.active").html(response);
            Holder.run({});
        });

        $req.fail(function(jxhr, textError, error) {
            alertify.error(textError);
        });

        $req.always(function() {
            $.unblockUI();
        });

    });

});
