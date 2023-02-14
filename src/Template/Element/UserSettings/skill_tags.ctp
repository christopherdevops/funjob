<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Competenze tecniche'));
$this->assign('subtitle', __('Parole chiave professionali (potrai essere ricercato tramite queste parole)'));
$this->assign('tab', 'skills');
?>

<?php
    // Righe successive vengono nascoste con il collapse
    //$collapseAfter = 5;
?>

<div id="user-skill-tags">
    <div class="alert alert-sm alert-info">
        <p class="no-margin" style="line-height:2em">
            <i class="fa fa-2x fa-tag"></i>
            <?= __('Cerca di usare termini più tecnici possibile (tags) in modo che le aziende possano trovarti') ?>
            <br>
            <?= __('Sarai trovato dalle aziende attraverso la ricerca utenti tramite queste competenze tecniche') ?>
        </p>
    </div>

    <?php for ($i=0; $i < 60; $i++) : //$collapseAfter-- ?>
        <?php
            $entitySet    = isset($User->user_skills[$i]) ? $User->user_skills[$i]['name'] : null;
            $dataSet      = $this->request->getData('user_skills.' .$i. '.name');
            $notCollapsed = $entitySet || $dataSet;

            if ($i == 0) {
                $notCollapsed = true;
            }
        ?>

        <div class="well well-sm user-skill-tags <?= !$notCollapsed ? 'hidden' : '' ?>">
            <?php
                echo $this->Form->control('user_skills.' .$i. '.id', [
                    'type'  => 'hidden',
                    'value' => $this->request->data('user_skills.' .$i. '.id')
                ]);
            ?>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                        echo $this->Form->control('user_skills.' .$i. '.name', [
                            'align' => 'left',
                            //'value' => $this->request->data('user_skills.' .$i. '.id'),
                            'label' => __('Competenza tecnica (parola chiave)'),
                            'help'  => __('Lascia il campo vuoto per eliminare questa competenza')
                        ]);
                    ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                        echo $this->Form->control('user_skills.' .$i. '.perc', [
                            'label'   => __('Livello di conoscienza'),
                            'align'   => 'right',
                            'type'    => 'number',
                            'min'     => 1,
                            'max'     => 100,
                            'default' => '',
                            'append'  => '<i class="fa fa-percent"></i>',
                            'help'    => __('Valore numero in percentuale'),
                            'default' => 50
                            //'value'   => $this->request->data('user_skills.' .$i. '.id')
                        ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php
                    ?>
                </div>
            </div>

        </div>
    <?php endfor ?>

    <hr>
    <button type="button" class="btn" id="user-skills-add-btn">
        <i class="fa fa-plus"></i>
        <?php echo __('Aggiungi nuova competenza') ?>
    </button>
    <script>
        $(function() {
            $("#user-skills-add-btn").on("click", function(evt) {
                evt.preventDefault();
                var el = $(".user-skill-tags.hidden").get(0);
                if (el) {
                    var $el = $(el).closest('.well');
                    console.log($el);
                    $el.removeClass("hidden");
                }
            });
        });
    </script>

</div>
