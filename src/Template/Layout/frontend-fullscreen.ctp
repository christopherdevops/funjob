<?php
    $this->extend('frontend');

    // Pagina in fullscreen
    $this->assign('content--class', 'content--fullscreen col-xs-12 col-sm-12 col-md-12 col-lg-12');

    echo $this->fetch('content');
