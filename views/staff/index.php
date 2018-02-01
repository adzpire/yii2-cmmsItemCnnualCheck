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
                    //'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],

//                    [
//                        'class'=>'kartik\grid\ExpandRowColumn',
//                        'width'=>'50px',
//                        'value'=>function ($model, $key, $index, $column) {
//                            return GridView::ROW_COLLAPSED;
//                        },
//                        'detail'=>function ($model, $key, $index, $column) {
//                            return Yii::$app->controller->renderPartial('_check-details', ['model'=>$model]);
//                        },
//                        'headerOptions'=>['class'=>'kartik-sheet-style'],
//                        'expandOneOnly'=>true
//                    ],
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

</div>
