<?php
    use Cake\Core\Configure;

    $this->assign('title', __('Crea una nuova domanda'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('I miei quiz'), ['_name' => 'me:quizzes'])
        ->add($quiz->title, ['controller' => 'quizzes', 'action' => 'edit', $quiz->id])
        ->add(__('Crea domanda'));
?>

<?php $this->append('css_head--inline') ?>
    .well--padding-sm {
        padding:10px !important;
    }

    .well h1 {
        margin:0 !important;
    }


    fieldset[disabled="disabled"] {
        display:none;
    }

    .well--border-radius-top-none {
        border-top-left-radius:0 !important;
        border-top-right-radius:0 !important;
    }

    .well--tab {
        background-color:inherit !important;
        border-top:0 !important;
    }

    /* bootstrap tab radio */
    a[role="tab"] {
        padding:10px !important;
    }
    .form-group.radio,
    .form-group.radio .radio
    {
        margin:0 !important;
    }


    .dl-horizontal dt {
        margin:0 !important;
        max-width:20px;
        text-align:left;
    }
    .dl-horizontal dd {margin:0 !important;}
<?php $this->end() ?>

<script>
    var config = {};
</script>

<?php // CONTATORI DOMANDE RIMANENTI ?>
<?php $this->start('toolbar') ?>
    <?php echo $this->element('frontend-quiz-editor-toolbar') ?>
<?php $this->end() ?>

<?php $this->start('tab:add') ?>
    <?php echo $this->Form->create($quizQuestion, ['valueSources' => ['context'], 'type' => 'file']); ?>

    <?php // Necessario per gestire directory upload (fonte) perchè uploadBehavior non può utilizzare {primaryKey} per entity da creare ?>
    <?php echo $this->Form->hidden('uuid', ['value' => $uuid]) ?>

    <fieldset>
        <?php
            echo $this->Form->hidden('quiz_id', ['value' => $this->request->pass[0]]);
            echo $this->Form->input('type', [
                'type'    => 'radio',
                'label'   => false,
                'default' => 'default',
                'options' => [
                    'default'       => __('Domanda con 4 risposte chiuse'),
                    'true_or_false' => __('Domanda con risposta vera o falsa')
                ]
            ]);

            echo $this->Form->input('question', [
                'type'        => 'textarea',
                'label'       => __('Titolo domanda'),
                'max'         => 100,
                'help'        => __('Termina la tua domanda con il punto interrogativo'),
                'placeholder' => __(
                    'Scrivi la tua domanda in massimo {max_chars} caratteri',
                    ['max_chars' => Configure::read('app.quizQuestion.maxChars')]
                )
            ]);
            echo $this->Form->input('complexity', [
                /*
                'prepend' => '0 - 10',
                'type'    => 'number',
                'min'     => 1,
                'max'     => 10,
                'default' => 5,
                */

                'type'    => 'select',
                'options' => [
                    '' => __('-- Seleziona una voce'),
                    1  => __('★ Facile'),
                    4  => __('★★ Medio'),
                    8  => __('★★★ Difficile'),
                ],

                'label'   => __('Difficoltà'),
                'help'    => __('Necessaria per la creazione dei livelli su quiz funjob'),
            ]);
        ?>
    </fieldset>

    <fieldset>
        <legend>
            <?= __('Fonte') ?>
            <small class="font-size-xs">
                <?php echo __('Necessaria per verificare la tua domanda, e fornire all\'utente un approfondimento') ?>
            </small>
        </legend>


        <?php
            // Non dovrebbe essere necessaria ma non si sà mai :D
            echo $this->Form->control('source_type', [
                'label' => false,
                'options' => [
                    ['value' => 'none', 'data-selector' => '.js-source--none', 'text' => __('Nessuna')],
                    ['value' => 'url', 'data-selector' => '.js-source--url', 'text' => __('Pagina Wikipedia')],
                    ['value' => 'book', 'data-selector' => '.js-source--book', 'text' => __('Scansione fotografica libro')]
                ],
                'help'  => __('Verrà mostrata a fine quiz per approfondire')
            ]);
            //echo $this->Form->error('source_type');
        ?>

        <fieldset class="source-config js-source--url" <?= $this->request->getData('source_type') == 'url' ? '' : 'disabled="disabled"' ?>>
            <?php
                echo $this->Form->input('source_url', [
                    'type'        => 'url',
                    'label'       => __('Link'),
                    'help'        => __('Solo link provenienti da pagine Wikipedia'),
                    //'prepend'     => 'http://wikipedia.org/wiki/',
                    //'placeholder' => 'pagina_wikipedia',
                    'placeholder' => 'http://it.wikipedia.org/wiki/',
                    'required'    => false,
                ]);
            ?>
        </fieldset>

        <fieldset class="source-config js-source--book" <?= $this->request->getData('source_type') == 'book' ? '' : 'disabled="disabled"' ?>>
            <?php
                echo $this->Form->input('source_book_title', [
                    'label'       => __('Titolo'),
                    'placeholder' => 'Titolo libro',
                    'required'    => false
                ]);
                echo $this->Form->input('source_book_page', [
                    'label'       => __('Pagina'),
                    'placeholder' => 'Pagina 4',
                    'required'    => false
                ]);
                echo $this->Form->input('source_book_photo', [
                    'type'        => 'file',
                    'label'       => __('Scansione pagina'),
                    'help'        => __('Ci servirà nel processo di validazione del quiz per verificare le risposte'),
                ]);
            ?>
        </fieldset>

        <script>
            $("#source-type").on("change", function(evt) {
                evt.preventDefault();
                var $fieldset = jQuery( $(this).find(":selected").data("selector") );
                if ($fieldset.length) {
                    $(".source-config").attr("disabled", "disabled");
                    $fieldset.removeAttr("disabled").fadeIn();
                }
            });
        </script>

    </fieldset>

    <?php $disabled = $answerFieldsets['default'] == false ?>
    <fieldset class="app-quiz-answer__default" <?= $disabled ? 'disabled="disabled"' : '' ?>>
        <legend><?= __('Risposte') ?></legend>
        <?php
            $ok = '<i style="color:green" class="glyphicon glyphicon-ok"></i>';
            $ko = '<i style="color:red" class="glyphicon glyphicon-remove"></i>';

            for ($i=0; $i < 4; $i++) {
                echo $this->Form->input('quiz_answers.' .$i. '.answer', [
                    'label'       => false, //  $i == 0 ? __('Risposta corretta') : __('Risposta errata'),
                    'prepend'     => $i == 0 ? $ok : $ko,
                    //'placeholder' => $i == 0 ? 'La risposta corretta' : 'La risposta errata',
                    'help'        => $i == 0 ? '<span class="text-bold text-success">' .__('Questa sarà la risposta corretta'). '</span>' : '',
                    'escape'      => false
                ]);
                echo $this->Form->hidden('quiz_answers.' .$i. '.is_correct', [
                    'value'  => $i == 0 ? '1' : '0'
                ]);
            }

            echo $this->Form->error('quiz_answers');
        ?>
    </fieldset>

    <?php $disabled = $answerFieldsets['true_or_false'] == false ?>
    <fieldset class="app-quiz-answer__trueorfalse" <?= $disabled ? 'disabled="disabled"' : '' ?>>
        <legend><?= __('Risposte [vero/falso]') ?></legend>
        <?php
            echo $this->Form->input('quiz_answers.0.is_correct', [
                'type'    => 'select',
                'label'   => false,
                'default' => 'true',
                'options' => [
                    '1'  => __('Vero'),
                    '0'  => __('Falso')
                ]
            ]);

            echo $this->Form->hidden('quiz_answers.0.answer', ['value' => null]);
        ?>
    </fieldset>

    <?= $this->Form->button(__('Salva')); ?>
    <?= $this->Form->end() ?>
<?php $this->end() ?>



<div class="well well-sm well--padding-sm">
    <h1 class="text-truncate text-center font-size-lg"><?= $quiz->title ?></h1>
    <h6 class="text-muted text-truncate font-size-md"><?= __('Domande in base alla difficoltà') ?></h6>

    <?php echo $this->fetch('toolbar') ?>
</div>

<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">

        <li role="presentation" class="">
            <a href="<?= $this->Url->build(['controller' => 'Quizzes', 'action' => 'edit', $this->request->pass[0]]) ?>" aria-controls="new">
                <span class="fa-stack">
                    <i class="fa fa-stack-1x fa-gamepad text-color-primary"></i>
                </span>

                <span class="hidden-xs"><?php echo __('Impostazioni gioco') ?></span>
                <span class="visible-xs-inline"><?php echo __('Gioco') ?></span>
            </a>
        </li>

        <li role="presentation" class="active">
            <a href="#new" aria-controls="new" role="tab" data-toggle="tab">
                <span class="fa-stack">
                    <i class="fa fa-plus fa-stack-1x text-color-primary"></i>
                </span>

                <span class="hidden-xs"><?php echo __('Nuova domanda') ?></span>
                <span class="visible-xs-inline"><?php echo __('Nuova') ?></span>
            </a>
        </li>
        <li role="presentation">
            <a class="js-quiz-questions-tab" href="#questions" aria-controls="questions" role="tab" data-toggle="tab">
                <span class="fa-stack">
                    <i class="fa fa-question fa-stack-1x text-color-primary"></i>
                </span>

                <span class="hidden-xs"><?php echo __('Domande già realizzate') ?></span>
                <span class="visible-xs-inline"><?php echo __('Domande') ?></span>
            </a>
        </li>
        <li role="presentation">
            <a class="js-quiz-questionsDisabled-tab" href="#questionsDisabled" aria-controls="questions" role="tab" data-toggle="tab">

                <span class="fa-stack">
                  <i class="fa fa-question fa-stack-1x text-color-primary"></i>
                  <i class="fa fa-ban fa-stack-2x text-danger"></i>
                </span>

                <span class="hidden-xs"><?php echo __('Domande disabilitate') ?></span>
                <span class="visible-xs-inline"><?php echo __('Disabilitate') ?></span>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            <?php echo $this->fetch('tab:add') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="questions">
            <noscript>
                <div class="alert alert-danger">
                    <p class="font-size-md"><?= __('Richiede Javascript') ?></p>
                </div>
            </noscript>
        </div>
        <div role="tabpanel" class="tab-pane" id="questionsDisabled">
            <noscript>
                <div class="alert alert-danger">
                    <p class="font-size-md"><?= __('Richiede Javascript') ?></p>
                </div>
            </noscript>
        </div>
    </div>
</div>



<style type="text/css">
    fieldset[disabled="disabled"] {
        display:none;
    }
</style>

<script type="text/javascript">
    $(function() {
        $("input[name='type']").on("change", function(evt) {
            var value = this.value;

            var answerDefault = $(".app-quiz-answer__default");
            var answerBool    = $(".app-quiz-answer__trueorfalse");

            if (value == "default") {
                answerBool.attr("disabled", "disabled").fadeOut();
                answerDefault.removeAttr("disabled").fadeIn();
            } else {
                answerDefault.attr("disabled", "disabled").fadeOut();
                answerBool.removeAttr("disabled").fadeIn();
            }
        });
    })
</script>
<script>
    $(function() {
        $(".js-quiz-questions-tab").on("click", function(evt) {
            var $req = $.ajax({
                url        : "<?= $this->Url->build(['action' => 'index', $quiz->id]) ?>",
                method     : "GET",
                beforeSend : function() {
                    $.blockUI({ timeout: 40 });
                }
            });

            $req.done(function(response) {
                $("#questions").html(response);
            });
            $req.fail(function(jxhr, textError, exception) {
            });
            $req.always(function() {
                $.unblockUI();
            });
        });

        $(".js-quiz-questionsDisabled-tab").on("click", function(evt) {
            var $req = $.ajax({
                url        : "<?= $this->Url->build(['action' => 'index', $quiz->id, 'disabled']) ?>",
                method     : "GET",
                beforeSend : function() {
                    $.blockUI({ timeout: 40 });
                }
            });

            $req.done(function(response) {
                $("#questionsDisabled").html(response);
            });
            $req.fail(function(jxhr, textError, exception) {
            });
            $req.always(function() {
                $.unblockUI();
            });
        });


        // Aggiornamento visibilità
        $("body").on("click", ".js-question-status-checkbox", function(evt) {
            var $form = $(this).closest("form");
            $form.trigger("submit");
        });

        $("body").on("submit", ".quiz-question-visibility", function(evt) {
            evt.preventDefault();
            var $this     = $(this);
            var $checkbox = $(":checkbox", $this);
            var isChecked = $checkbox.prop(":checked");

            var $req = $.ajax({
                method     : "PUT",
                url        : $this.attr("action"),
                data       : $this.serialize(),
                beforeSend : function() {
                    $.blockUI({});
                }
            });

            $req.done(function(response) {
                if (response.status == "success") {
                    alertify.success(response.message);
                    $this.closest("tr").remove();
                } else {
                    if (isChecked) {
                        $checkbox.removeAttr("checked");
                    } else {
                        $checkbox.prop("checked", "checked");
                    }

                    alertify.error(response.message);
                }
            });

            $req.always(function() {
                $.unblockUI();
            });
        });
    });
</script>
