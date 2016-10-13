<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="main-bg pt1 pb1">
    <div class="container wide">
        <div class="row">
            <div class="col-md-6">
                <h1 class="white"><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-md-6">
                <ul class="breadcrumbs white lh1 right-md">
                    <li><a href="/">Главная</a></li>
                    <li>Контакты</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="mb2"></div>

    <div class="container wide contacts">

        <div class="map grey-bg" style="height: 300px;">
            
        </div>

        <div class="mb2"></div>

        <div class="row">
            <div class="col-md-3">
                <div class="aside">
                    <div class="box contacts shadow-2 white-bg">
                        <h3 class="mb1">Наши контакты</h3>
                        <div class="row thin pt1 pb1">
                            <div class="col-xs-3">
                                <div class="icon-circle">
                                    <i class="fa fa-home"></i>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <h5>Адрес</h5>
                                <div>Киев, Крещатик 1</div>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="row thin pt1 pb1">
                            <div class="col-xs-3">
                                <div class="icon-circle">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <h5>Телефоны</h5>
                                <a class="phone" href="tel:+380441234567">+38 (044) 123 4567</a>
                                <a class="phone" href="tel:+380441234567">+38 (044) 123 4567</a>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="row thin pt1 pb1">
                            <div class="col-xs-3">
                                <div class="icon-circle">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <h5>E-mail</h5>
                                <a class="email" href="mailto:mail@example.com">ail@example.com</a>
                            </div>
                        </div>
                        <div class="border-bottom mb1"></div>
                        <div class="icons pt1 pb1">
                            <a href="#" class="icon-square facebook"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="icon-square foursquare"><i class="fa fa-foursquare"></i></a>
                            <a href="#" class="icon-square instagram"><i class="fa fa-instagram"></i></a>
                            <a href="#" class="icon-square vk"><i class="fa fa-vk"></i></a>
                            <a href="#" class="icon-square linkedin"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box shadow-2 white-bg">

                    <h3 class="mb2">Напишите нам</h3>

                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'name')->textInput(['class' => 'full mtr']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput(['class' => 'full mtr']) ?>
                            </div>
                        </div>

                        <!-- <?= $form->field($model, 'subject')->textInput(['class' => 'full mtr']) ?> -->

                        <?= $form->field($model, 'body')->textarea(['rows' => 5, 'class' => 'full mtr']) ?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-md-2">{image}</div><div class="col-md-3">{input}</div></div>',
                            'options' => ['class' => 'full mtr']
                        ]) ?>

                        <div class="form-group pt1 text-right">
                            <?= Html::submitButton('<i class="fa fa-envelope"></i> Submit', ['class' => 'btn btn-mt btn-primary', 'name' => 'contact-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
                

            </div>
            
        </div>
        <div class="mb2"></div>
    </div>
