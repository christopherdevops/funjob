<div class="app-menu app-menu--compact">

        <ul class="app-menu-list  list-unstyled text-center">
            <li class="hidden">
                <a href="#">
                    <img class="responsive-img" src="//www.funjob.loc/logo.png" alt="Your Brain, your business">
                </a>
            </li>

            <li class="hidden app-menu-list-item">
                <a href="#" class="visible-lg">
                    <i class="fa fa-desktop" aria-hidden="true"></i>
                    <span>lg</span>
                </a href="#">
                <a href="#" class="visible-md">
                    <i class="fa fa-desktop" aria-hidden="true"></i>
                    <span>md</span>
                </a href="#">
                <a href="#" class="visible-sm">
                    <i class="fa fa-tablet" aria-hidden="true"></i>
                    <span>sm</span>
                </a href="#">
                <a href="#" class="visible-xs">
                    <i class="fa fa-mobile" aria-hidden="true"></i>
                    <span>sx</span>
                </a href="#">
            </li>

            <li style="padding-top:5px" class="app-menu-list-item">
                <a href="#">
                    <img style="margin:0 auto" class="img-responsive img-circle" src="//gravatar.com/avatar/abe2d7626ec0125f99fd5eb3e7a05dff?s=40" alt="">
                    <span><?php echo $this->request->session()->read('Auth.User.firstname') ?></span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <div class="">
                        <img style="width:25px;height:25px" src="img/ui-menu-icons/email.svg" alt="">
                        <!-- <i class="fa fa-envelope"> -->
                            <span class="badge">
                                <?php echo rand(0,100) ?>
                            </span>
                        <!-- </i> -->
                    </div>
                </a>
            </li>
        </ul>

        <ul class="app-menu-list  list-unstyled text-center">

            <li class="app-menu-list-item--header">
                <!-- <h2 style="color:#1c9ad5">Fun</h2> -->
                <img style="width:50px;height:50px" src="img/ui-menu-icons/header_fun.svg" alt="">
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-cubes" aria-hidden="true"></i> -->
                    <img style="width:25px;height:25px" src="img/ui-menu-icons/gioca--1.svg" alt="">
                    <span>Gioca</span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-calendar-check-o" aria-hidden="true"></i> -->
                    <img style="width:25px;height:25px" src="img/ui-menu-icons/crea.svg" alt="">
                    <span>Crea</span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <div class="">
                        <!-- <i class="fa fa-credit-card" aria-hidden="true"> -->
                        <img style="width:25px;height:25px" src="img/ui-menu-icons/pix--2.svg" alt="">
                            <span class="badge">
                                <?php echo rand(0,1000) ?> PIX
                            </span>
                        <!-- </i> -->
                    </div>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <div class="">
                        <!-- <i class="fa fa-credit-card" aria-hidden="true"></i> -->
                        <img style="width:25px;height:25px" src="img/ui-menu-icons/store.svg" alt="">
                        <span>Negozio</span>
                    </div>
                </a>
            </li>

            <!--
            <li class="app-menu-list-item">
                <a href="#">
                    <div class="">
                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                        <span>Amici</span>
                    </div>
                </a>
            </li>
            -->
        </ul>

        <ul class="app-menu-list  list-unstyled text-center">
            <li class="app-menu-list-item--header2">
                <!-- <h2 style="color:#1c9ad5">Job</h2> -->
                <img style="width:50px;height:50px" src="img/ui-menu-icons/header_job.svg" alt="">
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-bar-chart" aria-hidden="true"></i> -->
                    <img style="width:50px;height:50px" src="img/ui-menu-icons/profilo-job.svg" alt="">
                    <span>Profilo</span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-bar-chart" aria-hidden="true"></i> -->
                    <img style="width:50px;height:50px" src="img/ui-menu-icons/quiz.svg" alt="">
                    <span>Talento</span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-bar-chart" aria-hidden="true"></i> -->
                    <img style="width:50px;height:50px" src="img/ui-menu-icons/curriculum--1.svg" alt="">
                    <span>CV</span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-bar-chart" aria-hidden="true"></i> -->
                    <img style="width:50px;height:50px" src="img/ui-menu-icons/classifiche.svg" alt="">
                    <span>Class <span style="display:inline" class="label label-info">(v2)</span></span>
                </a>
            </li>
            <li class="app-menu-list-item">
                <a href="#">
                    <!-- <i class="fa fa-bar-chart" aria-hidden="true"></i> -->
                    <img style="width:50px;height:50px" src="img/ui-menu-icons/azienda--1.svg" alt="">
                    <span>Job</span>
                </a>
            </li>


            <li style="height:6vh;">&nbps;</li>


            <?php for ($i=0; $i < 0; $i++) : ?>
            <li class="app-menu-list-item">
                <a href="#">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <span>Credito</span>
                </a>
            </li>
            <?php endfor ?>
        </ul>

</div>
