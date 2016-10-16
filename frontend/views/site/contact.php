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

<div
    class="map single grey-bg"
    data-lat="50.4507"
    data-lng="30.469"
    data-marker="/design/img/pin.svg"
    data-color="#92c005"
    data-zoom="14"
    style="height: 300px;"
>
</div>

<div class="container wide contacts">

    <div class="mb2"></div>

    <div class="row">
        <div class="col-md-3">
            <div class="aside">
                <div class="box contacts shadow-2 white-bg">
                    <h3 class="mb1"><?= Yii::t('translate','our_contacts') ?></h3>
                    <div class="row thin pt1 pb1">
                        <div class="col-xs-3">
                            <div class="icon-circle">
                                <i class="fa fa-home"></i>
                            </div>
                        </div>
                        <div class="col-xs-9">
                            <h5><?= Yii::t('translate','address') ?></h5>
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
                            <h5><?= Yii::t('translate','phones') ?></h5>
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
                            <h5><?= Yii::t('translate','email') ?></h5>
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

                <h3 class="mb2"><?= Yii::t('translate','write_to_us') ?></h3>

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['class' => 'full mtr'])->label(Yii::t('translate','your_name').' <sup>*</sup>') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput(['class' => 'full mtr'])->label(Yii::t('translate','your_email').' <sup>*</sup>') ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'body')->textarea(['rows' => 5, 'class' => 'full mtr'])->label(Yii::t('translate','your_message').' <sup>*</sup>') ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-md-2">{image}</div><div class="col-md-3">{input}</div></div>',
                        'options' => ['class' => 'full mtr']
                    ])->label(Yii::t('translate','captcha').' <sup>*</sup>') ?>

                    <div class="form-group pt1 text-right">
                        <?= Html::submitButton('<i class="fa fa-envelope"></i> '.Yii::t('translate','submit'), ['class' => 'btn btn-mt btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
            

        </div>
        
    </div>
    <div class="mb2"></div>
</div>
