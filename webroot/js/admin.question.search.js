$(function() {
    // Modale
    $(".js-admin-question-modal").on("click", function() {
        var $ajax = $.ajax({
            method : "GET",
            url    : config.adminPanel.url,
            beforeSend: function(jxhr) {
                $.blockUI({
                    timeout : 40000,
                    message : "Waiting ..."
                });
            }
        });
        $ajax.done(function(response) {
            var modal = bootbox.dialog({
                className : "funjob-questions-modal funjob-modal",
                size      : "large",
                title     : "Ricerca ...",
                message   : response
            });

            // Defect: campo di ricerca select2 non editable con tabindex su modale
            modal.removeAttr("tabindex");
        });
        $ajax.fail(function(jxhr, textResponse, exception) {
            alertify.error(textResponse);
        });
        $ajax.always(function() {
            $.unblockUI();
        })
    });


    // Form filter
    $("body").on("submit", ".js-filter-form", function(evt) {
        evt.preventDefault();

        var $this    = $(this);
        var formData = $this.serialize();
        var $req = $.ajax({
            method     : "POST",
            url        : $this.attr("action"),
            data       : formData,
            headers    : {
                "X-Csrf-Token" : config.csrfToken
            },
            beforeSend : function(jxhr) {
                $.blockUI({timeout: 60000, message: "WAITING ..."});
            }
        });

        $req.done(function(response) {
            $(".funjob-questions-modal .modal-body .bootbox-body").html(response);
        });
        $req.fail(function(jxhr, textResponse, exception) {
            alertify.error(textResponse);
        });

        $req.always(function() {
            $.unblockUI();
        });
    });


    // Paginazione ajax
    $("body").on("click", "li.next a[rel], li.previous a[rel]", function(evt) {
        evt.preventDefault();

        var $this = $(this);
        var url   = $this.attr("href");

        if (url == undefined) {
            return false;
        }

        $(".funjob-questions-modal .modal-body .bootbox-body").load(url);
    });


    $("body").on("submit", ".js-import-question-form", function(evt) {
        evt.preventDefault();
        var $this = $(this);
        var confirmation = confirm("Procedere?");

        if (!confirmation) {
            return false;
        }

        var $req = $.ajax({
            method     : "POST",
            url        : $this.attr("action"),
            data       : $this.serialize(),
            headers    : {
                "X-Csrf-Token": config.adminPanel.csrfToken
            },
            beforeSend : function() {
                $.blockUI({ });
            }
        });

        $req.done(function(response, textStatus, jxhr) {
            //var json = $.parseJSON(response);
            if (response.message) {
                alertify.success(response.message);
            }
        });
        $req.fail(function(jxhr, textStatus, exception) {
            var response = $.parseJSON(jxhr.responseText);
            if (response.message) {
                alertify.error(response.message);
            }
        });

        $req.always(function() {
            $.unblockUI();
        });

    });
});
