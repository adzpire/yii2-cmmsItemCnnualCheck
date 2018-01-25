<?php

namespace backend\modules\iac\controllers;

use Yii;
use backend\modules\iac\models\InvtCheck;
use backend\modules\iac\models\InvtCheckSearch;
use backend\modules\iac\models\InvtCheckcommit;
use backend\modules\iac\models\InvtCheckcommitSearch;
use backend\modules\iac\models\InvtcommitteeSearch;

use backend\modules\inventory\models\InvtType;
use backend\modules\inventory\models\InvtBudgettype;
use backend\modules\inventory\models\InvtStatus;
use backend\modules\inventory\models\InvtMain;

use backend\modules\location\models\MainLocation;

use backend\components\AdzpireComponent;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * AnnualcheckController implements the CRUD actions for InvtCheck model.
 */
class AnnualcheckController extends Controller
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

    /*public $admincontroller = [20];

   /public function beforeAction(){
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
     * Lists all InvtCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
		 
		 Yii::$app->view->title = Yii::t('app', 'Invt Checks').' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        $searchModel = new InvtCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChoosecommittee()
    {

        Yii::$app->view->title = Yii::t('app', 'เลือกคณะกรรมการตรวจ').' - '.Yii::t('app', Yii::$app->controller->module->params['title']);

        $year = date('Y');

        $searchModel = new InvtCheckcommitSearch(['year'=>$year]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModel2 = new InvtcommitteeSearch(['iacyear'=>$year]);
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);

        return $this->render('choosecommittee', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
        ]);
    }

    public function actionSelinvt($id)
    {
        $year = date('Y');
        Yii::$app->view->title = Yii::t('app', 'เลือกพัสดุ/ครุภัณฑ์ ปี '.$year).' - '.Yii::t('app', Yii::$app->controller->module->params['title']);

        $commmodel = $this->findCommmodel($id);

        $checkmodel = new InvtCheckSearch(['cc_id'=>$id]);
        $checkdataProvider = $checkmodel->search(Yii::$app->request->queryParams);
        $checkdataProvider->sort = false;

        $searchModel = new InvtcommitteeSearch(['iacyear'=>$year]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $filterS = InvtStatus::getStatusList();
        //$filterBt = InvtBudgettype::getBudgetList();
        $filterT = InvtType::getTypeList();
        $filterL = MainLocation::getLocationList();

        return $this->render('selinvt', [
            'commmodel' => $commmodel,
            'checkmodel' => $checkmodel,
            'checkdataProvider' => $checkdataProvider,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterT' => $filterT,
            'filterS' => $filterS,
            'filterL' => $filterL,
        ]);
    }

    public function actionSeladd()
    {
        $post = Yii::$app->request->post();
        $pk = $post['row_id'];
        $ccid = $post['ccid'];
        //print_r(Yii::$app->request->post());
        //$ccid = Yii::$app->request->post('ccid');
//        echo $ccid.'dasda'; exit;
        foreach ($pk as $key => $value)
        {
            $invtmodel = $this->findInvtmodel($value);
            $model = new InvtCheck();
            $model->scenario = 'copycreate';
            $model->cc_id = $ccid;
            $model->invt_id = $invtmodel->id;
            $model->old_stat = $invtmodel->invt_statID;
            $model->old_loc = $invtmodel->invt_locationID;
            $model->old_occupy = $invtmodel->invt_occupyby;
            $model->stat_id = $invtmodel->invt_statID;
            $model->loc_id = $invtmodel->invt_locationID;
            $model->occupy_by = $invtmodel->invt_occupyby;
            if(!$model->save()){
                print_r($model->getErrors());exit;
            }
        }
    }

    public function actionDelinvtlist()
    {
        $pk = Yii::$app->request->post('row_id');
        //$ccid = Yii::$app->request->post('ccid');
//        echo $ccid.'dasda'; exit;
        foreach ($pk as $key => $value)
        {
            $this->findModel($value)->delete();
        }
    }
    /**
     * Displays a single InvtCheck model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		 $model = $this->findModel($id);
		 
		 Yii::$app->view->title = Yii::t('app', 'Detail').' : '.$model->id.' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new InvtCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		 Yii::$app->view->title = Yii::t('app', 'Create').' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        $model = new InvtCheck();

		/* if enable ajax validate
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}*/
		
        if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
                AdzpireComponent::succalert('addflsh', 'เพิ่มรายการเรียบร้อย');
			    return $this->redirect(['view', 'id' => $model->id]);
			}else{
                AdzpireComponent::dangalert('addflsh', 'เพิ่มรายการไม่ได้');
			}
            print_r($model->getErrors());exit;
        }

            return $this->render('create', [
                'model' => $model,
            ]);
        

    }

    /**
     * Updates an existing InvtCheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		 $model = $this->findModel($id);
		 
		 Yii::$app->view->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Invt Check',
]) . $model->id.' - '.Yii::t('app', Yii::$app->controller->module->params['title']);
		 
        if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
                AdzpireComponent::succalert('edtflsh', 'ปรับปรุงรายการเรียบร้อย');
			    return $this->redirect(['view', 'id' => $model->id]);
			}else{
                AdzpireComponent::dangalert('edtflsh', 'ปรับปรุงรายการไม่ได้');
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } 

            return $this->render('update', [
                'model' => $model,
            ]);
        

    }

    /**
     * Deletes an existing InvtCheck model.
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
     * Finds the InvtCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvtCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvtCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findCommmodel($id)
    {
        if (($model = InvtCheckcommit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('ไม่พบหน้าที่ต้องการ.');
        }
    }

    protected function findInvtmodel($id)
    {
        if (($model = InvtMain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
