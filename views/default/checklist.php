<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;

use yii\helpers\Url;

use yii\widgets\DetailView;
use yii\widgets\Pjax;

use kartik\grid\GridView;

use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
.grid-view td {
    white-space: unset;
}
._fixhigh{
    height: 125px;
}
.tn-clickable:hover {
  cursor: pointer;
  cursor: hand; }
');
?>

<div class="invt-check-index">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="panel-title">
                <?php echo Html::icon('user') ?> ข้อมูลผู้ตรวจ
            </span>
        </div>
        <div class="panel-body">
            <?php
            if(isset($commmodel)) {
                echo $this->render('_commiteedetail', ['model' => $commmodel]);
                /*echo DetailView::widget([
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
                            'value' => function($model){
                                return $model->year.' <span class="text-danger">('.($model->year+543).')</span>';
                            },
                            'format' => 'html',
                        ],
                    ],
                ]);*/
            }else{
                echo 'ไม่มีการตรวจในปีนี้';
            } ?>
        </div>
    </div>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Html::icon('edit') . ' รายการยังไม่ส่งตรวจ' ?></span>
        </div>
        <div class="panel-body">

            <!--<h4><span class="text-danger">รายการแยกตามสถานที่</span>( <button class="btn btn-success"></button> : ตรวจแล้ว , <button class="btn btn-danger"></button> : ยังไม่ตรวจ)</h4>-->
            <p>คำแนะนำ : ถ้าไม่ได้เชื่อมต่ออินเตอร์เน็ตตลอดเวลาที่ตรวจให้ทำดังต่อไปนี้</p>
            <ul>
                <li>เปิดทุกรายการแยกไปแต่ละแท็บของเบราวเซอร์</li>
                <li class="text-danger">เมื่อเดินตรวจแล้วอย่าเพิ่งกด บันทึก </li>
                <li class="text-success">ต่ออินเตอร์เน็ตให้เรียบร้อย แล้วจึงกด บันทึก แต่ละแท็บ</li>
            </ul>
            <div class="row">
                <?php
                if(isset($commmodel)){
                    foreach($invtloc as $key => $value){
                ?>
                <div class="col-sm-6 col-md-3 _fixhigh text-center">
                    <div class="thumbnail tn-clickable" data-url="<?php echo Url::to(['default/check','id'=>$commmodel->id,'lid'=>$value->loc_id]); ?>" >
                        <h4><?php echo $value->loc->loc_name; ?></h4>
                        <p>จำนวน <span class="badge"><?php echo $value->invttotal; ?></span> รายการ</p>
                    </div>
                </div>
                <?php  }  ?>
            <?php  }else{
                echo 'ไม่มีการตรวจประจำปี สำหรับคุณ '.$this->context->checkperson->fullname;
            }  ?>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'header' => 'Quick Op',
    'id' => 'invttmodal',
]);
echo '<div id ="invttmodalcontent"></div>';
Modal::end();
?>
<?php
$this->registerJs("
/**
* Panel Clickable
* Like in front page, or quick actions
*/
$(document).ready(function(){
    $('.tn-clickable').click(function(){
    if($(this).data('url')!=''){
        window.open($(this).data('url'),'_blank');
    }
    });
});

//$('._notify').on('click', function(event){
$(document).on('click', '._notify', function(event){
//alert('fdf');
    event.preventDefault();
	$('#invttmodal').modal('show')
	.find('#invttmodalcontent')
	.load($(this).attr('value'));
    return false;
    });
    
", View::POS_END);
?>
