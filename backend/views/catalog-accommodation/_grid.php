<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'full striped noborder'],
    'columns' => [
        ['class' => CheckboxColumn::className()],
        'id',
        'alias',
        'active',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'header' => 'Actions',
            'contentOptions' => ['width' => '30%', 'class' => 'text-right'],
            'buttons' => [
                'update' => function ($url,$model) {
                    return Html::a('<i class="fa fa-edit"></i> Edit', $url, ['class' => 'btn btn-mt btn-success']);
                },
                'delete' => function ($url,$model) {
                    return Html::a(
                        '<i class="fa fa-trash"></i> Delete', 
                        $url, 
                        [
                            'class' => 'btn btn-mt btn-danger', 
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this hotel?',
                                'method' => 'post',
                            ]
                        ]
                    );
                },
            ],
        ],
    ],
]); ?>