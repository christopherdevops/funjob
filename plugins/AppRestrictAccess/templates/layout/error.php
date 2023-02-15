<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <style type="text/css">
            html,body{background-color:#00adee;color:white;font-size:15px;font-weight:bold}
            #header {text-align:center}
        </style>
    </head>
    <body>
        <div class="container">

            <div class="row" id="header">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <img src="logo.png" alt="">
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <?= $this->fetch('content') ?>
                </div>
            </div>
        </div>

    </body>
</html>
