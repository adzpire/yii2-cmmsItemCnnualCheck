<?php

use yii\bootstrap\Html;
//use kartik\widgets\DatePicker;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invt-check-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
		//'id' => 'kv-grid-demo',
		'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'id',
				'headerOptions' => [
					'width' => '50px',
				],
			],
			[
				'class'=>'kartik\grid\ExpandRowColumn',
				'width'=>'50px',
				'value'=>function ($model, $key, $index, $column) {
					return GridView::ROW_COLLAPSED;
				},
				'detail'=>function ($model, $key, $index, $column) {
					return Yii::$app->controller->renderPartial('_check-details', ['model'=>$model]);
				},
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				'expandOneOnly'=>true
			],
            //'cc_id',
			[
				'attribute' => 'invtName',
				'value' => 'invt.invt_name',
				'label' => $searchModel->attributeLabels()['invt_id'],
			],
			[
				'attribute' => 'statName',
				'value' => 'stat.invt_sname',
				'label' => $searchModel->attributeLabels()['stat_id'],
			],
			[
				'attribute' => 'locName',
				'value' => 'loc.loc_name',
				'label' => $searchModel->attributeLabels()['loc_id'],
			],
            // 'occupy_by:ntext',
            // 'note:ntext',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
				[
					'class' => 'yii\grid\ActionColumn',
					/*'visibleButtons' => [
						'view' => Yii::$app->user->id == 122,
						'update' => Yii::$app->user->id == 19,
						'delete' => function ($model, $key, $index) {
										return $model->status === 1 ? false : true;
									}
						],
					'visible' => Yii::$app->user->id == 19,*/
				],
        ],
		'pager' => [
			'firstPageLabel' => Yii::t('app', '1stPagi'),
			'lastPageLabel' => Yii::t('app', 'lastPagi'),
		],
		'responsive'=>true,
		'hover'=>true,
		'toolbar'=> [
			['content'=>
				Html::a(Html::icon('plus'), ['create'], ['class'=>'btn btn-success', 'title'=>Yii::t('app', 'Add Book')]).' '.
				Html::a(Html::icon('repeat'), ['grid-demo'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')])
			],
			//'{export}',
			'{toggleData}',
		],
		'panel'=>[
			'type'=>GridView::TYPE_INFO,
			'heading'=> Html::icon('user').' '.Html::encode($this->title),
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
