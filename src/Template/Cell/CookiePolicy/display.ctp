<?php
    $this->Html->script('/bower_components/js-cookie/src/js.cookie.js', ['once' => true, 'block' => 'js_foot']);
?>
<style>
    #cookie-policy {
        opacity:0.88;

        position:fixed;
            bottom:0;left:0;right:0;
            z-index:9999;
        background-color:#00adee;
        color:white;
        padding:4px;

        border-top-left-radius:5px;
        border-top-right-radius:5px;
    }

    #cookie-policy:hover {opacity:1;}

    #cookie-policy img.cookie {margin-right:10px}
    #cookie-policy p {font-weight:bold}
</style>

<?php if (!$is_accepted) : ?>
<section class="container animate animate-pulse" id="cookie-policy">
    <div class="row">
        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
            <p class="pull-left no-margin font-size-md2">
                <?php echo __('Questo sito utilizza cookie, continuando la navigazione ne accetti le norme e le condizioni') ?>
                <?php echo
                    __(
                        '{linkStart}normativa{linkEnd}',
                        [
                            'linkStart' => '<a href="'.$this->Url->build(['controller' => 'Pages', 'action' => 'display', 0 => 'cookie-policy']).'">',
                            'linkEnd'   => '</a>'
                        ]
                    )
                ?>
            </p>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <a href="#" id="accept-cookie-policy" class="btn btn-block btn-xs btn-success">
                <?= __('OK') ?>
            </a>
        </div>
    </div>
</section>
<script>
    $(function() {
        $("#accept-cookie-policy").on("click", function(evt) {
            evt.preventDefault();

            $("#cookie-policy").fadeOut("fast", function(evt) {
                Cookies.set("cookie_policy_accept", "true", {expires:1000});
            });
        });
    });
</script>
<?php endif ?>
