<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\web\View;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="invt-check-index">
    <?= GridView::widget([
        //'id' => 'kv-grid-demo',
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            /*[
                'attribute' => 'id',
                'headerOptions' => [
                    'width' => '50px',
                ],
            ],*/
            [
                'class'=>'kartik\grid\ExpandRowColumn',
                'width'=>'50px',
                'value'=>function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('/annualcheck/_check-details', ['model'=>$model]);
                },
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'expandOneOnly'=>true
            ],
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
            'occupy_by:ntext',
            'note:ntext',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
        ],
        'pager' => [
            'firstPageLabel' => Yii::t('app', 'รายการแรกสุด'),
            'lastPageLabel' => Yii::t('app', 'รายการท้ายสุด'),
        ],
        'responsive'=>true,
        'hover'=>true,
        'toolbar'=> [
            //'{export}',
            '{toggleData}',
        ],
        'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=> Html::icon('list-alt').' '.Html::encode($this->title),
            'before'=> DetailView::widget([
                'model' => $commmodel,
                'attributes' => [
                    [
                        'label' => $commmodel->attributeLabels()['user_id'],
                        'value' => $commmodel->user->fullname,
                        //'format' => ['date', 'long']
                    ],
                    [
                        'label' => $commmodel->attributeLabels()['position'],
                        'value' => $commmodel->position,
                        //'format' => ['date', 'long']
                    ],
                    [
                        'label' => $commmodel->attributeLabels()['year'],
                        'value' => $commmodel->year,
                        //'format' => ['date', 'long']
                    ],
                ],
            ]),
        ],
    ]); ?>
</div>