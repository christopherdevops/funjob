<?php
    $this->assign('title', __('Messaggi privati'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Messaggi privati'), ['action' => 'index'])
        ->add(__('Archivio'), ['action' => 'index']);
?>

<?php $this->append('css_head--inline') ?>
    #app-user-inbox-header {
        padding:7px;
    }

    .no-padding-left  {padding-left:0}
    .no-padding-right {padding-right:0}
<?php $this->end() ?>

<div id="app-user-inbox-header" class="well">

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a href="#" class="btn btn-sm btn-info btn-block">
                <?= __('Archivio messaggi') ?>
            </a>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="btn btn-sm btn-block btn-default" href="<?= $this->Url->build(['_name' => 'message:compose']) ?>">
                <i class="fa fa-plus"></i>

                <span class="text-truncate">
                    <?php echo __('Nuovo messaggio') ?>
                </span>
            </a>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <?=
                $this->Form->postLink(
                    '<i class="fa fa-envelope-open-o"></i>  ' . __('Segna tutti come letti'),
                    ['action' => 'mark_as_read'],
                    ['class' => 'btn btn-block btn-default', 'escape' => false]
                )
            ?>
        </div>
    </div>
</div>

<?php $this->start('list-group') ?>
    <ul class="list-group">

        <li class="list-group-item disabled flex-align-center" style="overflow:hidden">
            <span class="text-center text-uppercase"><?= __('Ultimi messaggi ricevuti') ?></span>
        </li>

        <?php foreach ($userMessages as $userMessage) : ?>
        <li class="list-group-item <?= $userMessage->messages_unread > 0 ? 'list-group-item-info' : '' ?>" style="overflow:hidden">
            <div class="row gutter-10">
                <div class="hidden-xs col-sm-1 col-md-1 col-lg-1">
                    <a href="<?= $this->Url->build(['_name' => 'message:view', $userMessage->uuid]) ?>">

                        <div class="">
                            <!-- <i class="fa fa-stack-2x fa-arrow-right"></i> -->
                            <?php if ($userMessage->messages_unread > 0) : ?>
                                <i class="fa fa-envelope-o"></i>
                            <?php else: ?>
                                <i class="fa fa-envelope-open-o"></i>
                            <?php endif ?>

                        </div>

                    </a>
                </div>

                <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">

                    <a href="<?= $this->Url->build(['_name' => 'message:view', $userMessage->uuid, '#' => 'app-inbox-reply-'. $userMessage->uuid . '--' .$userMessage->replies_last->id]) ?>">

                        <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                                <?php if ($userMessage->messages_unread > 0) : ?>
                                <i class="visible-xs-inline fa fa-envelope-o"></i>
                                <?php else: ?>
                                <i class="visible-xs-inline fa fa-envelope-open-o"></i>
                                <?php endif ?>

                                <?php
                                    echo $this->User->avatar(
                                        $userMessage->replies_last->reply_sender->avatarSrcMobile,
                                        ['style' => 'margin-right:10px']
                                    );
                                ?>

                                <span class="text-uppercase" style="overflow:hidden">
                                    <span class="no-margin font-size-md2 text-truncate">
                                        <?php if ($userMessage->context == 'job_offer') : ?>
                                            <span class="label label-danger">#job</span>
                                        <?php elseif ($userMessage->context == 'job_request') : ?>
                                            <span class="label label-info">#job</span>
                                        <?php endif ?>
                                        <?php echo $this->Text->truncate($userMessage->subject, 50, ['exact' => true]) ?>
                                    </span>
                                </span>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p class="no-margin">
                                    <span class="visible-xs text-muted font-size-xs">
                                        <?= $userMessage->replies_last->created ?>
                                    </span>
                                    <span class="text-truncate">
                                        <?= $this->Text->truncate($userMessage->replies_last->body, 65, ['exact' => true]) ?>
                                    </span>

                                    <span class="hidden-xs pull-right">
                                        <?= $userMessage->replies_last->created ?>
                                    </span>
                                </p>
                            </div>

                        </div>

                    </a>

                </div>
            </div>
        </li>
        <?php endforeach ?>

        <?php echo $this->element('pagination') ?>

        <?php if ($userMessages->isEmpty()): ?>
        <li class="list-group-item"><span class="text-muted">Nessun messaggio per te</span></li>
        <?php endif ?>

    </ul>
<?php $this->end() ?>

<?php $this->start('responsive-table') ?>
    <?php if ($userMessages->isEmpty()): ?>
    <?php endif ?>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th><?= __('Oggetto') ?></th>
                    <th>
                        <span class="font-size-md hidden-xs">
                            <?= __('Ultimo messaggio') ?>
                        </span>
                        <span class="font-size-md visible-xs">
                            <?= __('Messaggio') ?>
                        </span>
                    </th>
                    <th class="hidden-xs"><?= __('Data') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="visible-xs">
                    <td colspan="4">
                        <img style="height:25px" src="img/menu-touch-slider-tutorial.png" alt="">
                        <span class="text-muted font-size-sm">
                            <?php echo __('Scorri con il dito per visualizzare altre informazioni') ?>
                        </span>
                    </td>
                </tr>

                <?php foreach ($userMessages as $userMessage) : ?>
                <tr class="<?= $userMessage->messages_unread > 0 ? 'info' : '' ?>">
                    <td>
                        <?php if ($userMessage->messages_unread > 0) : ?>
                            <i class="fa fa-envelope-o"></i>
                        <?php else: ?>
                            <i class="fa fa-envelope-open-o"></i>
                        <?php endif ?>
                    </td>
                    <td>
                        <a href="<?= $this->Url->build(['_name' => 'message:view', $userMessage->uuid, '#' => 'app-inbox-reply-'. $userMessage->uuid . '--' .$userMessage->replies_last->id]) ?>">
                            <span class="text-uppercase" style="overflow:hidden">
                                <span class="no-margin font-size-md2 text-truncate">
                                    <?php if ($userMessage->context == 'job_offer') : ?>
                                        <span class="label label-danger">#job</span>
                                    <?php elseif ($userMessage->context == 'job_request') : ?>
                                        <span class="label label-info">#job</span>
                                    <?php endif ?>
                                    <?php echo $this->Text->truncate($userMessage->subject, 50, ['exact' => true]) ?>
                                </span>
                            </span>
                        </a>
                    </td>
                    <td>
                        <a href="<?= $this->Url->build(['_name' => 'message:view', $userMessage->uuid, '#' => 'app-inbox-reply-'. $userMessage->uuid . '--' .$userMessage->replies_last->id]) ?>">
                            <?php
                                echo $this->User->avatar(
                                    $userMessage->replies_last->reply_sender->avatarSrcMobile,
                                    ['style' => 'margin-right:10px']
                                );
                            ?>
                            <span class="text-truncate">
                                @<?= $userMessage->replies_last->reply_sender->username ?>:
                                <?= $this->Text->truncate($userMessage->replies_last->body, 50, ['exact' => true]) ?>
                            </span>
                        </a>
                    </td>
                    <td class="hidden-xs">
                        <?= $userMessage->replies_last->created ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php $this->end() ?>


<?php echo $this->fetch('responsive-table') ?>
<?php echo $this->element('pagination') ?>
