<?php
    $this->assign('title', $userMessage->subject ? $userMessage->subject :  __('Senza oggetto'));
    $this->Html->css([
        '//afeld.github.io/emoji-css/emoji.css'
    ], ['block' => 'css_foot']);

    $this->assign('header', ' ');
    $this->Breadcrumbs
        ->add(__('Messaggi privati'), ['action' => 'index'])
        ->add(__('Dettaglio'), $this->request->getAttribute('here'));
?>

<?php $this->append('css_head--inline') ?>
    *[class*="app-inbox-reply-"] .panel-body {
        padding:5px;
    }

    .app-inbox-avatar {
        text-align:center;
        display:inline-block;
        overflow:auto;
        max-width:100px;
    }

    .app-inbox-reply--me .app-inbox-avatar {
        display:inline-block;
        float:left;
    }
    .app-inbox-reply--user .app-inbox-avatar {
        display:inline-block;
        float:right;
    }

    /**
     * 1. posiziona freccietta cloud
     */
    .well--cloud {
        position:relative; /** @1 **/
        min-width:120px;
    }

    /**
     * 1. Blocchi stessa dimensione testo
     */
    .well-default {
        display:inline-block !important; /** 1 **/
        float:right !important;
    }
    .well-info {
        display:inline-block !important; /** 1 **/
        float:left !important;
    }

    .well-default.well--cloud:after {
        display: block;
        content: '';

        position: absolute;
        right: 0;
        top: 10px;

        width: 0;
        height: 0;
        border-style: solid;
        border-width: 9px 0 9px 9px;
        border-color: transparent transparent transparent #f5f5f5;
        margin-right: -8px;
    }

    .well-info.well--cloud:before {
        content: '';
        display: block;
        position: absolute;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 9px 9px 9px 0;
        border-color: transparent #00adee transparent transparent;
        left: 0;
        top: 10px;
        margin-left: -8px;
    }
<?php $this->end() ?>


<div class="well well-sm">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="no-margin font-size-md text-muted"><?php echo $userMessage->subject ?></h1>
            <h4 class="no-margin font-size-xs text-muted" style="margin-top:15px !important;">
                <?php echo __('Conversazione iniziata il {0}', $userMessage->created->format('d/m/Y')) ?>
            </h4>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <a href="<?= $this->Url->build(['_name' => 'message:archive']) ?>" class="btn btn-sm btn-info btn-block">
                <?= __('Archivio messaggi') ?>
            </a>

            <a class="btn btn-sm btn-block btn-default" href="<?= $this->request->getAttribute('here') ?>#user-message-reply-form">
                <i class="fa fa-comment"></i>

                <span class="text-truncate">
                    <?php echo __('Rispondi') ?>
                </span>
            </a>

        </div>
    </div>
</div>

<?php foreach ($userMessage->replies as $message) : ?>
<?php
    $is_mine = $message->sender_id == $this->request->getSession()->read('Auth.User.id');
?>

    <div class="app-inbox-reply app-inbox-reply--<?= $is_mine ? 'me' : 'user' ?> app-inbox-reply-<?= $userMessage->uuid ?>--<?= $message->id ?>">
        <div class="row">
            <?php
                /**
                 * Formato messaggio:
                 *
                 * messaggio inviato  (sinistra):  user + messaggio
                 * messaggio ricevuto (destra) :  messaggio + user
                 */
            ?>

            <?php if ($is_mine) : ?>
            <div class="hidden-xs col-sm-2 col-md-1 col-lg-1">
                <div class="dropdown">
                    <button style="border:0;background-color:transparent;" class="dropdown-toggle" type="button" data-toggle="dropdown">
                        <?= $this->User->avatar($message->reply_sender->avatarSrcMobile, ['class' => 'img-circle']) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= $this->Url->build($message->reply_sender->url) ?>">
                                @<?= $message->reply_sender->username ?>
                                <span class="text-muted">
                                    <?= __x('Il tuo nick', '(te stesso)') ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif ?>

            <div class="col-xs-12 col-sm-9 col-md-11 col-lg-11">

                <div class="well well-sm <?= $is_mine ? 'well-info' : 'well-default' ?> well--cloud">

                    <div class="dropdown visible-xs-inline-block" style="vertical-align:top">
                        <button style="border:0;background-color:transparent;" class="dropdown-toggle" type="button" data-toggle="dropdown">
                            <?= $this->User->avatar($message->reply_sender->avatarSrcMobile, ['class' => 'img-circle']) ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= $this->Url->build($message->reply_sender->url) ?>">
                                    @<?= $message->reply_sender->username ?>
                                </a>
                            </li>

                            <?php if (!$is_mine) : ?>
                            <li>
                                <a href="<?= $this->Url->build(['controller' => 'UserIgnoreLists', 'action' => 'add']) ?>">
                                    <?= __('Ignora') ?>
                                </a>
                            </li>
                            <?php endif ?>
                        </ul>
                    </div>

                    <div class="no-margin visible-sm visible-md visible-lg visible-xs-inline-block">
                        <div class="font-size-md2">
                            <?= $this->Text->autoParagraph($message->bodyFormatted) ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <footer class="font-size-sm">
                        <i class="fa fa-calendar"></i>
                        <datetime timestamp="<?= $message->created ?>">
                            <?= $message->created ?>
                        </datetime>
                    </footer>
                </div>
            </div>

            <?php if (!$is_mine) : ?>
            <div class="hidden-xs col-sm-2 col-md-1 col-lg-1">
                <div class="dropdown">
                    <button style="border:0;background-color:transparent;" class="dropdown-toggle" type="button" data-toggle="dropdown">
                        <?= $this->User->avatar($message->reply_sender->avatarSrcMobile, ['class' => 'img-circle']) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= $this->Url->build($message->reply_sender->url) ?>">
                                @<?= $message->reply_sender->username ?>
                            </a>
                        </li>
                        <li>
                            <?=
                                $this->Form->postLink(
                                        __('Ignora'),
                                        ['controller' => 'UserIgnoreLists', 'action' => 'add'],
                                        [
                                            'data' => [
                                                'recipient_user_id' => $this->request->getSession()->read('Auth.User.id'),
                                                'ignore_user_id'    => $message->reply_sender->id
                                            ],
                                            'confirm' => __('Sei sicuro di voler bloccare questo utente?')
                                        ],
                                        ['class' => 'text-danger']
                                )
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif ?>

        </div>

        <!--
        <div class="panel-footer">
            <?php
                echo $this->Html->link(
                    __('Link diretto'),
                    ['_name' => 'message:view', $userMessage->uuid, '#' => 'app-inbox-reply-'. $userMessage->uuid . '--' .$message->id],
                    []
                );
            ?>
        </div>
        -->

    </div>
    <div class="clearfix"></div>
<?php endforeach ?>

<div class="well well-sm">
    <?php echo $this->Form->create($userMessageReply, ['id' => 'user-message-reply-form', 'url' => ['action' => 'reply']]) ?>
    <fieldset>
        <legend><?php echo __('Rispondi al messaggio') ?></legend>
        <?php
            echo $this->Form->hidden('conversation_id', ['value' => $message->conversation_id]);
            echo $this->Form->control('body', [
                'label'       => false,
                'placeholder' => __('Digita qui la tua risposta  ...'),
                'type'        => 'textarea',
                'class'       => 'js-message-body'
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

    <?php echo $this->Form->submit(__('Invia'), ['class' => 'btn btn-primary btn-block']) ?>
    <?php echo $this->Form->end() ?>
</div>
