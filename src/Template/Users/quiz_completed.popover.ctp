<?php
    $scores = 0;
?>

<?php // POPOVER punteggi ?>
<script type="text/template" id="popover-content-<?= $session->id ?>-score-<?= $i ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($levels as $level) : $key = 'level_' . $level ?>
                    <i class="fa fa-check <?= !empty($session[$key]) && $session[$key] >= 6 ? 'fa-check--completed' : '' ?>"></i>
                    <span><?php echo __('Livello {0}', $level) ?></span>

                    <?php if (!empty($session[$key])) : ?>
                        <span class="text-muted"><?= __('+{0} punti', $session[$key]) ?></span>
                    <?php else: ?>
                        <span class="text-muted">N/D</span>
                    <?php endif ?>

                    <br>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</script>

<?php // POPOVER initializzazione ?>
<script type="text/javascript">
    $("#popover-<?= $session->id ?>-score-<?= $i ?>").popover({
        html    : true,
        trigger : "hover click",
        content : function () {
            var $popover = $("#popover-content-<?= $session->id ?>-score-<?= $i ?>");
            return $popover.html();
        }
    });
</script>
