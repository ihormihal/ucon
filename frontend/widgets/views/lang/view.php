<?php
use yii\helpers\Html;
?>
<a href="#"><?= $current->name;?> <i class="fa fa-angle-down"></i></a>
<ul class="collection">
    <?php foreach ($langs as $lang):?>
        <li><?= Html::a($lang->name, '/'.$lang->url.Yii::$app->getRequest()->getLangUrl()) ?></li>
    <?php endforeach;?>
</ul>