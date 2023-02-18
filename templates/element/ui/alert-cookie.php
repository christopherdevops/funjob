<?php if (!$this->request->getCookie($cookie)) : ?>
<div class="alert alert-info alert-remember">
    <button type="button" class="close" data-cookie="<?= $cookie ?>" data-dismiss="alert" aria-hidden="true">
        <i class="fa fa-close"></i>
    </button>
    <strong class="font-size-md2"><?= $title ?></strong>
    <p class="font-size-md2">
        <?php echo $message ?>
    </p>
</div>
<?php endif ?>
