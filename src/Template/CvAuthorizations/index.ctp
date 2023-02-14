<?php
/**
  * Archivio CvAuthorizations
  *
  * E' suddiviso in 3 tabs, ogniuna è un sotto archivio (utilizza /cv-authorizations/filter/:status)
  *
  * @var \App\View\AppView $this
  */

    $this->assign('title', __('Autorizzazioni CV'));
    $this->Html->script([
        '/bower_components/blockUI/jquery.blockUI.js',
        '/bower_components/matchHeight/dist/jquery.matchHeight-min.js'
    ], ['block' => 'js_foot']);
    $this->Html->css(['features/box-icon.css'], ['block' => 'css_head']);
?>

<?php $this->start('loader') ?>
    <script id="tpl-loader" type="text/template">
        <i class="text-color-primary fa fa-spinner fa-pulse fa-5x fa-fw"></i>
        <span class="sr-only"><?= __('Attendi ...') ?></span>
    </script>
<?php $this->end() ?>

<?php $this->append('css_head--inline') ?>
    div.tab-pane {
        min-height:300px !important;
        padding-top:30px;
    }

    <?php // Overrides: features/box-icon.css ?>
    .box-icon {
        background-color: whitesmoke;
    }

        .box-icon.sm {
            height:80px;
            width:80px;
        }
        .box-icon.xs {
            height:40px;
            width:40px;
        }

    .box-icon .fa {
        color:#00adee !important;
    }
<?php $this->end() ?>

<?php // VISIBILITÀ CV ?>
<?php if (!empty($user_cv->account_info->cv) && $user_cv->account_info->public_cv === true) : ?>
<div class="alert alert-info">
    <?= __('Sai che puoi limitare l\'accesso al tuo CV?') ?>
    <?= __('Cosi potrai autorizzare gli utenti manualmente') ?>
</div>
<?php endif ?>

<?php // VISIBILITÀ CV #2 ?>
<?php if (empty($user_cv->account_info->cv)) : ?>
<div class="alert alert-info">
    <?= __('Se non vuoi condividere il tuo CV puoi renderlo privato e permetterne la visione solo a chi autorizzi.') ?>
</div>
<?php endif ?>

<div class="row">
    <div class="pull-right">
        <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'job']) ?>">
            <i class="fa fa-cogs"></i>
            <?= __('Impostazioni CV') ?>
        </a>
    </div>
</div>

<div role="tabpanel" id="cv-authorizations-archive">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#dashboard" aria-controls="pending" role="tab" data-toggle="tab">
                <i class="fa text-muted fa-tachometer"></i>
                <span class="hidden-xs"><?= __('Stato autorizzazioni') ?></span>
            </a>
        </li>

        <li role="presentation">
            <a href="#pending" aria-controls="pending" role="tab" data-toggle="tab">
                <i class="fa text-muted fa-question"></i>
                <span class="hidden-xs"><?= __('In attesa') ?></span>
            </a>
        </li>
        <li role="presentation">
            <a href="#allowed" aria-controls="allowed" role="tab" data-toggle="tab">
                <i class="fa text-success fa-check"></i>
                <span class="hidden-xs"><?= __('Autorizzati') ?></span>
            </a>
        </li>
        <li role="presentation">
            <a href="#denied" aria-controls="denied" role="tab" data-toggle="tab">
                <i class="fa text-danger fa-remove"></i>
                <span class="hidden-xs"><?= __('Non autorizzati') ?></span>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="dashboard">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    <div class="box">
                        <div class="box-icon sm">
                            <span class="fa fa-4x fa-question"></span>
                        </div>
                        <div class="info">
                            <h4 class="font-size-lg text-center">
                                <?= __('{0} in attesa', $counter['pending']) ?>

                            </h4>
                            <p class="font-size-md">
                                <?= __('Utenti che attendono un tuo responso per poter visualizzare il tuo CV') ?>
                            </p>
                        </div>
                        <div class="footer">
                            <hr>
                            <button onclick="$('a[role=tab][href*=pending]').tab('show');return false" class="btn btn-sm btn-block btn-primary">
                                <?= __('Autorizza') ?>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    <div class="box">
                        <div class="box-icon sm">
                            <span class="fa fa-4x fa-eye"></span>
                        </div>
                        <div class="info">
                            <h4 class="font-size-lg text-center">
                                <?= __('{0} autorizzati', $counter['granted']) ?>
                            </h4>
                            <p class="font-size-sm">
                                <?= __('Utenti che possono accedere al tuo CV') ?>
                            </p>
                        </div>
                        <div class="footer">
                            <hr>
                            <button onclick="$('a[role=tab][href*=allowed]').tab('show');return false" class="btn btn-sm btn-block">
                                <?= __('Mostra') ?>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    <div class="box">
                        <div class="box-icon sm">
                            <span class="fa fa-4x fa-low-vision"></span>
                        </div>
                        <div class="info">
                            <h4 class="font-size-lg text-center">
                                <?= __('{0} bloccati', $counter['denied']) ?>
                            </h4>
                            <p class="font-size-sm">
                                <?= __('Utenti che non possono accedere al tuo CV') ?>
                            </p>
                        </div>
                        <div class="footer">
                            <hr>
                            <button onclick="$('a[role=tab][href*=denied]').tab('show');return false;" class="btn btn-sm btn-block">
                                <?= __('Mostra') ?>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="pending">
            <noscript class="text-danger">
                <?= __x('JS disattivato', 'Attiva JavaScript') ?>
            </noscript>
        </div>
        <div role="tabpanel" class="tab-pane" id="allowed">
            <noscript class="text-danger">
                <?= __x('JS disattivato', 'Attiva JavaScript') ?>
            </noscript>
        </div>
        <div role="tabpanel" class="tab-pane" id="denied">
            <noscript class="text-danger">
                <?= __x('JS disattivato', 'Attiva JavaScript') ?>
            </noscript>
        </div>
    </div>
</div>

<?= $this->fetch('loader') ?>
<script>
    var UI_AJAX_TAB = (function (settings) {
        var $req = undefined;
        var api  = {};
        var _api = {};

        api.load = function(ajaxSettings) {
            //if (ajaxSettings.beforeSend == undefined) {
            ajaxSettings.beforeSend = api.ajaxLoader;
            //}

            $req = $.ajax(ajaxSettings);

            $req.done(api.ajaxSuccess);
            $req.fail(api.ajaxFailure);
            $req.always(api.ajaxAlways);

            return $req;
        };

        api.ajaxSuccess = function(response) {
            console.log(this);
            this.html(response);
        };

        /* Ajax error / http error */
        api.ajaxFailure = function(jxhr, errStatus, errThrow) {
            console.log(this);
            alertify.error(errStatus);
        };

        api.ajaxAlways = function() {
            this.unblock();
        };

        /* Tab loader */
        api.ajaxLoader = function() {
            this.block({
                message: document.querySelector("#tpl-loader").innerHTML,
                css: {
                    display         : "block",
                    width           : "100%",
                    height          : "100%",
                    border          : 0,
                    backgroundColor : "transparent",
                    color           : "#f0f0f0",
                    opacity         : 0.10
                }
            });
        }

        return api;
    });

    $(function() {

        $('.box .info').matchHeight({});


        $("#cv-authorizations-archive *[role=tab]").on("show.bs.tab", function(evt) {
            var target = $(evt.target).attr("href"); // activated tab
            var $tab   = $(target);

            $tab.trigger("ajaxLoad.tab.funjob");
        });

        // AJAX TABS
        $("#pending").on("ajaxLoad.tab.funjob", function(evt) {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#pending"),
                url      : "<?= $this->Url->build(['_name' => 'cv:authorizations:filter', 0 => 'pending']) ?>",
                timeout  : 10000 // in ms
            });
        });

        $("#allowed").on("ajaxLoad.tab.funjob", function(evt) {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#allowed"),
                url      : "<?= $this->Url->build(['_name' => 'cv:authorizations:filter', 0 => 'allowed']) ?>",
                timeout  : 10000 // in ms
            });
        });

        $("#denied").on("ajaxLoad.tab.funjob", function(evt) {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#denied"),
                url      : "<?= $this->Url->build(['_name' => 'cv:authorizations:filter', 0 => 'denied']) ?>",
                timeout  : 10000 // in ms
            });
        });

        // PAGINAZIONE AJAX SU TAB CORRENTE
        $("body").on("click", "ul.pager a", function(evt) {
            evt.preventDefault();

            if ($.trim(this.href) == "") {
                return false;
            }

            var $tab = $(".tab-pane.active");
            var tab  = UI_AJAX_TAB();

            tab.load({
                context  : $tab,
                url      : this.href,
                timeout  : 10000 // in ms
            });
        });

    });
</script>

<script>
    $("body").on("click", ".js-cv_authorizations-status button", function(evt) {
        var $thisBtn  = $(this);
        var $btnGroup = $(this).closest('.btn-group');
        var $btnForm  = $thisBtn.next('.js-cv_authorization-form').children('form');

        if ($btnForm.length == 0) {
            return false;
        }

        var $req = $.ajax({
            url    : $btnForm.prop("action"),
            method : "PUT",
            data   : $btnForm.serialize()
        });

        $req.done(function(response) {
            var $entityDiv = $btnGroup.closest('.js-cv_authorization-entity');
            alertify.success(<?= json_encode(__x('Autorizzazione CV modificata di stato', 'Autorizzazione salvata')) ?>);
            $entityDiv.fadeOut("fast");
        });

        $req.fail(function(errStatus, errText, errThrow) {
            alertify.error(errText.message);
        });

        $req.always(function(errStatus, errText, errThrow) {
        });

    });
</script>
