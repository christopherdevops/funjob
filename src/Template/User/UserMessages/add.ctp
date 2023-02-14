<?php
    $this->assign('title', __('Componi nuovo messaggio'));
    $this->Html->css([
        '//afeld.github.io/emoji-css/emoji.css'
    ], ['block' => 'css_foot']);

    $this->Breadcrumbs
        ->add(__('Messaggi privati'), ['action' => 'index'])
        ->add(__('Componi'), ['action' => 'add']);
?>

<div class="well">
    <div class="page-header no-margin">
        <h1 class="font-size-lg"><?php echo __('Componi messaggio') ?></h1>

        <a class="btn btn-default" href="<?= $this->Url->build(['_name' => 'message:archive']) ?>">
            <i class="fa fa-arrow-left"></i>
            <?= __('Torna a messaggi') ?>
        </a>
    </div>

    <?php echo $this->Form->setValueSources(['query', 'context'])->create($userMessage) ?>
    <fieldset>
        <?php
            echo $this->Form->control('replies.0.recipients.1.username', [
                'type'        => 'text',
                'placeholder' => 'Ricerca utente ...',
                'help'        => 'Digita l\'username e attendi il risultato',
                'class'       => 'typeahead typeahead--users',
            ]);

            echo $this->Form->control('context', [
                'label'   => __('Tipologia messaggio'),
                'type'    => 'select',
                'options' => [
                    'job_offer'   => __('Offerta di lavoro'),
                    'job_request' => __('Richiesta di lavoro'),
                    'pm'          => __('Messaggio personale')
                ],
                'default' => 'pm'
            ]);

            // echo $this->Form->control('_messageTemplate', [
            //     'type'        => 'select',
            //     'options'     => $templates,
            //     'label'       => 'Messaggi pre-definiti',
            //     'placeholder' => ''
            // ]);

            echo $this->Form->control('subject', [
                'label' => __('Oggetto')
            ]);

            echo $this->Form->control('replies.0.body', [
                'label' => __('Messaggio'),
                'type'  => 'textarea',
                'class' => 'js-message-body'
            ]);
        ?>
    </fieldset>
    <fieldset class="well well-sm">
        <?php foreach ($emoticons as $emoticonIcon => $emoticonTextual): ?>
        <button title="<?= __('{0}', $emoticonTextual) ?>" type="button" data-sendtext="<?= $emoticonTextual ?>" class="js-emoticon-btn btn btn-default btn-xs">
            <?= $emoticonIcon ?>
        </button>

        <?php endforeach ?>
        <script>
            $(".js-emoticon-btn").on("click", function(evt) {
                evt.preventDefault();

                var textarea = document.querySelector(".js-message-body");
                textarea.value += this.dataset.sendtext + " ";
                textarea.focus();
            });
        </script>
    </fieldset>

    <?php
        echo $this->Form->submit(__('Invia'), ['class' => 'btn btn-block btn-lg btn-primary']);
        echo $this->Form->end()
    ?>
</div>



<script defer="defer" src="/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
<script defer="defer" src="/bower_components/handlebars/handlebars.min.js"></script>
<link rel="stylesheet" href="css/typeahead.css">
<script>
$(function() {

    var usersSource = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,

        remote: {
            url      : '/users/search.json?term=%QUERY',
            wildcard : '%QUERY',

            filter   : function (response) {
                return $.map(response.results, function(item) {
                    return item;
                });
            },
        }
    });

    usersSource.initialize();

    var $typeahead = $('.typeahead--users').typeahead({
        minLength : 3,
        limit     : 15,

        hint      : true,
        highlight : true,
    }, {
        delay     : 1000,
        name      : 'user-search',
        engine    : Handlebars,
        source    : usersSource.ttAdapter(),

        displayKey   : "username",
        displayValue : "id",

        templates : {
            pedding    : '<span>Attendere prego ...</span>',
            empty      : "<span class='font-size-sm text-muted'>Nessun risultato</span>",
            suggestion : Handlebars.compile('<div><strong>@{{username}}</strong></div>'),

            header     : '<p class="tt-tag-heading tt-tag-heading2 font-size-sm text-muted"><?= __('Risultati') ?></p>',
            footer     : ''
        },
    });
});
</script>
