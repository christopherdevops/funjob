<div style="position:fixed;width:inherit;z-index:999" class="app-menu--fixed">
    <div>
        <span class="visible-lg"><div style="width:15px;height:15px;background-color:green"></div> Large</span>
        <span class="visible-md"><div style="width:15px;height:15px;background-color:lime"></div> Medium</span>
        <span class="visible-sm"><div style="width:15px;height:15px;background-color:orange"></div> Small</span>
        <span class="visible-xs"><div style="width:15px;height:15px;background-color:red"></div> Extra small</span>
    </div>

    <!-- MENU -->
    <!--
    <div class="app_menu_icon" onclick="javascript:this.classList.toggle('change');document.querySelector('.app-menu').classList.toggle('hidden');">
        <span class="sr-only"><?php echo __('Chiudi') ?></span>
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    -->

    <nav>
        <ul class="app-menu hidden" style="padding:0;margin:0;list-style-type:none;overflow:scroll-y;">
            <li><img class="img-responsive" src="img/ui-menu-icons/header_fun.png" alt="Fun"></li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'quizzes', 'action' => 'index']) ?>">
                    <img class="img-responsive" src="img/ui-menu-icons/quizzes.png" alt="">
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'quizzes', 'action' => 'index']) ?>">
                    <img class="img-responsive" src="img/ui-menu-icons/quiz-completed.png" alt="">
                </a>
            </li>
            <li>
                <a href="<?= $this->Url->build(['controller' => 'quizzes', 'action' => 'index']) ?>">
                    <img class="img-responsive" src="img/ui-menu-icons/credits.png" alt="">
                </a>
            </li>
            <li>
                <a href="#">
                    <img class="img-responsive" src="img/ui-menu-icons/market.png" alt="">
                </a>
            </li>

            <li><img class="img-responsive" src="img/ui-menu-icons/header_job.png" alt="Job"></li>
            <li>
                <a href="#">
                    <img src="" alt="">
                </a>
            </li>
        </ul>
    </nav>
</div>
