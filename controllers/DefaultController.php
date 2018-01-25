<?php

namespace backend\modules\iac\controllers;

use backend\modules\inventory\models\InvtStatus;
use Yii;
use backend\modules\iac\models\InvtCheck;
use backend\modules\iac\models\InvtCheckSearch;
use backend\modules\iac\models\InvtCheckcommit;
use backend\modules\iac\models\InvtCheckcommitSearch;
use backend\modules\iac\models\InvtCheckIndexSearch;
use backend\modules\iac\models\DefaultCheckSearch;

use backend\modules\location\models\MainLocation;

use backend\modules\person\models\Person;

use backend\components\AdzpireComponent;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\Response;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * Default controller for the `iac` module
 */
class DefaultController extends Controller
{
    public $checkperson;
    public $moduletitle;
    public $committee;
    public function beforeAction($action){
        $this->checkperson = Person::findOne([Yii::$app->user->id]);
        $cookies = Yii::$app->request->cookies;
        $this->committee = $cookies->getValue('ccyear');
        $this->moduletitle = Yii::t('app', Yii::$app->controller->module->params['title']);
        return parent::beforeAction($action);
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if(isset($this->committee)){
            $commmodel = $this->findCommmodel($this->committee);
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่เป็นกรรมการก่อน');
            return $this->redirect(['selyear']);
        }
        Yii::$app->view->title = Yii::t('app', 'รายการตรวจประจำปี ').$commmodel->year.' - '.$this->checkperson->fullname;

        $invtloc = InvtCheck::find()
            ->select(['loc_id','count(id) AS invttotal', 'SUM(note) AS notetext'])
            ->andWhere([
                'cc_id'=>$commmodel->id,
                'status'=> 0,
            ])
            ->groupBy(['loc_id'])
            ->all();

        $searchModel = new InvtCheckIndexSearch;
        if(!Yii::$app->request->queryParams){
            $param['InvtCheckIndexSearch']['status'] = '99';
            // พี่บ่าวลองเปลี่ยนดู มันได้ แต่มันโผล่ abcd ที่ textbox ลองเปลี่ยน searching เป็นฟิลด์อื่นที่ไม่เกี่ยวข้องกับการค้นหาหน้าเว็บดู
        }else{
            $param = Yii::$app->request->queryParams;
        }
        //print_r(Yii::$app->request->queryParams);exit();
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'commmodel' => $commmodel,
            'invtloc' => $invtloc,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheckedlist()
    {
        if(isset($this->committee)){
            $commmodel = $this->findCommmodel($this->committee);
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่เป็นกรรมการก่อน');
            return $this->redirect(['selyear']);
        }

        Yii::$app->view->title = Yii::t('app', 'รายการที่ตรวจแล้วปี ').$commmodel->year.' - '.$this->checkperson->fullname;

        $invtlocchecked = InvtCheck::find()
            ->select(['loc_id','count(id) AS invttotal', 'SUM(note) AS notetext'])
            ->andWhere([
                'cc_id'=>$commmodel->id,
                'status'=> 1,
            ])
            ->groupBy(['loc_id'])
            ->all();

        return $this->render('checkedlist', [
            'commmodel' => $commmodel,
            'invtlocchecked' => $invtlocchecked,
        ]);
    }

    public function actionHistory()
    {
        Yii::$app->view->title = Yii::t('app', 'ประวัติการตรวจประจำปี ').' - '.$this->checkperson->fullname;

        $searchModel = new InvtCheckcommitSearch(['user_id'=>Yii::$app->user->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelyear()
    {
        Yii::$app->view->title = Yii::t('app', ' เลือกปีที่เป็นกรรมการตรวจ').' - '.$this->checkperson->fullname;

        $model = new InvtCheckcommit();

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $cookie = \Yii::$app->response->cookies;
            $cookie->add(new \yii\web\Cookie([
                'name' => 'ccyear',
                'value' => $post['InvtCheckcommit']['id'],
                'expire' => time() + (60*60*24*30),
            ]));
            $cookie->add(new \yii\web\Cookie([
                'name' => 'checkyear',
                'value' => $this->findCommmodel($post['InvtCheckcommit']['id'])->year,
                'expire' => time() + (60*60*24*30),
            ]));
            AdzpireComponent::succalert('addflsh', 'เลือกปีที่เป็นผู้ตรวจสอบแล้ว');
            return $this->redirect(['index']);/**/
            /*if($model->save()){
                AdzpireComponent::succalert('addflsh', 'เพิ่มรายการใหม่เรียบร้อย');
                return $this->redirect(['index']);
            }else{
                AdzpireComponent::dangalert('addflsh', 'เพิ่มรายการไม่ได้');
            }
            print_r($model->getErrors());exit;*/
        }

        $list = InvtCheckcommit::getCcYearList(Yii::$app->user->id);
        return $this->render('selyear', [
            'list' => $list,
            'model' => $model,
        ]);
    }

    public function actionChecking($id, $lid, $it = NULL)
    {
        $newcheck = new InvtCheck();

        //$post = Yii::$app->request->post('row_id');
        //$pk = $post['row_id'];
        if (Yii::$app->request->post('InvtCheck')) {
            $post = Yii::$app->request->post('InvtCheck');
//            print_r($post);exit();
            foreach($post as $key => $value){

                //$mdlcheck = new InvtCheck();

                $mdlcheck = $this->findModel($key);
                $mdlcheck->stat_id=$value['stat_id'];
                $mdlcheck->loc_id=$value['loc_id'];
                $mdlcheck->occupy_by=$value['occupy_by'];
                $mdlcheck->status=1;

                $mdlcheck->note = ($value['note'] == NULL) ? '-' : $value['note'];
                if(isset($value['sel'])){
//                    echo $value['stat_id'];
                    if(!$mdlcheck->save()){
                        print_r($mdlcheck->getErrors());exit;
                    }
                }

            }
//            exit();
            AdzpireComponent::succalert('addflsh', 'บันทึกเรียบร้อย');
            return $this->redirect(['index']);
        }

        if(isset($this->committee)){
            $mdlcmm = $this->findCommmodel($this->committee);
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่เป็นกรรมการก่อน');
            return $this->redirect(['selyear']);
        }
        $model = InvtCheck::find()
            ->andWhere([
                    'cc_id'=>$mdlcmm->id,
                    'loc_id' => $lid,
                    //'status' => [0,1],
                    'status' => 0,
                ])
            ->all();
        Yii::$app->view->title = Yii::t('app', 'รายการตรวจประจำปี ').' - '.$this->checkperson->fullname;

        $loclist = MainLocation::getLocationList();
        $statlist = InvtStatus::getStatusList();
        return $this->render('checking', [
            'model' => $model,
            'it' => $it,
            'newcheck' => $newcheck,
            'loclist' => $loclist,
            'statlist' => $statlist,
        ]);
    }

    public function actionCheck($id, $lid, $it = NULL)
    {
        $newcheck = new InvtCheck();

        Yii::$app->view->title = Yii::t('app', 'รายการตรวจประจำปี ').' - '.$this->checkperson->fullname;

        if (Yii::$app->request->post('InvtCheck')) {
            $post = Yii::$app->request->post('InvtCheck');
            $selarray = Yii::$app->request->post('selection');

            foreach($selarray as $key => $value){

                $mdlcheck = $this->findModel($value);
                $mdlcheck->stat_id = $post[$value]['stat_id'];
                $mdlcheck->loc_id = $post[$value]['loc_id'];
                $mdlcheck->occupy_by = $post[$value]['occupy_by'];
                $mdlcheck->status=1;
                $mdlcheck->note = $post[$value]['note'];

                if(!$mdlcheck->save()){
                    print_r($mdlcheck->getErrors());exit;
                }
            }
//            exit();
            AdzpireComponent::succalert('addflsh', 'บันทึกเรียบร้อย');
            return $this->redirect(['index']);
        }

        if(isset($this->committee)){
            $mdlcmm = $this->findCommmodel($this->committee);
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่เป็นกรรมการก่อน');
            return $this->redirect(['selyear']);
        }
        $searchModel = new DefaultCheckSearch(['cc_id'=>$mdlcmm->id, 'loc_id' => $lid]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $loclist = MainLocation::getLocationList();
        $statlist = InvtStatus::getStatusList();
        return $this->render('check', [
            'dataProvider' => $dataProvider,
            'it' => $it,
            'mdlcmm' => $mdlcmm,
            'loclist' => $loclist,
            'statlist' => $statlist,
        ]);
    }

    public function actionChecked($id, $lid)
    {
        $newcheck = new InvtCheck();

        if (Yii::$app->request->post('InvtCheck')) {
            $post = Yii::$app->request->post('InvtCheck');
            foreach($post as $key => $value){

                $mdlcheck = $this->findModel($key);
                $mdlcheck->stat_id=$value['stat_id'];
                $mdlcheck->loc_id=$value['loc_id'];
                $mdlcheck->occupy_by=$value['occupy_by'];
                $mdlcheck->status=1;

                $mdlcheck->note = ($value['note'] == NULL) ? '-' : $value['note'];
                if(isset($value['sel'])){
                    if(!$mdlcheck->save()){
                        print_r($mdlcheck->getErrors());exit;
                    }
                }

            }
            AdzpireComponent::succalert('addflsh', 'บันทึกเรียบร้อย');
            return $this->redirect(['index']);
        }

        if(isset($this->committee)){
            $mdlcmm = $this->findCommmodel($this->committee);
        }else{
            AdzpireComponent::dangalert('addflsh', 'ต้องเลือกปีที่เป็นกรรมการก่อน');
            return $this->redirect(['selyear']);
        }
        $model = InvtCheck::find()
            ->andWhere([
                'cc_id'=>$mdlcmm->id,
                'loc_id' => $lid,
                //'status' => [0,1],
                'status' => 1,
            ])
            ->all();
        Yii::$app->view->title = Yii::t('app', 'รายการที่ตรวจแล้ว ').' - '.$this->checkperson->fullname;

        $loclist = MainLocation::getLocationList();
        $statlist = InvtStatus::getStatusList();
        return $this->render('checking', [
            'model' => $model,
            'newcheck' => $newcheck,
            'loclist' => $loclist,
            'statlist' => $statlist,
        ]);
    }

    public function actionView($id)
    {
        Yii::$app->view->title = Yii::t('app', 'รายการตรวจประจำปี ').' - '.$this->checkperson->fullname;

        $commmodel = InvtCheckcommit::find()
            ->andWhere([
                'id'=>$id
            ])->one();

        $searchModel = new InvtCheckSearch(['cc_id'=>$commmodel->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'commmodel' => $commmodel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

    protected function findCommmodel($id)
    {
        //echo $this->committee;exit();
        if (($model = InvtCheckcommit::findOne($id)) !== null AND $model->user_id == Yii::$app->user->id) {
            return $model;
        } else {
            throw new NotFoundHttpException('ไม่พบหน้าที่ต้องการหรือไม่มีสิทธิเข้าถึงงง.');
        }
    }

    public function actionReadme()
    {
        return $this->render('readme');
    }

    public function actionRemovecookie()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('ccyear');
    }

    public function actionSetvercookies()
    {
        $cookie = \Yii::$app->response->cookies;
        $cookie->add(new \yii\web\Cookie([
            'name' => \Yii::$app->controller->module->params['modulecookies'],
            'value' => \Yii::$app->controller->module->params['ModuleVers'],
            'expire' => time() + (60*60*24*30),
        ]));
        $this->redirect(Url::previous());
    }

    public function actionQdetail($id)
    {
        $model = $this->findModel($id);

        return $this->renderPartial('_invtdetail', [
            'model' => $model->invt,
        ]);
    }

    public function actionNotify($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                echo 1;
            }else{
                echo 0;
            }

        }else{
            return $this->renderAjax('_icedit', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('ccyear');
        $cookies->remove('staffccyear');
        return $this->goHome();
    }

}
