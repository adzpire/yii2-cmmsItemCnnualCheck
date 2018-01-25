<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheckcommit */

?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
 			/*[
				'label' => $model->attributeLabels()['id'],
				'value' => $model->id,			
				//'format' => ['date', 'long']
			],*/
     			[
				'label' => $model->attributeLabels()['user_id'],
					'value' => $model->user->fullname,
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['position'],
				'value' => $model->position,			
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['year'],
                'value' => function($model){
                    return $model->year.' <span class="text-danger">('.($model->year+543).')</span>';
                },
                'format' => 'html',
			],
    	],
    ]) ?>