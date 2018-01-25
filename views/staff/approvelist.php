<?php

use yii\bootstrap\Html;
//use kartik\widgets\DatePicker;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckcommitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invt-checkcommit-index">

    <div class="panel panel-info">
        <div class="panel-heading">
            <span class="panel-title">
                <?php echo Html::icon('search') ?> ค้นหา
            </span>
        </div>
        <div class="panel-body">
            <?php Pjax::begin(); ?>
            <?php echo $this->render('_search', ['model' => $searchModel2]); ?>
            <?php
            if(isset($_GET['InvtCheckIndexSearch']) and $_GET['InvtCheckIndexSearch']['searchstring'] !== ''){
                echo GridView::widget([
                    //'id' => 'kv-grid-demo',
                    'dataProvider' => $dataProvider2,
                    'columns' => [
                        [
                            'attribute' => 'cc_id',
                            'value' => 'invtChecks.user.fullname',
                            'label' => 'ผู้ตรวจ',
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'searchstring',
                            'value' => 'invt.invt_name',
                            'label' => $searchModel->attributeLabels()['invt_id'],
                        ],
                        [
                            'attribute' => 'searchstring',
                            'value' => 'invt.invt_code',
                            'label' => 'รหัส',
                        ],
                        [
                            'attribute' => 'locName',
                            'value' => 'loc.loc_name',
                            'label' => $searchModel->attributeLabels()['loc_id'],
                            'filter' => false,
                        ],
                        //'status',
                        [
                            'attribute' => 'locName',
                            'value' => function ($model, $key, $index, $column) {
                                if ($model->status == 0) {
                                    return '<span class="label label-danger">ยังไม่ส่งการตรวจ</span>';
                                } elseif ($model->status == 1) {
                                    return '<span class="label label-warning">ส่งการตรวจแล้ว</span>';
                                } elseif ($model->status == 2) {
                                    return '<span class="label label-warning">พัสดุรับทราบแล้ว</span>';
                                }

                            },
                            'format' => 'html',
                            'label' => $searchModel->attributeLabels()['status'],
                            'filter' => false,
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{checking} {notify}',
                            'buttons' => [
                                'checking' => function ($url, $model, $key) {
                                    return Html::a(' ' . Html::icon('pencil') . ' ', ['checking', 'id' => $model->cc_id, 'lid' => $model->loc_id, 'it' => $model->id], ['class' => 'btn btn-sm btn-default', 'data-pjax' => 0, 'title' => 'แก้ไข', 'target' => '_blank']);
                                    //return Html::a(' '.$model->invtChecks->user_id.' :: '.Yii::$app->user->id, $url);
                                },
                                'notify' => function ($url, $model, $key) {
                                    //return Html::a(' ' . Html::icon('exclamation-sign') . ' แจ้ง', ['notify', 'id' => $model->cc_id, 'lid' => $model->loc_id, 'it' => $model->id], ['class' => 'btn btn-sm btn-success', 'data-pjax' => 0, 'title' => 'แจ้งให้ทราบ', 'target' => '_blank']);
                                    return Html::button( Html::icon('exclamation-sign'). ' แจ้ง', ['value' => Url::to(['notify', 'id'=>$model->id]),'class' => 'btn btn-sm btn-success _notify', 'title' => 'แจ้งให้ทราบ']);
                                    //return Html::a(' '.$model->invtChecks->user_id.' :: '.Yii::$app->user->id, $url);
                                },
                            ],
                            'headerOptions' => [
                                'width' => '50px',
                            ],
                            'visibleButtons' => [
                                /*'view' => Yii::$app->user->id == 122,
                                'update' => Yii::$app->user->id == 19, Yii::$app->user->id*/
                                'checking' => function ($model, $key, $index) {
                                    return $model->invtChecks->user_id === Yii::$app->user->id ? true : false;
                                },
                                'notify' => function ($model, $key, $index) {
                                    return $model->invtChecks->user_id !== Yii::$app->user->id ? true : false;
                                }

                            ],

                            /*'visible' => $model->invtChecks->user_id === Yii::$app->user->id,*/
                        ],
                    ],
                    'pager' => false,
                    'pjax'=> true,
                    'responsive' => true,
                    'hover' => true,
                    'toolbar' => false,
                    'panel' => false,
                    'resizableColumns'=>false,
                ]);
            }
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>

    <?= GridView::widget([
		//'id' => 'kv-grid-demo',
		'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'id',
				'headerOptions' => [
					'width' => '60px',
				],
			],
			[
				'attribute' => 'userName',
				'value' => 'user.fullname',
				'label' => $searchModel->attributeLabels()['user_id'],
			],
            'position',
            //'created_at',
            // 'created_by',
            // 'updated_at',
            [
                'attribute' => 'id',
                'headerOptions' => [
                    'width' => '90px',
                ],
                'header' => 'ยังไม่ตรวจ',
                'value' => function ($data) {
                    //return Html::a('<i class="glyphicon glyphicon-ok-circle"></i>',$url);
                    return $data->checkleft;
                },
                'contentOptions' => [
                    'class'=>'text-center text-danger',
                ],
                'filter' => false
            ],
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '{unchange}',
					'buttons' => [
						'unchange' => function ($url, $model, $key) {
							//return Html::a('<i class="glyphicon glyphicon-ok-circle"></i>',$url);
							return Html::a(' '.$model->unchange.' ', $url);
						},
					],
					'headerOptions' => [
						'width' => '80px',
					],
					'contentOptions' => [
						'class'=>'text-center',
					],
					'header' => 'ไม่เปลี่ยน',
				],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{change}',
				'buttons' => [
					'change' => function ($url, $model, $key) {
                        return Html::a(' '.$model->change.' ', $url);
					}
				],
				'headerOptions' => [
					'width' => '60px',
				],
				'contentOptions' => [
					'class'=>'text-center',
				],
				'header' => 'เปลี่ยน',
			],
        ],
		'pager' => [
			'firstPageLabel' => Yii::t('app', 'รายการแรกสุด'),
			'lastPageLabel' => Yii::t('app', 'รายการท้ายสุด'),
		],
		'responsive'=>true,
		'hover'=>true,
		'pjax' => true,
		'toolbar'=> false,
		'panel'=>[
			'type'=>GridView::TYPE_INFO,
			'heading'=> Html::icon('ok-circle').' '.Html::encode($this->title),
		],
    ]); ?>
<?php 	 /* adzpire grid tips
		[
				'attribute' => 'id',
				'headerOptions' => [
					'width' => '50px',
				],
			],
		[
		'attribute' => 'we_date',
		'value' => 'we_date',
			'filter' => DatePicker::widget([
					'model'=>$searchModel,
					'attribute'=>'date',
					'language' => 'th',
					'options' => ['placeholder' => Yii::t('app', 'enterdate')],
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'pickerButton' =>false,
					//'size' => 'sm',
					//'removeButton' =>false,
					'pluginOptions' => [
						'autoclose' => true,
						'format' => 'yyyy-mm-dd'
					]
				]),
			//'format' => 'html',			
			'format' => ['date']

		],	
		[
			'attribute' => 'we_creator',
			'value' => 'weCr.userPro.nameconcatened'
		],
	 */
 ?> 	
</div>
