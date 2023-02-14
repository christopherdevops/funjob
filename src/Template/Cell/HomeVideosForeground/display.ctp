<?php
    echo $this->Html->css(['home/widget-foreground-video']);
?>

<div class="panel panel-sm panel-info">
    <div class="panel-heading">
        <div class="panel-title font-size-md2">
            <i class="fa fa-play"></i>
            <?= __('Giochi: Tutorial e Social') ?>
        </div>
    </div>
    <div class="panel-body">
        <div id="youtube-players" class="row gutter-10">
            <?php foreach ($videos as $i => $video) : ?>
                <div class="col-sm-6 col-md-6 col-lg-6">

                    <?php if ($this->request->is('mobile') || $this->request->is('tablet')) : ?>
                    <div class="funjob-home-block-foreground-video-player youtube-player">
                        <a href="#" class="js-funjob-home-block-foreground-video-player-link-mobile" data-template="#<?= 'funjob-home-block-foreground-video' .$i. '-player-template' ?>">
                            <div class="funjob-home-block-foreground-video-title font-size-md2 text-truncate">
                                <i class="text-color-gray--dark pull-left fa fa-file-video-o"></i>
                                <span class="text-color-primary">
                                    <?= $video['title'] ?>
                                </span>
                            </div>
                        </a>
                    </div>
                    <script type="text/template" id="funjob-home-block-foreground-video<?= $i ?>-player-template">
                        <?php echo $video['embed'] ?>
                    </script>
                    <?php else: ?>
                        <div class="funjob-home-block-foreground-video-player youtube-player">
                            <?php echo $video['embed'] ?>
                        </div>
                        <a href="<?= $video['href'] ?>" class="btn btn-block btn-xs btn-info">
                            <i class="fontello-quiz-play"></i>
                            <?php if ($i == 0) : ?>
                                <?= __('Gioca Quiz Tutorial') ?>
                            <?php else: ?>
                                <?= __('Gioca Social Quiz') ?>
                            <?php endif ?>
                        </a>
                        <div class="visible-xs-block margin-top--md"></div>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>

        <hr class="visible-md visible-lg funjob-home-block-foreground-video-spacer">
        <script type="text/template" id="funjob-home-block-foreground-popover">
            <p class="font-size-md2 text-color-primary">
                <?php echo __('Inserisci il tuo Video Tutorial YouTube nel tuo profilo FUNJOB e crea un quiz ad esso riferito.') ?>
                <br>
                <?php echo __('Guadagnerai {pix} trasformabili in premi quando i tuoi amici o utenti lo giocheranno.', [
                    'pix' => '<span style="color:#ffc300"><i class=\'fontello-credits\'></i> PIX</span>']) ?>
                <br>
                <?php echo __('I migliori saranno inseriti in Home Page.') ?>
            </p>

            <div class="margin-top--md"></div>
            <a href="/contacts?type=info&subject=<?= __('Proposta gioco home') ?>" class="btn btn-sm btn-default btn-block">
                <i class="fa fa-envelope text-color-primary"></i>
                <?= __('Proponi gioco su questo box') ?>
            </a>
        </script>
        <script>
            $(function() {
                $(".js-homevideo-popover-trigger").on("click", function(evt) {
                    bootbox.dialog({
                        className : "funjob-modal",
                        title     : <?= json_encode('Proponi il tuo gioco qui') ?>,
                        message   : function() {
                            return $("#funjob-home-block-foreground-popover").html();
                        }
                    });
                });
            });
        </script>
        <a data-toggle="popover" href="#" class="btn btn-default btn-xs btn-block js-homevideo-popover-trigger">
            <div class="text-truncate">
                <i class="text-color-primary fa fa-info-circle"></i>
                <?= __('Proponi il tuo quiz') ?>
            </div>
        </a>

    </div>
</div>

<?php if ($this->request->is('mobile') || $this->request->is('tablet')) : ?>
<script type="text/javascript">
    $(function() {
        $(".js-funjob-home-block-foreground-video-player-link-mobile").on("click", function(evt) {
            evt.preventDefault();
            var $tpl = $( $(this).data("template") );
            var modal;

            var modal = bootbox.dialog({
                classname: "funjob-modal",
                message: function() {
                    alert("fuuu");
                    return $tpl.html();
                }
            })


        });
    })
</script>
<?php endif ?>

