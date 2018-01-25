<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
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
                    รายการตรวจของ <?php echo $model[0]->invtChecks->user->fullname; ?> ตำแหน่ง <?php echo $model[0]->invtChecks->position; ?> ปี <?php echo $model[0]->invtChecks->year.' <span class="text-danger">('.($model[0]->invtChecks->year+543).')</span>'; ?>
                </div>
            </div>
            <div class="input-group has-error">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                <input type="search" id="myInput" onkeyup="myFunction()"  class="form-control" placeholder="ค้นหา รหัสพัสดุ/ครุภัณฑ์">
            </div>
            <?php
            echo Html::beginForm();
            ?>
            <div class="table-responsive" style="overflow: auto">
            <table class="table table-condensed table-hover table-bordered table-striped" id="myTable">
                <thead>
                <tr>
                    <th width="25px"><input type="checkbox" id="checkAll"></th>
                    <th><?php echo 'หมายเลขรหัส'; //$newcheck->invt->getAttributeLabel('invt_code') ?></th>
                    <th><?php echo 'รายละเอียด'; ?></th>
                    <th><?php echo $newcheck->getAttributeLabel('loc_id') ?></th>
                    <th><?php echo $newcheck->getAttributeLabel('stat_id') ?></th>
                    <th><?php echo $newcheck->getAttributeLabel('occupy_by') ?></th>
                    <th><?php echo $newcheck->getAttributeLabel('note') ?></th>
                    <th width="25px"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($model as $key => $value) { ?>
                    <tr class="<?php echo (isset($it) and $it != 0) ? ($it == $value->id) ? 'danger' : false : false ?>">
                        <td>
                            <input name="InvtCheck[<?php echo $value->id ?>][sel]" type="checkbox">
                        </td>
                        <td style="min-width:100px; white-space: normal;"><?php echo $value->invt->invt_code ?></td>
                        <td style="min-width:150px; white-space: normal;"><?php echo $value->invt->shortdetail ?></td>
                        <td style="max-width:140px;">
                            <?php
                            echo Html::dropDownList('InvtCheck[' . $value->id . '][loc_id]', $value->loc_id, $loclist, ['class' => 'form-control locsel']);
                            //echo $value->invt->invtLocation->loc_name
                            ?>
                        </td>
                        <td><?php
                            echo Html::dropDownList('InvtCheck[' . $value->id . '][stat_id]', $value->stat_id, $statlist, ['class' => 'form-control statsel']);
                            //echo $value->invt->invtStat->invt_sname
                            ?>
                        </td>
                        <td><?php
                            echo Html::textInput('InvtCheck[' . $value->id . '][occupy_by]', $value->occupy_by, ['class' => 'form-control']);
                            //echo $value->invt->invt_occupyby
                            ?>
                        </td>
                        <td><?php
                            echo Html::textInput('InvtCheck[' . $value->id . '][note]', $value->note, ['class' => 'form-control']);
                            //echo $value->invt->invt_occupyby
                            ?>
                        </td>
                        <td class="text-center">
                            <?php echo Html::a(Html::icon('eye-open'),['qdetail', 'id' => $value->id], ['class' => '_qdetail', 'data-pjax' => 0]); ?>
<!--                            <a href="/office/iac/default/view?id=2" title="View" aria-label="View" data-pjax="0">-->
<!--                                <span class="glyphicon glyphicon-eye-open"></span>-->
<!--                            </a>-->
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
            </div>
            <div class="form-group text-center">
                <p class="text-danger">ถ้าไม่ได้เชื่อมต่ออินเตอร์เน็ตเมื่อตรวจแล้วอย่าเพิ่งกด บันทึก </p>
                <p class="text-success">ต่ออินเตอร์เน็ตให้เรียบร้อย แล้วจึงกด บันทึก</p>
                <h3 class="text-primary">ติ้กเลือกรายการที่ต้องการส่งด้วย</h3>
                <?= Html::submitButton(Html::icon('floppy-disk').' '.Yii::t('app', 'บันทึก'), ['class' => 'btn btn-success']) ?>
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
        table = document.getElementById("myTable");
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
 
", View::POS_READY);

?>
