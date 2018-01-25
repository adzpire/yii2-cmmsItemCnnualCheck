<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inventory\models\InvtCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="invt-check-index">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="panel-title">
                <?php echo Html::icon('user') ?> ข้อมูลผู้ตรวจ
            </span>
        </div>
        <div class="panel-body">
            <?php echo $ddd;
            if(isset($commmodel)) {
                echo $this->render('_commiteedetail', ['model' => $commmodel]);
            }else{
                echo 'ไม่มีการตรวจในปีนี้';
            } ?>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <span class="panel-title"><?php echo Html::icon('check') . ' รายการส่งตรวจแล้วรอพัสดุรับทราบ' ?></span>
        </div>
        <div class="panel-body">
            <div class="row">
                <?php
                if(isset($commmodel)){
                    foreach($invtlocchecked as $key => $value){
                        ?>
                        <div class="col-sm-6 col-md-3 _fixhigh text-center">
                            <div class="thumbnail tn-clickable" style="background: <?php echo $value->notetext === NULL ? '#f0e3d8' : '#dff0d8'; ?>" data-url="<?php echo Url::to(['default/checked','id'=>$commmodel->id,'lid'=>$value->loc_id]); ?>" >
                                <h4><?php echo $value->loc->loc_name; ?></h4>
                                <p>จำนวน <span class="badge"><?php echo $value->invttotal; ?></span> รายการ</p>
                            </div>
                        </div>
                    <?php  }  ?>
                <?php  }else{
                    echo 'ไม่มีการตรวจประจำปีสำหรับคุณ '.$this->context->checkperson->fullname.' หรือคุณยังไม่เลือกปีที่เป็นกรรมการ';
                }  ?>
            </div>
        </div>
    </div>
</div>
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
", View::POS_END);
$this->registerCss("
._fixhigh{
    height: 125px;
}
.tn-clickable:hover {
  cursor: pointer;
  cursor: hand; }
");
?>
