<?php

use yii\bootstrap\Html;
//use kartik\widgets\DatePicker;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckcommitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invt-checkcommit-index">

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
					'width' => '60px',
				],
			],
			[
				'attribute' => 'userName',
				'value' => 'user.fullname',
				'label' => $searchModel->attributeLabels()['user_id'],
			],
            'position',
            'year',
            //'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '{selinvt}',
					'buttons' => [
						'selinvt' => function ($url, $model, $key) {
							//return Html::a('<i class="glyphicon glyphicon-ok-circle"></i>',$url);
							return Html::a(Html::icon('edit'), $url);
						}
					],
					'headerOptions' => [
						'width' => '60px',
					],
					'contentOptions' => [
						'class'=>'text-center',
					],
					'header' => 'เลือก',
				],
        ],
		'pager' => [
			'firstPageLabel' => Yii::t('app', '1stPagi'),
			'lastPageLabel' => Yii::t('app', 'lastPagi'),
		],
		'responsive'=>true,
		'hover'=>true,
		'pjax' => true,
		'toolbar'=> [
			['content'=>
				//Html::a(Html::icon('plus'), ['create'], ['class'=>'btn btn-success', 'title'=>Yii::t('app', 'Add Book')]).' '.
				Html::a(Html::icon('repeat'), ['grid-demo'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')])
			],
			//'{export}',
			'{toggleData}',
		],
		'panel'=>[
			'type'=>GridView::TYPE_INFO,
			'heading'=> Html::icon('bookmark').' '.Html::encode($this->title),
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
