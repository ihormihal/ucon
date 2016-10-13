<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<?= $this->render('/site/home/_slider'); ?>

<section class="hotels pt2 pb5">
    <div class="container wide">

        <div class="pt1 pb3 text-center">
            <h2>Отели</h2>
        </div>
        
        <?= $this->render('/site/home/_hotels'); ?>
    </div>
</section>
