<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\widgets\SearchHotelsAside;

$this->title = $model->content['title'];

//$this->params['breadcrumbs'][] = ['label' => 'Hotels', 'url' => ['catalog-accommodation/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="page-header pt1 pb1">
    <div class="container wide">
        <div class="row">
            <div class="col-md-6">
                <h1 class="white"><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-md-6">
                <div class="white lh1 right-md">
					<?= Breadcrumbs::widget([
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					]) ?>
				</div>
            </div>
        </div>
    </div>
</section>

<div class="mb2"></div>

<div class="container wide page-hotels">
	<div class="row">
		<div class="col-md-3">

			<div class="aside">
				
				<?= SearchHotelsAside::widget();?>
				
			</div>
		</div>
		<div class="col-md-9">
			<div class="hotels">
				<?= $this->render('_hotels', [
					'hotels' => $hotels,
				]) ?>
			</div>
		</div>
	</div>
</div>