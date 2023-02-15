<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:cat', 'job');

    $this->assign('tab:name', 'user_skills');
    $this->assign('tab:icon', 'fa fa-tags');
    $this->assign('tab:title', __('Competenze tecniche'));
?>

<?php $this->append('css_head--inline') ?>
  /* resume stuff (http://bootsnipp.com/snippets/MRgWB) */

  .bs-callout {
      -moz-border-bottom-colors: none;
      -moz-border-left-colors: none;
      -moz-border-right-colors: none;
      -moz-border-top-colors: none;
      border-color: #eee;
      border-image: none;
      border-radius: 3px;
      border-style: solid;
      border-width: 1px 1px 1px 5px;
      margin-bottom: 5px;
      padding: 20px;
  }
  .bs-callout:last-child {
      margin-bottom: 0px;
  }
  .bs-callout h4 {
      margin-bottom: 10px;
      margin-top: 0;
  }

  .bs-callout-danger {
      border-left-color: #d9534f;
  }

  .bs-callout-danger h4{
      color: #d9534f;
  }

  .resume .list-group-item:first-child, .resume .list-group-item:last-child{
    border-radius:0;
  }

  /*makes an anchor inactive(not clickable)*/
  .inactive-link {
     pointer-events: none;
     cursor: default;
  }

  .resume-heading .social-btns{
    margin-top:15px;
  }
  .resume-heading .social-btns i.fa{
    margin-left:-5px;
  }



  @media (max-width: 992px) {
    .resume-heading .social-btn-holder{
      padding:5px;
    }
  }


  /* skill meter in resume. copy pasted from http://bootsnipp.com/snippets/featured/progress-bar-meter */

  .progress-bar {
      text-align: left;
      white-space: nowrap;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: pointer;
  }

  .progress-bar > .progress-type {
      padding-left: 10px;
  }

  .progress-meter {
      min-height: 15px;
      border-bottom: 2px solid rgb(160, 160, 160);
    margin-bottom: 15px;
  }

  .progress-meter > .meter {
      position: relative;
      float: left;
      min-height: 15px;
      border-width: 0px;
      border-style: solid;
      border-color: rgb(160, 160, 160);
  }

  .progress-meter > .meter-left {
      border-left-width: 2px;
  }

  .progress-meter > .meter-right {
      float: right;
      border-right-width: 2px;
  }

  .progress-meter > .meter-right:last-child {
      border-left-width: 2px;
  }

  .progress-meter > .meter > .meter-text {
      position: absolute;
      display: inline-block;
      bottom: -20px;
      width: 100%;
      font-weight: 700;
      font-size: 0.85em;
      color: rgb(160, 160, 160);
      text-align: left;
  }

  .progress-meter > .meter.meter-right > .meter-text {
      text-align: right;
  }

  /**** resume ****/
<?php $this->end() ?>


<div class="row">
    <div class="col-md-12">
        <ul class="list-group">
           <a class="list-group-item inactive-link" href="#">
             <?php foreach($User->user_skills as $skill) : ?>
               <div class="progress">
                  <div data-placement="top" style="width: <?= (int) $skill->perc ?>%;"
                     aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?= $skill->perc ?>" role="progressbar" class="progress-bar progress-bar-success">
                     <span class="sr-only"><?= $skill->perc ?>%</span>
                     <span class="progress-type"><?= $skill->name ?></span>
                  </div>
               </div>
             <?php endforeach ?>
              <div class="progress-meter">
                 <div style="width: 25%;" class="meter meter-left">
                    <span class="meter-text"><?php echo __('Scarso') ?></span>
                 </div>
                 <div style="width: 25%;" class="meter meter-left">
                    <span class="meter-text"><?php echo __('Mediocre') ?></span>
                 </div>
                 <div style="width: 30%;" class="meter meter-right">
                    <span class="meter-text"><?php echo __('Professionista') ?></span>
                 </div>
                 <div style="width: 20%;" class="meter meter-right">
                    <span class="meter-text"><?php echo __('Esperto') ?></span>
                 </div>
              </div>
           </a>
        </ul>
    </div>
</div>
