$(function() {

    $("body").popover({
        selector  : ".level-popover",
        html      : true,
        container : "body",
        trigger   : "hover click",
        placement : "auto",
        content   : function() {
            if (this.classList.contains("stepwizard-btn--passed")) {
                return document.querySelector('#tpl-level-passed').innerHTML;
            } else {
                return document.querySelector('#tpl-level-locked').innerHTML;
            }
        }
    });

    $(".funjob-quiz-level").hover(
        function() {
            $(this).find(".content").hide().next().show();
        },
        function() {
            $(this).find(".content--hover").hide().prev().show();
        }
    );

    // Filtri:
    $("body").on("submit", ".quiz-completed-form-filter", function(evt) {
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

    // Visibilità quiz
    $("body").on("click", ".js-quiz-session-visibility-btn", function(evt) {
        var $this = $(this);
        evt.preventDefault();

        bootbox.confirm(i18n.confirmMessage, function(reply) {
            if (reply == true) {
                var $form = $("form", $this);
                if ($form.length) {
                    $form.trigger("submit");
                }
            }
        });
    });

    // Visibilità quiz
    $("body").on("click", ".js-quiz-session-delete-btn", function(evt) {
        var $this = $(this);
        evt.preventDefault();

        bootbox.confirm(i18n.game_session.confirmDelete, function(reply) {
            if (reply == true) {
                window.location.url = this.href;
            }
        });
    });

});
