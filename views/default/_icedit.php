<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
/*
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
*/
/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invt-check-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'invt-check-form',
			//'validateOnChange' => true,
            //'enableAjaxValidation' => true,
			//	'enctype' => 'multipart/form-data'
			]); ?>

    <div class="form-group">
        <label class="control-label  col-sm-3">ผู้ตรวจ</label>
        <div class="col-sm-9">
            <div class="form-control-static">
                <?php echo $model->invtChecks->user->fullname; ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label  col-sm-3">รหัส</label>
        <div class="col-sm-9">
            <div class="form-control-static">
                <?php echo $model->invt->concatened; ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'note')->textinput(['placeholder'=>'เช่น อยู่ห้อง A406'])->label('แจ้ง'); ?>

	<?php /* adzpire form tips
		$form->field($model, 'wu_tel', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]);
		//file field
				echo $form->field($model, 'file',[
		'addon' => [
       
'append' => !empty($model->wt_image) ? [
			'content'=> Html::a( Html::icon('download').' '.Yii::t('kpi/app', 'download'), Url::to('@backend/web/'.$model->wt_image), ['class' => 'btn btn-success', 'target' => '_blank']), 'asButton'=>true] : false
    ]])->widget(FileInput::classname(), [
			//'options' => ['accept' => 'image/*'],
			'pluginOptions' => [
				'showPreview' => false,
				'showCaption' => true,
				'showRemove' => true,
				'initialCaption'=> $model->isNewRecord ? '' : $model->wt_image,
				'showUpload' => false
			]
]);
		*/ ?>
    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ?  Html::icon('floppy-disk').' '.Yii::t('app', 'Save') :  Html::icon('floppy-disk').' '.Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord){
            echo Html::resetButton( Html::icon('refresh').' '.Yii::t('app', 'Reset') , ['class' => 'btn btn-warning']);
        } ?>
	</div>

    <?php ActiveForm::end(); ?>

    <?php
    $this->registerJs("
$('form#invt-check-form').on('beforeSubmit', function(event){
    //alert('ddddd');

	var form = $(this);
	$.post(
		form.attr('action'),
		form.serialize()
	).done(function(result){
		if(result == 1){
			form.trigger('reset');
			$('#invttmodal').modal('hide');
			alert('แจ้งแล้ว');
		}else{
			alert(result);
		}
	}).fail(function(result){
		alert('server error');
	});
	return false;
});
", View::POS_END);
    ?>
</div>
