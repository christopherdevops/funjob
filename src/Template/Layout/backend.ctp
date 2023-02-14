<?php
    $this->extend('frontend');
    // Pagina in fullscreen
    $this->assign('content--class', 'content--fullscreen col-xs-12 col-sm-12 col-md-12 col-lg-12');

    try {
        $this->Breadcrumbs->insertAt(0, '<i class="fa fa-legal"></i> ' . __('Amministrazione'), '#');
    } catch(Exception $e) {
        $this->Breadcrumbs->add('<i class="fa fa-legal"></i> ' . __('Amministrazione'), '#');
    }
?>

<?= $this->fetch('content') ?>
