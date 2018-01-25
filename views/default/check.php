<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\web\View;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
.grid-view td {
    white-space: unset;
}
.locsel {
width : 130px;
}
.statsel {
width : 100px;
}
.textinp {
width : 120px;
}
');
?>
<div class="invt-check-index">
    <div class="panel panel-success">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Html::icon('check') . ' ' . Html::encode('รายการตรวจ') ?></span>
            <?php echo Html::a(Html::icon('th-list') . ' ' . Yii::t('app', 'รายการตรวจสอบ'), ['/default'], ['class' => 'btn btn-default panbtn']) ?>
            <?php echo Html::a(Html::icon('hourglass') . ' ' . Yii::t('app', 'ประวัติการตรวจ'), ['/default'], ['class' => 'btn btn-default panbtn']) ?>
            <?php echo Html::a(Html::icon('chevron-down'), '#collapseExample', ['data-toggle'=>'collapse', 'aria-expanded'=> 'false', 'aria-controls'=>'collapseExample']) ?>
        </div>
        <div class="panel-body">
            <div class="collapse in" id="collapseExample">
                <div class="well well-sm">
                    <!--รายการตรวจของ <?php /*echo $model[0]->invtChecks[0]->user->fullname; */?> ตำแหน่ง <?php /*echo $model[0]->invtChecks[0]->position; */?> ปี --><?php /*echo $model[0]->invtChecks[0]->year; */?>
                    รายการตรวจของ <?php echo $mdlcmm->user->fullname; ?> ตำแหน่ง <?php echo $mdlcmm->position; ?> ปี <?php echo $mdlcmm->year.' <span class="text-danger">('.($mdlcmm->year+543).')</span>'; ?>
                </div>
            </div>
            <div class="input-group has-error">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                <input type="search" id="myInput" onkeyup="myFunction()"  class="form-control" placeholder="ค้นหา รหัสพัสดุ/ครุภัณฑ์">
            </div>
            <?php
            echo Html::beginForm();
            ?>
            <?php
                echo GridView::widget([
                    'id' => 'kv-grid-demo',
                    'dataProvider' => $dataProvider,
                    'filterModel' => false,
                    'containerOptions' => ['style' => 'overflow: auto'],
                    'responsiveWrap' => true,
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
                            'class' => 'kartik\grid\CheckboxColumn',
                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                            /*'value' => function ($model){
                                //return Html::dropDownList('InvtCheck[' . $model->id . '][loc_id]', $model->loc_id, $loclist, ['class' => 'form-control locsel']);
                                return Html::checkbox('InvtCheck['. $model->id .'][sel]');
                            },*/
                        ],
                        [
                            //'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'searchstring',
                            'value' => 'invt.invt_code',
                            'label' => 'รหัส',
                            'contentOptions' => ['style' => 'width:100px; white-space: normal;'],
                        ],
                        [
                            //'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'searchstring',
                            'value' => 'invt.shortdetail',
                            'label' => 'ชื่อ',
                            //'noWrap' => true,
                            'contentOptions' => ['style' => 'min-width:150px; white-space: normal;'],
                        ],
                        /*[
                            'attribute' => 'locName',
                            'value' => 'loc.loc_name',
                            'label' => 'สถานที่',
                        ],*/
                        [
                            'attribute' => 'locName',
                            'value' => function ($model) use ($loclist) {
                                return Html::dropDownList('InvtCheck[' . $model->id . '][loc_id]', $model->loc_id, $loclist, ['class' => 'form-control locsel']);
                            },
                            'format' => 'raw',
                            'label' => 'สถานที่',
                            'contentOptions' => ['style' => 'max-width:140px;'],
                        ],
                        [
                            'attribute' => 'stat_id',
                            'value' => function ($model) use ($statlist) {
                                return Html::dropdownList('InvtCheck[' . $model->id . '][stat_id]', $model->stat_id, ['1'=>'ระบุไม่ได้', '2'=>'ใช้การได้', '3'=>'ชำรุด', '4'=>'เสื่อมสภาพ', '5'=>'สูญหาย', '6'=>'ไม่ได้ใช้งาน'], ['class' => 'form-control statsel']);
                            },
                            'format' => 'raw',
                            'label' => 'สถานะ',
                            'contentOptions' => ['style' => 'width:110px;'],
                        ],
                        //'stat_id',
                        [
                            'attribute' => 'occupy_by',
                            'value' => function ($model){
                                return Html::textInput('InvtCheck[' . $model->id . '][occupy_by]', $model->occupy_by, ['class' => 'form-control textinp']);
                                //return $model->occupy_by;
                            },
                            'format' => 'raw',
                            'label' => 'การครอบครอง/อื่นๆ',
                            'contentOptions' => ['style' => 'width:120px;'],
                        ],
                        //'occupy_by',
                        //'note',
                        [
                            'attribute' => 'note',
                            'value' => function($model) {
                                return Html::textInput('InvtCheck[' . $model->id . '][occupy_by]', $model->note, ['class' => 'form-control textinp']);
                                //return $model->note;
                            },
                            'format' => 'raw',
                            'label' => 'หมายเหตุ(การตรวจ)',
                            'contentOptions' => ['style' => 'width:120px;'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a(Html::icon('eye-open'),['qdetail', 'id' => $model->id], ['class' => '_qdetail', 'data-pjax' => 0]);
                                    },
                            ],
                        ],
                    ],
                    'persistResize' => false,
                    'pager' => false,
                    'responsive' => true,
                    'hover' => true,
                    'toolbar' => false,
                    'panel' => false,
                    'resizableColumns'=>false,
                ]);
            ?>
            <div class="form-group text-center">
                <p class="text-danger">ถ้าไม่ได้เชื่อมต่ออินเตอร์เน็ตเมื่อตรวจแล้วอย่าเพิ่งกด บันทึก </p>
                <p class="text-success">ต่ออินเตอร์เน็ตให้เรียบร้อย แล้วจึงกด บันทึก</p>
                <h3 class="text-primary">ติ้กเลือกรายการที่ต้องการส่งด้วย</h3>
                <?= Html::submitButton(Html::icon('floppy-disk').' '.Yii::t('app', 'บันทึก'), ['class' => 'btn btn-success', 'id' => 'submitButton']) ?>
            </div>
            <?php echo Html::endForm(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("kv-grid-demo");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<?php
Modal::begin([
    'id' => 'modal',
    'header' => 'ข้อมูลพัสดุ',
]);
echo '<div id ="modalcontent"></div>';
Modal::end();
?>
<?php
$this->registerCss("
    .panel-body {
         padding: 0px;
    }
");
?>
<?php
$this->registerJs("
$('._qdetail').on('click', function(event){
		event.preventDefault();
		//alert($(this).attr('href'));
		$('#modal').modal('show')
		.find('#modalcontent')
		.load($(this).attr('href'));
			return false;//just to see what data is coming to js
    });

$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
$(document).on('click', '#submitButton', function (e) {    
    var HotId = $('#kv-grid-demo').yiiGridView('getSelectedRows');
    if(HotId.length == 0){
        e.preventDefault();
        alert('ไม่มีการเลือก');
    }
});
", View::POS_READY);

?>
