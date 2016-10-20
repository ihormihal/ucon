<?php

use yii\helpers\Html;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\widgets\WLang;

?>

<div class="top-menu hide-md-under">
  <div class="container wide">
    <div class="row">
      <div class="col-md-6">
        <nav>
          <ul class="nav nav-inline">
            <li class="dropdown">
              <a href="#">UAH <i class="fa fa-angle-down"></i></a>
              <ul class="collection">
                <li><a href="#">USD</a></li>
                <li><a href="#">EUR</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <?= WLang::widget();?>
            </li>
          </ul>
        </nav>
      </div>
      <div class="col-md-6">
        <nav class="right-md">
          <ul class="nav nav-inline">
            <?php if (Yii::$app->user->isGuest): ?>
              <li><?= Html::a('Login', ['site/login'], []) ?></li>
              <li><?= Html::a('Signup', ['site/signup'], []) ?></li>
            <?php else: ?>
              <li>
                <?= Html::beginForm(['site/logout'], 'post') ?>
                <?= Html::submitButton('Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'link']) ?>
                <?= Html::endForm() ?>
              </li>
            <?php endif ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</div>
<div class="navbar nav-main white-bg shadow-2">
  <div class="container wide">
    <div class="row">
      <div class="col-md-3 col-xs-8">
        <div class="">
          <div class="logo">
            <img src="/design/images/logo.png" alt="">
          </div>
        </div>
      </div>
      <div class="col-md-9 col-xs-4">
        <div class="right">
          <nav class="show-lg-over">
            <?= Nav::widget([
              'options' => ['class' => 'nav nav-inline'],
              'items' => $menuItems,
            ]); ?>
          </nav>
          <div class="menu-open-bar text-right show-md-under"><i class="mti">menu</i></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="navbar-fixed-space"></div>
<div class="menu-slide slide-left white-bg">
  <nav>
    <?= Nav::widget([
      'options' => ['class' => 'nav nav-col border-bottom'],
      'items' => $menuItems,
    ]); ?>
    <ul class="nav nav-col">
      <li><a href="#">Login</a></li>
      <li><a href="#">Signup</a></li>
    </ul>
  </nav>
</div>
<div class="page-overlay"></div>