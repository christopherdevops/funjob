<?php
    $this->Html->css([
        '/bower_components/bootstrap/dist/css/bootstrap.min.css',
        '/bower_components/animate.css/animate.min.css',
        '/bower_components/font-awesome/css/font-awesome.min.css',
    ], ['block' => 'css']);
?>
<html>
    <head>
        <title><?= $this->fetch('title') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="<?= $this->Url->build('/') ?>" />

        <?php echo $this->fetch('css') ?>

        <style type="text/css">
            html,body{background-color:#00adee;color:white;font-size:15px;font-weight:bold}
            #header {text-align:center}
        </style>
    </head>
    <body>
        <div class="container">

            <div class="row" id="header">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <img src="logo.png" alt="">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <a style="color:white;font-weight:bold;" href="javascript:history.back();">
                            <i class="fa fa-arrow-left"></i>
                            <?= __('Torna dietro') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div style="margin-top:40px" class="visible-md visible-lg">
                </div>
                <hr>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <?= $this->fetch('content') ?>
                    </div>
                    <div class="text-center" style="font-size:10vh">
                        <i class="fa fa-frown-o" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
