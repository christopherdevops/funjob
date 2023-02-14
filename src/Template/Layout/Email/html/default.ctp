<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body>
    <?= $this->fetch('content') ?>

    <hr style="border-color:#00adee;border-size:1px;margin:15px">
    <footer>
        <div>
            <div id="logo" style="clear:both">
                <div style="background-color:#00adee;overflow:hidden;float:left;padding:5px">
                    <img style="width:40px;height:40px" src="<?= $this->Url->build('/logo.png', ['fullBase' => true]) ?>" alt="logo">
                </div>
                <div style="float:left;padding-left:20px">
                    <span style="color:#00adee;font-weight:bold">FunJob</span> <br>
                    <strong>“The Social Talent Network”</strong>
                    <br>
                    <a href="http://www.funjob.it">Website</a>
                </div>
            </div>

        </div>
    </footer>
</body>
</html>
