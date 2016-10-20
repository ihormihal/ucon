<?php
use backend\models\CatalogAccommodation;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<pre><?php  var_dump(array_keys(Yii::$app->authManager->getRolesByUser(1))); ?></pre>

<?php 

	$model = new CatalogAccommodation();
	$model->author = 1;

	if(Yii::$app->user->can('authorAccess', ['model' => $model])){
		echo 'authorAccess';
	}

?>
<br>
<?php

	if(Yii::$app->user->can('vendorAccess')){
		echo 'vendorAccess';
	}

?>
<br>
<?php

	if(Yii::$app->user->can('contentAccess')){
		echo 'contentAccess';
	}

?>
<br>
<?php

	if(Yii::$app->user->can('adminAccess')){
		echo 'adminAccess';
	}

?>

