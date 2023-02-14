<?php if ($messages_unread > 0) : ?>
<a
    class="app-menu-primary-btn--mail animated animated--infinite <?= $messages_unread > 0 ? 'pulse' : '' ?>"
    href="<?= $this->Url->build(['_name' => 'message:archive']) ?>"
>
   <i class="fa fa-envelope-o visible-md visible-lg" style="font-size:24px"></i>
   <i class="fa fa-envelope-o visible-xs visible-sm" style="font-size:22px"></i>
   <span class="mail-badge"><?= $messages_unread ?></span>
</a>
<?php else: ?>
<a class="app-menu-primary-btn--mail" href="<?= $this->Url->build(['_name' => 'message:archive']) ?>">
   <i class="fa fa-envelope-o visible-md visible-lg" style="font-size:24px"></i>
   <i class="fa fa-envelope-o visible-xs visible-sm" style="font-size:22px"></i>
</a>
<?php endif ?>
