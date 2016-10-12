<?php 
use yii\helpers\Html;

?>
<div class="aside-menu">
    <nav>
        <ul>
            <li>
                <?= Html::a('<i class="icon fa fa-building"></i> <span class="title">Гостиницы</span>', ['catalog-accommodation/index'], []) ?>
            </li>
            <!-- <li>
                <?= Html::a('<i class="icon fa fa-umbrella"></i> <span class="title">Санатории</span>', ['category/view', 'id' => 1], []) ?>
            </li> -->
            
            <li class="parent <?php if(true) echo 'active';?>">
                <a href="javascript:void(0)">
                    <i class="icon fa fa-star"></i>
                    <span class="title">Атрибуты</span>
                </a>
                <ul>
                    <li>
                        <?= Html::a('<i class="icon fa fa-building"></i> <span class="title">Гостиницы</span>', ['catalog-attributes/index', 'model_name' => 'CatalogAccommodation']) ?>
                    </li>
                    <li>
                        <?= Html::a('<i class="icon fa fa-bed"></i> <span class="title">Номера</span>', ['catalog-attributes/index', 'model_name' => 'CatalogRooms']) ?>
                    </li>
                </ul>
            </li>
            <li>
                <?= Html::a('<i class="icon fa fa-language"></i> <span class="title">Языки</span>', ['lang/index']) ?>
            </li>
        </ul>

    </nav>
</div>