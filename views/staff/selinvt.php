<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;

use backend\modules\iac\models\InvtCheck;
/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheckcommit */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายการพัสดุ/คณะกรรมการ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
.grid-view td {
    white-space: unset;
}
');
?>
<?php Pjax::begin(['id' => 'itempjax']); ?>
        <?php
//        echo DetailView::widget([
//            'model' => $commmodel,
//            'attributes' => [
//                [
//                    'label' => $commmodel->attributeLabels()['id'],
//                    'value' => $commmodel->id,
//                    //'format' => ['date', 'long']
//                ],
//                [
//                    'label' => $commmodel->attributeLabels()['user_id'],
//                    'value' => $commmodel->user->fullname,
//                    //'format' => ['date', 'long']
//                ],
//                [
//                    'label' => $commmodel->attributeLabels()['position'],
//                    'value' => $commmodel->position,
//                    //'format' => ['date', 'long']
//                ],
//                [
//                    'label' => $commmodel->attributeLabels()['year'],
//                    'value' => $commmodel->year,
//                    //'format' => ['date', 'long']
//                ],
//            ],
//        ]);
        echo GridView::widget([
            'id' => 'kv-grid-demo2',
            'dataProvider'=> $checkdataProvider,
            'columns' => [
                'invt_id',
                'invt.invt_name',
                'loc.loc_name',
                'occupy_by:ntext',
                // 'note:ntext',
                // 'created_at',
                // 'created_by',
                // 'updated_at',
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                ],
                /*[
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delinvtlist}',
                    'buttons' => [
                        'delinvtlist' => function ($url, $model, $key) {
                            //return Html::a('<i class="glyphicon glyphicon-ok-circle"></i>',$url);
                            return Html::a(Html::icon('remove-circle'), $url,['title' =>'ลบ', 'data-confirm'=>'คุณแน่ใจหรือไม่ที่จะลบรายการนี้?','data-method'=>'post', 'data-pjax' => 0, 'class'=>'_indydel']);
                        }
                    ],
                    'headerOptions' => [
                        'width' => '50px',
                    ],
                    'contentOptions' => [
                        'class'=>'text-center',
                    ],
                    'header' => 'ลบ',
                ],*/
            ],
            'pager' => [
                'firstPageLabel' => Yii::t('app', 'รายการแรกสุด'),
                'lastPageLabel' => Yii::t('app', 'รายการท้ายสุด'),
            ],
            'responsive'=>true,
            'hover'=>true,
            'pjax'=>true,
            'toolbar' => [
                ['content' =>
                    Html::a(Html::icon('minus') . ' ' . Yii::t('app', 'ลบที่เลือก'), ['#'], ['class' => 'btn btn-danger', 'id' => 'delButton', 'data-pjax' => 0])
                ],
                '{toggleData}',
            ],
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=> Html::icon('scissors').' '.Html::encode('รายการพัสดุ/ครุภัณฑ์ของกรรมการ'),
                'before'=> DetailView::widget([
                    'model' => $commmodel,
                    'attributes' => [
//                        [
//                            'label' => $commmodel->attributeLabels()['id'],
//                            'value' => $commmodel->id,
//                            //'format' => ['date', 'long']
//                        ],
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
                            'value' => function($model){
                                return $model->year.' <span class="text-danger">('.($model->year+543).')</span>';
                            },
                            'format' => 'html',
                        ],
                    ],
                ]),
                /*'before'=> function($model){
                    return $this->render('_commiteedetail', ['commmodel' => $model]);
                },*/
                //'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                //'footer'=>false
            ],
        ]); ?>

    <?= GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'options' => [
                'width' => '50px'
            ],
        ],
        [
            'attribute' => 'invt_name',
        ],
        [
            'attribute' => 'tname',
            'value' => 'invtType.invt_tname',
            'label' => $searchModel->attributeLabels()['invt_typeID'],
            'filter'=> $filterT,
        ],
        [
            'attribute' => 'lname',
            'value' => 'invtLocation.loc_name',
            'label' => $searchModel->attributeLabels()['invt_locationID'],
            'filter'=> $filterL,
        ],
        [
            'attribute' => 'sname',
            'value' => 'invtStat.invt_sname',
            'label' => $searchModel->attributeLabels()['invt_statID'],
            'filter'=> $filterS,
        ],
        //'invt_budgetyear',
        [
            'attribute' => 'invt_budgetyear',
            'value' => function($model){
                return $model->invt_budgetyear.' <span class="text-danger">('.($model->invt_budgetyear+543).')</span>';
            },
            'format' => 'html',
        ],
        //'invt_statID',
        // 'invt_code',
        // 'invt_name',
        // 'invt_brand',
        // 'invt_detail:ntext',
        // 'invt_image',
        // 'invt_ppp',
        // 'invt_budgetyear',
        'invt_occupyby',
        // 'invt_note:ntext',
        // 'invt_contact',
        // 'invt_buyfrom',
        // 'invt_buydate',
        // 'invt_checkindate',
        // 'invt_guarunteedateend',
        // 'invt_takeoutdate',
        // 'invt_date',

        [
            'class' => 'kartik\grid\CheckboxColumn',
        ],
    ],
    'pager' => [
        'firstPageLabel' => Yii::t('inventory/app', 'รายการแรกสุด'),
        'lastPageLabel' => Yii::t('inventory/app', 'รายการท้ายสุด'),
    ],
    'responsive' => true,
    'hover' => true,
//    'floatHeader'=>true,
//    'floatHeaderOptions'=>['scrollingTop'=>'0'],
    'toolbar' => [
        ['content' =>
            Html::a(Html::icon('plus') . ' ' . Yii::t('app', 'บันทึกที่เลือก'), ['#'], ['class' => 'btn btn-success', 'id' => 'addButton', 'data-pjax' => 0])
        ],
        '{toggleData}',
    ],
    'panel' => [
        'type' => GridView::TYPE_INFO,
        'heading' => Html::icon('scissors') . ' ' . Html::encode($this->title),
    ],
]); ?>
<?php Pjax::end(); ?>
<!--<a id="MyButton" class="btn btn-danger" href="/office/iac/annualcheck/#"><span class="glyphicon glyphicon-plus"></span> บันทึกที่เลือก</a>-->
<?php

$this->registerJs("
$(document).on('click', '#addButton', function (e) {
    e.preventDefault();
    var HotId = $('#kv-grid-demo').yiiGridView('getSelectedRows');
    if(HotId.length > 0){
        $.ajax({
            type: 'POST',
            url : ['seladd'],
            data : {row_id: HotId, ccid: ".$commmodel->id."},
            //data : {row_id: HotId},
            success : function(data) {
                if($('#itempjax').length == 0) {
                    alert('nooooo');
                }else{
                    alert(data);
                    $.pjax.reload({container:'#itempjax'});
                }
            }
        });
    }else{
       alert('empty');
    }
});
$(document).on('click', '#delButton', function (e) {
    e.preventDefault();
    var HotId = $('#kv-grid-demo2').yiiGridView('getSelectedRows');
    if(HotId.length > 0){
        $.ajax({
            type: 'POST',
            url : ['delinvtlist'],
            //data : {row_id: HotId, ccid: ".$commmodel->id."},
            data : {row_id: HotId},
            success : function(data) {
                if($('#itempjax').length == 0) {
                    alert('nooooo');
                }else{
                    alert(data);
                    $.pjax.reload({container:'#itempjax'});
                }
            }
        });
    }else{
       alert('empty');
    }
});
", View::POS_READY);

?>
