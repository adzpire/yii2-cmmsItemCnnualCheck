<?php

use yii\bootstrap\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheckcommit */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invt Checkcommits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invt-checkcommit-create">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="panel-title"><?= Html::icon('edit') . ' ' . Html::encode($this->title) ?></span>
            <?= Html::a(Html::icon('list-alt') . ' ' . Yii::t('app', 'entry'), ['index'], ['class' => 'btn btn-success panbtn']) ?>
        </div>
        <div class="panel-body">
            <?= $this->render('_formcommittee', [
                'model' => $model,
                'committeelist' => $committeelist,
                'positionarr' => $positionarr,
                'year' => $year,
            ]) ?>
        </div>
    </div>
    <?= GridView::widget([
        //'id' => 'kv-grid-demo',
        'dataProvider'=> $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'user.fullname',
                'label' => $model->attributeLabels()['user_id'],
            ],
            'position',
            [
                'attribute' => 'year',
                'value' => function($model){
                    return $model->year.' <span class="text-danger">('.($model->year+543).')</span>';
                },
                'format' => 'html',
            ],
            //'created_at',
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
        'toolbar'=> false,
        //'pjax' => true,
        'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=> Html::icon('user').' '.Html::encode('รายชื่อกรรมการปี '.$year),
        ],
    ]); ?>
</div>
