<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\jui\AutoComplete;
/*
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
*/
/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheckcommit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invt-checkcommit-form">

    <?php $form = ActiveForm::begin([
			'layout' => 'horizontal', 
			'id' => 'invt-checkcommit-form',
			//'validateOnChange' => true,
            //'enableAjaxValidation' => true,
			//	'enctype' => 'multipart/form-data'
			]); ?>

    <?php // $form->field($model, 'user_id')->textInput();
    echo $form->field($model, 'user_id')->widget(Select2::classname(), [
        'data' => $committeelist,
        'options' => ['placeholder' => Yii::t('app', 'ค้นหา/เลือก')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'position')->widget(\yii\jui\AutoComplete::classname(), [
        'options' => [
            'class' => 'form-control',
            'placeholder' => 'เช่น ประธานกรรมการ',
        ],
        'clientOptions' => [
            'source' => $positionarr,
        ],
    ]) ?>

    <?php
    if(!$model->isNewRecord) {
        $form->field($model, 'year')->widget(DatePicker::classname(), [
            'language' => 'th',
            'options' => ['placeholder' => 'enterdate'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy'
            ]]);
    }else{ ?>
        <div class="form-group field-invtcheckcommit-year required">
            <label class="control-label col-sm-3" for="invtcheckcommit-year">
                <?php echo $model->attributeLabels()['year'] ?>
            </label>
            <div class="col-sm-6">
                <div class="input-group">
                    <div class="form-control-static">
                        <?php echo $year.' <span class="text-danger">('.($year+543).')</span>'; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

<?php 		/* adzpire form tips
		$form->field($model, 'wu_tel', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]);
		//file field
				echo $form->field($model, 'file',[
		'addon' => [
       
'append' => !empty($model->wt_image) ? [
			'content'=> Html::a( Html::icon('download').' '.Yii::t('app', 'download'), Url::to('@backend/web/'.$model->wt_image), ['class' => 'btn btn-success', 'target' => '_blank']), 'asButton'=>true] : false
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
		*/
 ?>     <div class="form-group text-center">
        <?= Html::submitButton(Html::icon('floppy-disk').' '.Yii::t('app', 'บันทึก'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?php
//        if(!$model->isNewRecord){
//		 echo Html::resetButton( Html::icon('refresh').' '.Yii::t('app', 'Reset') , ['class' => 'btn btn-warning']);
//		 }
        echo Html::a( Html::icon('ban-circle').' '.Yii::t('app', 'ยกเลิก'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);
        ?>
		 
	</div>

    <?php ActiveForm::end(); ?>

</div>
