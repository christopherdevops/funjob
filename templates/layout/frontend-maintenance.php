<html>
    <head>
        <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">

        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <style type="text/css">
            html,body{background-color:#00adee !important;color:white;}
            .admin-box {display:block;position:fixed;top:0;right:0;color:#00adee !important;font-size:4vw;}
            #__debug_kit, iframe {display:none;}
            .modal {color:black;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <a class="js-confirm" href="#">
                            <img style="width:10vw" src="logo.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php echo $this->fetch('content') ?>
                </div>
            </div>
        </div>


        <form id="js-admin-panel" action="#">
            <input class="js-admin-keyword admin-box" type="text" style="width:40%;background-color:transparent;border:0;color:#00adee;outline:none !impoortant;">
        </form>
        <script id="admin-login" type="text/template">
            <?php
                echo $this->Form->create(null, ['url' => ['_name' => 'auth:login']]);
                echo $this->Form->control('username', [
                    'label'       => false,
                    'placeholder' => __('Nome utente'),
                    'help'        => __('Solo gli addetti ai lavori possono momentaneamente accedere')
                ]);
                echo $this->Form->control('password', [
                    'label'       => false,
                    'placeholder' => __('Password')
                ]);
                echo $this->Form->button(__('Accesso admin'));
                echo $this->Form->end();
            ?>
        </script>



        <script src="/bower_components/bootbox.js/bootbox.js"></script>
        <script>
            $(function() {
                $(".js-confirm").on("click", function(evt) {
                    bootbox.dialog({
                        title   : <?= json_encode(__('Accesso')) ?>,
                        message : document.querySelector("#admin-login").innerHTML
                    });
                });
            });
        </script>
    </body>
</html>
