<?php

namespace backend\modules\iac;
use Yii;
/**
 * iac module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\iac\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		Yii::$app->formatter->locale = 'th_TH';
		Yii::$app->formatter->calendar = \IntlDateFormatter::TRADITIONAL;
		
		 if (!isset(\Yii::$app->i18n->translations['iac'])) {
            \Yii::$app->i18n->translations['iac'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@backend/modules/iac/messages'
            ];
        }

		parent::init();

		$this->layout = 'iac';
		$this->params['ModuleVers'] = '1.1';
		$this->params['title'] = 'ระบบตรวจสอบพัสดุ/ครุภัณฑ์ประจำปี';
        $this->params['modulecookies'] = 'iacck';
        // custom initialization code goes here
    }
}
