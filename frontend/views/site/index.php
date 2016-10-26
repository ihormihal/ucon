<?php

use frontend\widgets\PopularHotels;
use frontend\widgets\PopularRooms;

$this->title = 'My Yii Application';
?>

<?= $this->render('/site/home/_slider'); ?>

<section class="hotels pt2 pb2">
    <div class="container wide">
        <?= PopularHotels::widget();?>
        <?= PopularRooms::widget();?>
    </div>
</section>
