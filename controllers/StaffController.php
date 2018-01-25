<?php

namespace backend\modules\iac\controllers;

use Yii;
use backend\modules\iac\models\InvtCheck;
use backend\modules\iac\models\InvtCheckSearch;
use backend\modules\iac\models\InvtCheckcommit;
use backend\modules\iac\models\InvtCheckcommitSearch;
use backend\modules\iac\models\StaffUnchangeSearch;
use backend\modules\iac\models\StaffChangeSearch;
use backend\modules\iac\models\StaffEditSearch;
use backend\modules\iac\models\StaffBrokenReport;
use backend\modules\iac\models\InvtCheckIndexSearch;
use backend\modules\iac\models\InvtcommitteeSearch;

use backend\modules\location\models\MainLocation;

use backend\modules\inventory\models\InvtMain;
use backend\modules\inventory\models\InvtType;
use backend\modules\inventory\models\InvtStatus;

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
 * Default controller for the `iac` module
 */
class StaffController extends Controller
{
    public $checkyear;
    public $moduletitle;
    public $checkperson;

    public function beforeAction($action){
        $this->checkperson = Person::findOne([Yii::$app->user->id]);
        $cookies = Yii::$app->request->cookies;
        $this->checkyear = $cookies->getValue('staffccyear');
        $this->moduletitle = Yii::t('app', Yii::$app->controller->module->params['title']);
        return parent::beforeAction($action);
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->view->title = Yii::t('app', 'รับทราบผลการตรวจ').' - '.$this->moduletitle;

        $searchModel = new InvtCheckcommitSearch(['year'=> date('Y')]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModel2 = new InvtCheckIndexSearch;
        if(!Yii::$app->request->queryParams){
            $param['InvtCheckIndexSearch']['status'] = '99';
            // พี่บ่าวลองเปลี่ยนดู มันได้ แต่มันโผล่ abcd ที่ textbox ลองเปลี่ยน searching เป็นฟิลด์อื่นที่ไม่เกี่ยวข้องกับการค้นหาหน้าเว็บดู
        }else{
            $param = Yii::$app->request->queryParams;
        }
        //print_r(Yii::$app->request->queryParams);exit();
        $dataProvider2 = $searchModel2->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'searchModel2' => $searchModel2,
        ]);
    }

    public function actionApprovelist()
    {
        if(!isset($this->checkyear)){
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่ตรวจสอบก่อน');
            return $this->redirect(['selyear']);
        }

        Yii::$app->view->title = Yii::t('app', 'รับทราบผลการตรวจ ปี ').$this->checkyear.' ('.($this->checkyear+543).') '.' - '.$this->moduletitle;

        $searchModel = new InvtCheckcommitSearch(['year'=> $this->checkyear]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModel2 = new InvtCheckIndexSearch;
        if(!Yii::$app->request->queryParams){
            $param['InvtCheckIndexSearch']['status'] = '99';
        }else{
            $param = Yii::$app->request->queryParams;
        }
        //print_r(Yii::$app->request->queryParams);exit();
        $dataProvider2 = $searchModel2->search($param);

        return $this->render('approvelist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'searchModel2' => $searchModel2,
        ]);
    }

    public function actionBrokenreport()
    {
        if(!isset($this->checkyear)){
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่ตรวจสอบก่อน');
            return $this->redirect(['selyear']);
        }

        $searchModel = new StaffBrokenReport(['checkyear'=> $this->checkyear]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->view->title = Yii::t('app', 'รายงานชำรุด/เสื่อมสภาพ').' ปี '.$this->checkyear.' ('.($this->checkyear+543).') '.'- '.$this->moduletitle;


        //$yearlist = InvtCheckcommit::getDistinctyear();
        $filterS = InvtStatus::getStatusList();
        $filterL = MainLocation::getLocationList();
        return $this->render('brokenreport', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'yearlist' => $yearlist,
            'filterS' => $filterS,
            'filterL' => $filterL,
        ]);
    }

    public function actionSelyear()
    {
        Yii::$app->view->title = Yii::t('app', ' เลือกปีที่ตรวจสอบ');

        $model = new InvtCheckcommit();

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $cookie = \Yii::$app->response->cookies;
            $cookie->add(new \yii\web\Cookie([
                'name' => 'staffccyear',
                'value' => $post['InvtCheckcommit']['id'],
                'expire' => time() + (60*60*24*30),
            ]));
            AdzpireComponent::succalert('addflsh', 'เลือกปีที่ตรวจสอบแล้ว');
            return $this->redirect(['index']);
        }

        $list = InvtCheckcommit::getDistinctyear(true);
        return $this->render('selyear', [
            'list' => $list,
            'model' => $model,
        ]);
    }

    public function actionRemovecookie()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('staffccyear');
    }

    public function actionCommitteelist()
    {
        if(isset($this->checkyear)){
            $searchModel = new InvtCheckcommitSearch(['year' => $this->checkyear]);
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่ตรวจสอบก่อน');
            return $this->redirect(['selyear']);
        }
        Yii::$app->view->title = Yii::t('app', 'รายการคณะกรรมการตรวจ').' - ปี '.$this->checkyear.' ('.($this->checkyear+543).') '.$this->moduletitle;

//        $searchModel = new InvtCheckcommitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('committeelist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreatecommittee()
    {
        if(isset($this->checkyear)){
            $year = $this->checkyear;
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่ตรวจสอบก่อน');
            return $this->redirect(['selyear']);
        }
        Yii::$app->view->title = Yii::t('app', 'สร้างผู้ตรวจใหม่').' - '.$this->checkyear.' ('.($this->checkyear+543).') '.$this->moduletitle;

        $model = new InvtCheckcommit();
        /* if enable ajax validate
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }*/

        if ($model->load(Yii::$app->request->post())) {
            $model->year = $year;
            if($model->save()){
                AdzpireComponent::succalert('addflsh', 'เพิ่มรายการใหม่เรียบร้อย');
                return $this->redirect('committeelist');
            }else{
                AdzpireComponent::dangalert('addflsh', 'เพิ่มรายการไม่ได้');
            }
            print_r($model->getErrors());exit;
        }
        //$qcmt = Staff::find()->all();
        //$committeelist = ArrayHelper::map($qcmt, 'user_id', 'person.fullname');
//        $qstaff = Person::getPersonList();
        $qstaff = InvtCheckcommit::getAvialablelist($year);
        $positionarr = InvtCheckcommit::getPositionautocomplete();

        $searchModel = new InvtCheckcommitSearch(['year'=>$year]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('createcommittee', [
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
    public function actionUpdatecommittee($id)
    {
        $model = $this->findCommmodel($id);

        Yii::$app->view->title = Yii::t('app', 'ปรับปรุงรายการ {modelClass}: ', [
                'modelClass' => 'Invt Checkcommit',
            ]) . $model->id.' - '.$this->moduletitle;

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                AdzpireComponent::succalert('edtflsh', 'ปรับปรุงรายการเรียบร้อย');
                return $this->redirect('committeelist');
            }else{
                AdzpireComponent::dangalert('edtflsh', 'ปรับปรุงรายการไม่ได้');
            }
            print_r($model->getErrors());exit;
        }

        $committeelist = InvtCheckcommit::getAvialablelist($model->year, $model->user_id);
        $positionarr = InvtCheckcommit::getPositionautocomplete();

        return $this->render('updatecommittee', [
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
    public function actionDeletecommittee($id)
    {
        $this->findCommmodel($id)->delete();

        AdzpireComponent::succalert('edtflsh', 'ลบรายการเรียบร้อย');

        return $this->redirect(['index']);
    }

    public function actionSelinvt($id)
    {
        if(isset($this->checkyear)){
            $year = $this->checkyear;
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่ตรวจสอบก่อน');
            return $this->redirect(['selyear']);
        }

        Yii::$app->view->title = Yii::t('app', 'เลือกพัสดุ/ครุภัณฑ์ ปี '.$this->checkyear.' ('.($this->checkyear+543).') ').' - '.Yii::t('app', Yii::$app->controller->module->params['title']);

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

    public function actionApprovedlist()
    {
        Yii::$app->view->title = Yii::t('app', 'รายการประจำปีที่ตรวจแล้ว').' - '.$this->moduletitle;

        $searchModel = new InvtCheckcommitSearch(['year'=> date('Y')]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approvedlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUnchange($id)
    {
        Yii::$app->view->title = Yii::t('app', 'รับทราบผลการตรวจประจำปีที่ไม่มีการเปลี่ยนแปลง');

        $commmodel = $this->findCommmodel($id);

        $searchModel = new StaffUnchangeSearch(['cc_id'=>$commmodel->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approve', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'commmodel' => $commmodel,
        ]);
    }

    public function actionChange($id)
    {
        Yii::$app->view->title = Yii::t('app', 'รับทราบผลการตรวจประจำปีที่มีการเปลี่ยนแปลง');

        $commmodel = $this->findCommmodel($id);

        $searchModel = new StaffChangeSearch(['cc_id'=>$commmodel->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approve', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'commmodel' => $commmodel,
        ]);
    }
    public function actionApprbatch()
    {
        $post = Yii::$app->request->post();
        $pk = $post['row_id'];
        //$ccid = $post['ccid'];
        foreach ($pk as $key => $value)
        {
            $checkmodel = $this->findModel($value);
            $checkmodel->status = 2;
//            $model->scenario = 'copycreate';
            if(!$checkmodel->save()){
                print_r($checkmodel->getErrors());exit;
            }
            $invtmain = $this->findInvtmain($checkmodel->invt_id);

            $invtmain->invt_locationID = $checkmodel->loc_id;
            $invtmain->invt_statID = $checkmodel->stat_id;
            $invtmain->invt_occupyby = $checkmodel->occupy_by;

            if(!$invtmain->save()){
                print_r($invtmain->getErrors());exit;
            }
        }
    }

    public function actionChecking($id, $lid)
    {
        $newcheck = new InvtCheck();

        //$post = Yii::$app->request->post('row_id');
        //$pk = $post['row_id'];
        if (Yii::$app->request->post('InvtCheck')) {
            $post = Yii::$app->request->post('InvtCheck');
            foreach($post as $key => $value){

                //$mdlcheck = new InvtCheck();
                $mdlcheck = $this->findModel($key);
                $mdlcheck->stat_id=$value['stat_id'];
                $mdlcheck->loc_id=$value['loc_id'];
                $mdlcheck->occupy_by=$value['occupy_by'];
                //$mdlcheck->invt_id=$key;
                //$mdlcheck->cc_id= $id;

                $mdlcheck->note = ($value['note'] == NULL) ? '-' : $value['note'];

                if(!$mdlcheck->save()){
                    print_r($mdlcheck->getErrors());exit;
                }
            }
            /*Yii::$app->getSession()->setFlash('addflsh', [
                'type' => 'success',
                'duration' => 4000,
                'icon' => 'glyphicon glyphicon-ok-circle',
                'message' => Yii::t('app', 'UrDataCreated'),
            ]);*/
            AdzpireComponent::succalert('addflsh', 'ตรวจสอบเรียบร้อย');
            return $this->redirect(['index']);
        }

        $mdlcmm = $this->findCommmodel($id);
        $model = InvtCheck::find()
            ->andWhere(
                [
                    'cc_id'=>$mdlcmm->id,
                    'loc_id' => $lid,
                ]
            )
            ->all();
        Yii::$app->view->title = Yii::t('app', 'รายการตรวจประจำปี ').' - '.$this->checkperson->fullname;

        $loclist = MainLocation::getLocationList();
        $statlist = InvtStatus::getStatusList();
        return $this->render('checking', [
            'model' => $model,
            'newcheck' => $newcheck,
            'loclist' => $loclist,
            'statlist' => $statlist,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = InvtCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findInvtmain($id)
    {
        if (($model = InvtMain::findOne($id)) !== null) {
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
            throw new NotFoundHttpException('ไม่พบหน้าที่ต้องการหรือไม่มีสิทธิเข้าถึง.');
        }
    }

    public function actionQupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                echo '1';
            }else{
                print_r($model->getErrors());exit;
            }
        }else{
            $loclist = MainLocation::getLocationList();
            $statlist = InvtStatus::getStatusList();
            return $this->renderAjax('_form', [
                'model' => $model,
                'loclist' => $loclist,
                'statlist' => $statlist,
            ]);
        }
    }

    public function actionUpdateinvt($id)
    {
        $model = $this->findInvtmain($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                echo '1';
            }else{
                print_r($model->getErrors());exit;
            }
        }else{
            return $this->renderAjax('_forminvt', [
                'model' => $model,
            ]);
        }
    }

    public function actionEditlist()
    {
        if(!isset($this->checkyear)){
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่ตรวจสอบก่อน');
            return $this->redirect(['selyear']);
        }

        Yii::$app->view->title = Yii::t('app', 'รายการประจำปี ').$this->checkyear.' ('.($this->checkyear+543).') '.'ที่ตรวจแล้ว(เปิดให้คนตรวจแก้ใหม่ได้) - '.$this->moduletitle;

        $searchModel = new InvtCheckcommitSearch(['year'=>$this->checkyear]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('editlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($id)
    {
        Yii::$app->view->title = Yii::t('app', 'รายการที่ตรวจแล้ว(เปิดให้ผู้ใช้แก้ได้)');

        $commmodel = $this->findCommmodel($id);

        $searchModel = new StaffEditSearch(['cc_id'=>$commmodel->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approve', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'commmodel' => $commmodel,
        ]);
    }

    public function actionEditbatch()
    {
        $post = Yii::$app->request->post();
        $pk = $post['row_id'];
        //$ccid = $post['ccid'];
        foreach ($pk as $key => $value)
        {
            $checkmodel = $this->findModel($value);
            $checkmodel->status = 1;
            if(!$checkmodel->save()){
                print_r($checkmodel->getErrors());exit;
            }
        }
    }
}
