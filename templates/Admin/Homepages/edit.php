<?php
    $this->assign('title', __d('backend', 'Configurazione home'));
    $this->Breadcrumbs
        ->add(__d('backend', 'Home'));
?>


<?php
    echo $this->Form->create($HomepageSettings, ['url' => ['action' => 'edit']]);
    echo $this->Form->control('id');
?>

<div class="well">
    <fieldset>
        <legend><?= __d('backend', 'Video in primo piano (1/2)') ?></legend>
        <?php
            echo $this->Form->control('foreground_video_title', [
                'label' => __d('backend', 'Titolo')
            ]);
            echo $this->Form->control('foreground_video_embed', [
                'label' => __d('backend', 'Codice embed video (HTML)'),
                'help'  => __d('backend', 'Video preso da Youtube.com')
            ]);
            echo $this->Form->control('foreground_video_href', [
                'label' => __d('backend', 'Link')
            ]);
    ?>
    </fieldset>
</div>

<div class="well">
    <fieldset>
        <legend><?= __d('backend', 'Video in primo piano (2/2)') ?></legend>
        <?php
            echo $this->Form->control('foreground_video2_title', [
                'label' => __d('backend', 'Titolo')
            ]);
            echo $this->Form->control('foreground_video2_embed', [
                'label' => __d('backend', 'Codice embed video (HTML)'),
                'help'  => __d('backend', 'Video preso da Youtube.com')
            ]);
            echo $this->Form->control('foreground_video2_href', [
                'label' => __d('backend', 'Link')
            ]);
        ?>
    </fieldset>
</div>


<?php
    echo $this->Form->button(__('Salva'), ['class' => 'btn btn-sm btn-default']);
    echo $this->Form->end();
?>
