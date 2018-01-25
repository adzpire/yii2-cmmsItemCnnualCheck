<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

use yii\web\View;
//use kartik\widgets\DatePicker;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckcommitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
.grid-view td {
    white-space: unset;
}
');
?>
    <div class="invt-checkcommit-index">
        <h4 class="text-center text-danger">หมายเหตุ : เมื่อกด [รับทราบที่เลือก] ระบบจะเปลี่ยนสถานะของพัสดุในฐานข้อมูลทันที</h4>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php Pjax::begin(['id' => 'apprpjax']); ?>
        <?= GridView::widget([
            'id' => 'kv-grid-demo',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'invt_id',
                    'value' => 'invt.invt_code',
                    'label' => 'รหัส'
                ],
                [
                    'attribute' => 'invt_id',
                    'value' => 'invt.longdetail'
                ],
                [
                    'attribute' => 'stat_id',
                    //'value' => 'stat.invt_sname',
                    'value' => function ($model) {
                        if($model->old_stat != $model->stat_id){
                            return Html::a($model->stat->invt_sname, '#', ['title'=>'เดิม: '.$model->oldStat->invt_sname]);
                        }else{
                            return $model->stat->invt_sname;
                        }
                    },
                    'contentOptions' => function ($model) {
                        if($model->old_stat != $model->stat_id){
                            return ['class' => 'bg-danger'];
                        }
                    },
                    'format' => ['raw'],
                ],
                [
                    'attribute' => 'loc_id',
//                    'value' => 'loc.loc_name',
                    'value' => function ($model) {
                        if($model->old_loc != $model->loc_id){
                           return Html::a($model->loc->loc_name, '#', ['title'=>'เดิม: '.$model->oldLoc->loc_name]);
                        }else{
                            return $model->loc->loc_name;
                        }
                    },
                    'contentOptions' => function ($model) {
                        if($model->old_loc != $model->loc_id){
                            return ['class' => 'bg-danger'];
                        }
                    },
                    'format' => ['raw'],
                ],
                [
                    'attribute' => 'occupy_by',
                    'value' => function ($model) {
                        if($model->old_occupy != $model->occupy_by){
                            return '<a href="#" title="เดิม: '.$model->old_occupy.'">'.$model->occupy_by.'</a>';
                        }else{
                            return $model->occupy_by;
                        }
                    },
                    'contentOptions' => function ($model) {
                        if($model->old_occupy != $model->occupy_by){
                            return ['class' => 'bg-danger'];
                        }
                    },
                    'format' => ['raw']
                ],
                'note',
                [
                    'attribute' => 'updated_at',
                    'format' => ['datetime']
                ],
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{qupdate}',
                    'buttons' => [
                        'qupdate' => function ($url, $model, $key) {
                            //return Html::a('<i class="glyphicon glyphicon-ok-circle"></i>',$url);
                            return Html::a(Html::icon('pencil'), $url, ['class' => '_qedit', 'data-pjax' => 0]);
                        },
                    ],
                    'headerOptions' => [
                        'width' => '50px',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                'header' => 'แก้ไขการตรวจ',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{updateinvt}',
                    'buttons' => [
                        'updateinvt' => function ($url, $model, $key) {
                            //return Html::a('<i class="glyphicon glyphicon-ok-circle"></i>',$url);
                            return Html::a(Html::icon('edit'), ['updateinvt', 'id' => $model->invt_id], ['class' => '_qeditinvt', 'data-pjax' => 0]);
                        }
                    ],
                    'headerOptions' => [
                        'width' => '50px',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                    'header' => 'แก้ไขอุปกรณ์',
                ],
            ],
            'pager' => [
                'firstPageLabel' => Yii::t('app', 'รายการแรกสุด'),
                'lastPageLabel' => Yii::t('app', 'รายการท้ายสุด'),
            ],
            'responsive' => true,
            'hover' => true,
            'pjax' => true,
            'toolbar' => [
                [
                    'content' =>
                        (\Yii::$app->controller->action->id == 'edit') ? Html::a(Html::icon('edit') . ' ' . Yii::t('app', 'ให้แก้ที่เลือก'), ['#'], ['class' => 'btn btn-danger', 'id' => 'editButton', 'data-pjax' => 0]) : Html::a(Html::icon('check') . ' ' . Yii::t('app', 'รับทราบที่เลือก'), ['#'], ['class' => 'btn btn-success', 'id' => 'apprButton', 'data-pjax' => 0]),
                ],
                [
                    'content' =>
                        Html::a(Html::icon('th-list') . ' ' . Yii::t('app', 'รายการตรวจสอบ'), ['index'], ['class' => 'btn btn-default']),
                ],
                [
                    'content' =>
                        Html::a(Html::icon('list') . ' ' . Yii::t('app', 'ตรวจสอบประจำปีที่ตรวจทราบแล้ว'), ['approvedlist'], ['class' => 'btn btn-primary']),
                ],
            ],
            'panel' => [
                'type' => GridView::TYPE_INFO,
                'heading' => Html::icon('ok-circle') . ' ' . Html::encode($this->title),
                'before' => DetailView::widget([
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
            ],
        ]); ?>
        <?php /* adzpire grid tips
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
        <?php Pjax::end(); ?>
    </div>
<?php
Modal::begin([
    'id' => 'modal',
]);
echo '<div id ="modalcontent"></div>';
Modal::end();
?>
<?php

$this->registerJs("
$(document).on('click', '#apprButton', function (e) {
    e.preventDefault();
    var HotId = $('#kv-grid-demo').yiiGridView('getSelectedRows');
    if(HotId.length > 0){
        $.ajax({
            type: 'POST',
            url : ['apprbatch'],
            data : {row_id: HotId, ccid: " . $commmodel->id . "},
            success : function(data) {
               //alert(data);
                if(data == '') {
                    alert('บันทึกเรียบร้อย');
                    $.pjax.reload({container:'#apprpjax'});
                }else{
                    alert('save error');
                }
            }
        });
    }else{
       alert('empty');
    }
});
$(document).on('click', '#editButton', function (e) {
    e.preventDefault();
    var HotId = $('#kv-grid-demo').yiiGridView('getSelectedRows');
    if(HotId.length > 0){
        $.ajax({
            type: 'POST',
            url : ['editbatch'],
            data : {row_id: HotId, ccid: " . $commmodel->id . "},
            success : function(data) {
               //alert(data);
                if(data == '') {
                    alert('บันทึกเรียบร้อย');
                    $.pjax.reload({container:'#apprpjax'});
                }else{
                    alert('save error');
                }
            }
        });
    }else{
       alert('empty');
    }
});
$(document).on('click', '._qedit', function (e) {
    e.preventDefault();
        $('#modal').find('.modal-header').html('แก้ไขการตรวจ<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>');
		$('#modal').modal('show')
		.find('#modalcontent')
		.load($(this).attr('href'));
    return false;
});
$(document).on('click', '._qeditinvt', function (e) {
    e.preventDefault();
        $('#modal').find('.modal-header').html('แก้ไขข้อมูลอุปกรณ์<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>');
		$('#modal').modal('show')
		.find('#modalcontent')
		.load($(this).attr('href'));
    return false;
});
$(document).on('pjax:end', function () {
    var btns = $(\"[data-toggle='popover']\");
    if (btns.length) {
        btns.popoverButton();
    }
 });
", View::POS_READY);

?>