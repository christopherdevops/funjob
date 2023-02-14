<?php $this->start('userblocks:toggle') ?>
    <div class="btn-group" role="group" data-toggle="buttons" aria-label="<?= __('Mostra/Nascondi') ?>">
        <label class="btn btn-md btn-default">
            <input type="radio" name="user-profile-panel-visibility" id="user-profile-panel-visibility--expand">
            <i class="text-color-primary fa fa-folder-open-o"></i>
            <span class="text-color-gray font-size-sm">
                <?= __('Apri tutti') ?>
            </span>
        </label>

        <label class="btn btn-md btn-default active">
            <input type="radio" name="user-profile-panel-visibility" id="user-profile-panel-visibility--collapse">
            <i class="text-color-primary fa fa-folder-o"></i>
            <span class="text-color-gray font-size-sm">
                <?= __('Chiudi') ?>
                <span class="hidden-xs"> <?= __('tutti') ?></span>
            </span>
        </label>
    </div>
    <?php $this->append('js_foot') ?>
    <script>
    $(function() {
        (function() {
            //var $collapse = $('#user-profile-panels .collapse');
            $('input[name="user-profile-panel-visibility"]').on("change", function (evt) {
                var $this           = $(this);
                var $wrapper        = $this.closest("[data-collapse-target]");
                var wrapperSelector = $wrapper.data("collapse-target");
                var _method         = "show";


                if (this.id == "user-profile-panel-visibility--collapse") {
                    _method = "hide";
                }

                $(".collapse", wrapperSelector).collapse(_method);
            });
        })();
    });
    </script>
    <?php $this->end() ?>
<?php $this->end() ?>


<div id="user-profile-panels" class="panel-group">

    <div class="page-header" style="border-color:#00adee !important;margin-top:0 !important;">
        <span class="font-family--alba text-color-primary" style="margin-left:15px;font-size:1.8em;">Fun</span>
        <div class="pull-right" data-collapse-target=".collapse-fun">
            <?= $this->fetch('userblocks:toggle') ?>
        </div>
    </div>
    <div class="collapse-fun">
        <?php
            $myself   = $this->UserProfile->isMyProfile();
            $hasBlock = false;

            if ($myself || !empty($User->profile_block->is_bigbrain)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/bigbrain');
            }

            if ($myself || !empty($User->profile_block->biography)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/bio');
            }

            if ($myself || !empty($User->profile_block->hobbies)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/hobbies');
            }

            if ($myself || !empty($User->profile_block->music)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/film');
            }

            if ($myself || !empty($User->profile_block->films)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/music');
            }

            if ($myself || !empty($User->profile_block->inspirations)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/inspirations');
            }

            if ($myself || !empty($User->profile_block->quotes)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/quotes');
            }
        ?>

        <?php if (!$hasBlock) : ?>
        <p class="text-muted text-center">
            <i class="fa fa-frown-o fa-2x"></i>
            <?= __('Nessuna informazione pervenuta') ?>
        </p>
        <?php endif ?>

    </div>


    <div class="page-header" style="margin-top:30px !important;border-color:gray !important">
        <span class="font-family--alba" style="color:gray;margin-left:15px;font-size:1.8em">Job</span>
        <div class="pull-right" data-collapse-target=".collapse-job">
            <?= $this->fetch('userblocks:toggle') ?>
        </div>
    </div>

    <div class="collapse-job">
        <?php
            $hasBlock = false;

            if ($myself || !empty($User->profile_block->studies)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/studies');
            }

            if ($myself || !empty($User->profile_block->job_skills)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/job_skills');
            }

            if ($myself || !empty($User->user_skills)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/skills');
            }

            if ($myself || !empty($User->profile_block->pubblications)) {
                $hasBlock = true;
                echo $this->element('UserProfile/tabs/pubblications');
            }
        ?>

        <?php if (!$hasBlock) : ?>
        <p class="text-muted text-center">
            <i class="fa fa-frown-o fa-2x"></i>
            <?= __('Nessuna informazione pervenuta') ?>
        </p>
        <?php endif ?>

    </div>
</div>


<?php $this->append('js_foot') ?>
<script>
    $(function() {
        var iconMap = ["fa-folder-o", "fa-folder-open-o"];

        $("#user-profile-panels")
            .on("hide.bs.collapse", function(e) {
                var $icon = $(e.target).closest(".panel").find(".pull-right .fa")
                $icon.removeClass(iconMap[0]).addClass(iconMap[1]);
            })
            .on("show.bs.collapse", function(e) {
                var $icon = $(e.target).closest(".panel").find(".pull-right .fa")
                $icon.removeClass(iconMap[1]).addClass(iconMap[0]);
            });
    });
</script>
<?php $this->end() ?>
