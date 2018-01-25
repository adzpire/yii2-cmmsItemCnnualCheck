<?php

namespace backend\modules\iac\controllers;

use Yii;
use backend\modules\iac\models\InvtCheckcommit;
use backend\modules\iac\models\InvtCheckcommitSearch;
use backend\modules\person\models\Person;

use backend\components\AdzpireComponent;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * AnnualcheckcommiteeController implements the CRUD actions for InvtCheckcommit model.
 */
class AnnualcheckcommiteeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

	 //public $admincontroller = [20];

    /* public function beforeAction(){
        foreach($this->admincontroller as $key){
            array_push(Yii::$app->controller->module->params['adminModule'],$key);
        }

        return true;

        if(ArrayHelper::isIn(Yii::$app->user->id, Yii::$app->controller->module->params['adminModule'])){
            //echo 'you are passed';
        }else{
            throw new ForbiddenHttpException('You have no right. Must be admin module.');
        }

    }*/
	 
    /**
     * Lists all InvtCheckcommit models.
     * @return mixed
     */
    public function actionIndex()
    {
		 
		 Yii::$app->view->title = Yii::t('app', 'รายการคณะกรรมการตรวจ').' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        $searchModel = new InvtCheckcommitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvtCheckcommit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		 $model = $this->findModel($id);
		 
		 Yii::$app->view->title = Yii::t('app', 'ดูรายละเอียด').' : '.$model->id.' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new InvtCheckcommit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		 Yii::$app->view->title = Yii::t('app', 'สร้างผู้ตรวจใหม่').' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        $model = new InvtCheckcommit();
        $year = date('Y');
		/* if enable ajax validate
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}*/
		
        if ($model->load(Yii::$app->request->post())) {
            $model->year = $year;
			if($model->save()){
                AdzpireComponent::succalert('addflsh', 'เพิ่มรายการใหม่เรียบร้อย');
			return $this->redirect(['view', 'id' => $model->id]);	
			}else{
				AdzpireComponent::dangalert('addflsh', 'เพิ่มรายการไม่ได้');
			}
            print_r($model->getErrors());exit;
        }
            //$qcmt = Staff::find()->all();
            //$committeelist = ArrayHelper::map($qcmt, 'user_id', 'person.fullname');
            $qstaff = Person::getPersonList();
            $qcode = InvtCheckcommit::find()->select('position')->limit(10)->distinct()->asArray()->all();
            $positionarr = [];
            foreach($qcode as $key => $value){
                array_push($positionarr,$value['position']);
            }



            $searchModel = new InvtCheckcommitSearch(['year'=>$year]);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->sort = false;

            return $this->render('create', [
                'model' => $model,
                'committeelist' => $qstaff,
                'positionarr' => $positionarr,
                'year' => $year,
                'dataProvider' => $dataProvider,
            ]);

    }

    /**
     * Updates an existing InvtCheckcommit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		 $model = $this->findModel($id);
		 
		 Yii::$app->view->title = Yii::t('app', 'ปรับปรุงรายการ {modelClass}: ', [
    'modelClass' => 'Invt Checkcommit',
]) . $model->id.' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
                AdzpireComponent::succalert('edtflsh', 'ปรับปรุงรายการเรียบร้อย');
			    return $this->redirect(['view', 'id' => $model->id]);
			}else{
                AdzpireComponent::dangalert('edtflsh', 'ปรับปรุงรายการไม่ได้');
			}
            print_r($model->getErrors());exit;
        }

        $committeelist = Person::getPersonList();

        $qcode = InvtCheckcommit::find()->select('position')->limit(10)->distinct()->asArray()->all();
        $positionarr = [];
        foreach($qcode as $key => $value){
            array_push($positionarr,$value['position']);
        }

            return $this->render('update', [
                'model' => $model,
                'committeelist' => $committeelist,
                'positionarr' => $positionarr,
            ]);
        

    }

    /**
     * Deletes an existing InvtCheckcommit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        AdzpireComponent::succalert('edtflsh', 'ลบรายการเรียบร้อย');

        return $this->redirect(['index']);
    }

    /**
     * Finds the InvtCheckcommit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvtCheckcommit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvtCheckcommit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('ไม่พบหน้าที่ต้องการ.');
        }
    }

}
