<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Html;
use yii\helpers\Url;
//use yii\bootstrap\Nav;
use backend\components\Monav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\ArrayHelper;
use kartik\widgets\Growl;
use kartik\widgets\SideNav;

AppAsset::register($this);

$js = <<< 'SCRIPT'
/* To initialize BS3 tooltips set this below */
$(function () {
    $("[data-toggle='tooltip']").on('click',function(e){
    e.preventDefault();
  }).tooltip();
});
/* To initialize BS3 popovers set this below */
$(function () {
    $("[data-toggle='popover']").on('click',function(e){
    e.preventDefault();
  }).popover();
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php
    $this->registerCss("
		.wrap > .container {
			 padding: 0px 15px 20px;
		}
		.cmmslogo{
			align-content: left;
			width: 45px;
			padding: 3px;
		}
		.nav-main-backend{
		    display : none; 
		}
		.navtablelogo{
			float:right;
		}
		.navbar-brand {
			 padding: 2px 15px;
		}
		.navbar-brand > img {
			 display: inline;
		}
		.breadcrumb>li+li:before {
            content:\"»\";
        }
        .nav {
            margin-bottom: 5px;
        }
		/* PADDING VERTICAL */
		.padding-v-xxs {
		padding-top: 5px;
		padding-bottom: 5px;
		}
		.padding-v-xs {
		padding-top: 10px;
		padding-bottom: 10px;
		}
		.padding-v-base {
		padding-top: 15px;
		padding-bottom: 15px;
		}
		.padding-v-md {
		padding-top: 20px;
		padding-bottom: 20px;
		}
		.padding-v-lg {
		padding-top: 30px;
		padding-bottom: 30px;
		}
		.line {
		width: 100%;
		height: 2px;
		margin: 10px 0;
		overflow: hidden;
		font-size: 0;
		}
		.line-xs {
		margin: 0;
		}
		.line-lg {
		margin-top: 15px;
		margin-bottom: 15px;
		}
		.line-dashed {
		background-color: transparent;
		border-bottom: 1px dashed #dee5e7 !important;
		}
		div.required label:after{
			content: \" *\";
			color: red;
		}
		.panbtn{
			float:right;
			margin: -5px 5px 0px 0px;
		}
		.media a{
			color: black;
			text-decoration: none;
		}
		.media:hover {
          background-color: #f5f5f5;
        }
        .alert-custom {
            color: #a15426;
            background-color: #ffffff;
            border: 1px solid #a15426;
        }
        .btn-link{
            padding: 15px;
        }
     ");
    ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php $modul = \Yii::$app->controller->module;
?>
<?php
$this->registerLinkTag([
    //'title' => 'Live News for Yii',
    'rel' => 'shortcut icon',
    'type' => 'image/x-icon',
    'href' => '/media/commsci.ico',
]);
?>
<div class="wrap" style="margin-top: -50px;">
    <?php
    NavBar::begin([
        'brandLabel' => '<img class="cmmslogo" alt="Brand" src="'.'/media/parallax/img/commsci_logo_black.png'.'">'.'<table class="navtablelogo"><tbody>
		  <tr><td>'.Yii::t( 'app', 'ระบบตรวจสอบพัสดุ/ครุภัณฑ์ประจำปี').'</td></tr>
		  <tr style="font-size: small;"><td>'.Yii::t( 'app', 'ระบบจัดการพัสดุ/ครุภัณฑ์ คณะวิทยาการสื่อสาร').'</td></tr>
		  </tbody></table>',
        'brandUrl' => Url::toRoute('/'.$modul->id),
        'innerContainerOptions' => ['class'=>'container-fluid'],
        'options' => [
            'class' => 'navbar-default',
        ],
    ]);
    $menuItems = [
        /*[
            'label' => Html::Icon('user').' '.Yii::t( 'app', 'คณะกรรมการ'),
            'url' => ['#'],
            'items' => [
                ['label' => Html::Icon('plus').' '.Yii::t( 'app', 'เพิ่ม'), 'url' => ['/iac/annualcheckcommitee/create']],
                ['label' => Html::Icon('th-list').' '.Yii::t( 'app', 'รายการ'), 'url' => ['/iac/annualcheckcommitee']],
            ],
            'visible' => \Yii::$app->user->can('IACStaff'),
        ],
        [
            'label' => Html::Icon('tag').' '.Yii::t( 'app', 'วัสดุ/กรรมการ'),
            'url' => ['#'],
            'items' => [
                ['label' => Html::Icon('plus').' '.Yii::t( 'app', 'เพิ่ม'), 'url' => ['/iac/annualcheck/choosecommittee']],
                ['label' => Html::Icon('th-list').' '.Yii::t( 'app', 'รายการ'), 'url' => ['/iac/annualcheck']],
            ],
            'visible' => \Yii::$app->user->can('IACStaff'),
        ],*/
        [
            'label' => Html::Icon('fullscreen') . ' ' . Yii::t('app', 'ระบบที่เกี่ยวข้อง'),
            'url' => ['#'],
            'items' => [
                ['label' => Html::Icon('folder-open') . ' ' . Yii::t('app', 'ระบบเอกสารและแบบฟอร์มออนไลน์'), 'url' => ['dochub/']],
                ['label' => Html::Icon('scissors') . ' ' . Yii::t('app', 'ระบบข้อมูลพัสดุ/ครุภัณฑ์'), 'url' => ['/inventory/']],
            ]
        ],
        ['label' => Html::Icon('folder-open').' '.Yii::t( 'app', 'รายงาน'), 'url' => Url::current(), 'options' => ['class' => 'disabled']],
        ['label' => Html::Icon('info-sign').' '.Yii::t( 'app', 'คำแนะนำการใช้งาน'), 'url' => ['default/readme']],
    ];
    if (Yii::$app->user->isGuest) {
        // $menuItems[] = ['label' => Yii::t( 'app', 'Signup'), 'url' => ['/site/signup']];
        $menuItems1[] = ['label' => Html::Icon('log-in').' '.Yii::t( 'app', 'เข้าสู่ระบบ'), 'url' => Yii::$app->user->loginUrl];
    } else {
        $menuItems1[] = ['label' => Html::Icon('dashboard').' '.Yii::t( 'app', 'office'), 'url' => ['/']];
        $menuItems1[] = ['label' => Html::Icon('globe').' '.Yii::t( 'app', 'หน้าเว็บไซต์หลัก'), 'url' => '/'];
        $menuItems1[] = '<li>'
            . Html::beginForm(['default/logout', 'url' => Url::current()], 'post')
            . Html::submitButton(
                Html::Icon('log-out') . ' ' . Yii::t('app', 'ออกจากระบบ') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
        /*$menuItems[] = [
            'label' => Html::Icon('option-horizontal') . ' ' . Yii::t('app', 'อื่นๆ'),
            'url' => ['#'],
            'items' =>
                [
                    '<li>'
                    .Html::a(Html::Icon('dashboard') . ' ' . Yii::t('app', 'office') , ['/'])
                    .'</li>',
                    '<li>'
                    . Html::a(Html::Icon('globe') . ' ' . Yii::t('app', 'หน้าเว็บไซต์หลัก'), '/')
                    . '</li>',
                    '<li>'
                    . Html::beginForm(['default/logout', 'url' => Url::current()], 'post')
                    . Html::submitButton(
                        Html::Icon('log-out') . ' ' . Yii::t('app', 'ออกจากระบบ') . ' (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>',
                ],
        ];*/
    }
    echo Monav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    echo Monav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems1,
    ]);
    NavBar::end();
    ?>
    <div class="container-fluid">
        <?php
        $cookies = Yii::$app->request->cookies;

        if (($cookie = $cookies->get($modul->params['modulecookies'])) !== null) {
            if($cookie->value != $modul->params['ModuleVers']){
                $delcookies = Yii::$app->response->cookies;
                $delcookies->remove($modul->params['modulecookies']);
            }
        }else{
            echo $this->render('/_version');
        }
        ?>
        <?= Breadcrumbs::widget([
            'encodeLabels' => false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => [
                'label' => Html::Icon('home'),
                'url' => Url::toRoute('/'.$modul->id),
            ],
        ]) ?>
        <?= Alert::widget() ?>
        <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
            <?php
            echo Growl::widget([
                'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
                //'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
                'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
                'showSeparator' => true,
                'delay' => 1, //This delay is how long before the message shows
                'pluginOptions' => [
                    'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                    'placement' => [
                        'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                        'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'center',
                    ]
                ]
            ]);
            ?>
        <?php endforeach; ?>
        <?php
            //check action for extend col
            $checkaction = Yii::$app->controller->action->id;
            $extendedpage = ['checking', 'check', 'checked','unchange', 'change'];
        ?>
        <div class='col-md-3' style='<?php echo ArrayHelper::isIn($checkaction, $extendedpage) ? 'display: none;' : false ?>' >
            <!-- -->
            <?php if (\Yii::$app->user->can('Staff')) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Html::icon('cloud-upload').' ผู้ตรวจ' ?></h3>
                </div>
                <div class="panel-body">
                    <p><strong>ข้อมูลผู้ใช้ : </strong> <?php echo Yii::$app->user->identity->profile->fullname; ?></p>
                    <p><strong>ปีที่เป็นกรรมการตรวจ :</strong>
                        <?php
                            if (($cookie = $cookies->get('ccyear')) !== null) {
                                echo '<span class="text-success">'.$cookies->get('checkyear').'</span>';
                            }else{
                                echo '<span class="text-danger">ยังไม่เลือก</span>';
                            }
                        ?>
                    </p>
                </div>
                <div class="table">
                    <?php
                    echo Monav::widget([
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => Html::icon('calendar').' เลือกปีที่เป็นกรรมการ',
                                'url' => ['default/selyear'],
                            ],
                            [
                                'label' => Html::icon('search').' ค้นหา',
                                'url' => ['default/'],
                            ],
                            [
                                'label' => Html::icon('inbox').' รายการยังไม่ส่งผลตรวจ',
                                'url' => ['default/checklist'],
                                'count' => '\backend\modules\iac\models\DefaultCheckSearch',
                            ],
                            [
                                'label' => Html::icon('edit').' รายการส่งแล้วรอพัสดุรับทราบ',
                                'url' => ['default/checkedlist'],
                            ],
                            [
                                'label' => Html::icon('hourglass').' ประวัติการตรวจ',
                                'url' => ['default/history'],
                            ],
                        ],
                        'options' => ['class' => 'nav-stacked'], // set this to nav-tab to get tab-styled navigation
                    ]); ?>
                </div>
            </div>
            <?php }
            //if (\Yii::$app->user->can('IACStaff')) {
            if (\Yii::$app->user->can('IACStaff')) {
                ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Html::icon('tasks').' เจ้าหน้าที่' ?></h3>
                </div>
                <div class="panel-body">
                    <p><strong>ปีที่ตรวจสอบ :</strong>
                        <?php
                        if (($cookie = $cookies->get('staffccyear')) !== null) {
                            echo '<span class="text-success">'.$cookie.'</span>';
                        }else{
                            echo '<span class="text-danger">ยังไม่เลือก</span>';
                        }
                        ?>
                    </p>
                </div>
                <div class="table">

                    <?php
                    echo Monav::widget([
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => Html::icon('calendar').' เลือกปีที่ตรวจสอบ',
                                'url' => ['staff/selyear'],
                            ],
                            [
                                'label' => Html::icon('user').' จัดการคณะกรรมการ-วัสดุ',
                                'url' => ['staff/committeelist'],
                                'visible' => \Yii::$app->user->can('IACStaff'),
                                //'count' => '\backend\modules\tc\models\TeacherContributionSearch',
                            ],
                            [
                                'label' => Html::icon('saved').' รับทราบรายการตรวจ',
                                'url' => ['staff/'],
                                //'count' => '\backend\modules\tc\models\TeacherContributionSearch',
                            ],
                            [
                                'label' => Html::icon('pencil').' รายการตรวจแล้ว(เปิดให้แก้ได้)',
                                'url' => ['staff/editlist'],
                            ],
                            [
                                'label' => Html::icon('stats').' รายงานชำรุด/เสื่อมสภาพ',
                                'url' => ['staff/brokenreport'],
                            ],
                            [
                                'label' => Html::icon('folder-open').' รายการทั้งหมด',
                                'url' => ['staff/'],
                            ],
                        ],
                        'options' => ['class' => 'nav-stacked'], // set this to nav-tab to get tab-styled navigation
                    ]); ?>
                </div>
            </div>
            <?php } ?>
            <?php
            //echo Yii::$app->controller->action->id;
            //echo Html::button('hide',['id'=>'hidecol', 'class'=>'btn btn-danger']);
            ?>
        </div>

        <?php
        //            in yii2-bootstrap Nav
        //            if(isset($item['count'])) {
        //                $item['count'];
        //                $searchModel = new $item['count']();
        //                //$searchModel = new \backend\modules\tc\models\TeacherContributionSearch();
        //                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //                //print_r($dataProvider);
        //                $count = $dataProvider->getTotalCount();
        //                //echo $count; exit;
        //                $label .=  Html::tag('span', $count,['class'=>'badge pull-right']);
        //            }
        //            echo SideNav::widget([
        //            'type' => 'primary',
        //            'encodeLabels' => false,
        //            'heading' => Html::icon('cog').' operation',
        //            'items' => [
        //            // Important: you need to specify url as 'controller/action',
        //            // not just as 'controller' even if default action is used.
        //                ['label' => 'Home', 'icon' => 'home', /*'url' => Url::to(['/site/home', 'type'=>$type]), 'active' => ($item == 'home')*/],
        //                ['label' => 'Home', 'icon' => 'home', /*'url' => Url::to(['/site/home', 'type'=>$type]), 'active' => ($item == 'home')*/],
        //
        //                    ['label' => 'Books', 'icon' => 'book',
        //                    'items' => [
        //                    ['label' => '<span class="pull-right badge">10</span> New Arrivals', 'url' => Url::to(['/site/new-arrivals', 'type'=>$type]), 'active' => ($item == 'new-arrivals')],
        //                    ['label' => '<span class="pull-right badge">5</span> Most Popular', 'url' => Url::to(['/site/most-popular', 'type'=>$type]), 'active' => ($item == 'most-popular')],
        //                    ['label' => 'Read Online', 'icon' => 'cloud', 'items' => [
        //                    ['label' => 'Online 1', 'url' => Url::to(['/site/online-1', 'type'=>$type]), 'active' => ($item == 'online-1')],
        //                    ['label' => 'Online 2', 'url' => Url::to(['/site/online-2', 'type'=>$type]), 'active' => ($item == 'online-2')]
        //                    ]],
        //                ]],
        //                ['label' => '<span class="pull-right badge">3</span> Categories', 'icon' => 'tags', 'items' => [
        //                    ['label' => 'Fiction', 'url' => Url::to(['/site/fiction', 'type'=>$type]), 'active' => ($item == 'fiction')],
        //                    ['label' => 'Historical', 'url' => Url::to(['/site/historical', 'type'=>$type]), 'active' => ($item == 'historical')],
        //                    ['label' => '<span class="pull-right badge">2</span> Announcements', 'icon' => 'bullhorn', 'items' => [
        //                    ['label' => 'Event 1', 'url' => Url::to(['/site/event-1', 'type'=>$type]), 'active' => ($item == 'event-1')],
        //                    ['label' => 'Event 2', 'url' => Url::to(['/site/event-2', 'type'=>$type]), 'active' => ($item == 'event-2')]
        //                    ]],
        //                ]],
        //                ['label' => 'Profile', 'icon' => 'user', 'url' => Url::to(['/site/profile', 'type'=>$type]), 'active' => ($item == 'profile')],
        //            ],
        //            ]);
        ?>
        <?php /*
echo Nav::widget([
    'encodeLabels' => false,
    'items' => [
        '<li style="padding: 10px;" role="presentation" class="disabled"><span class="glyphicon glyphicon-tasks"></span>  สำหรับเจ้าหน้าที่</li>',
        ['label' => Html::Icon('play').' '.Yii::t( 'app', 'Invt Mains'),
            'items' => [
                ['label' => Html::Icon('menu-right').' '.Yii::t( 'app', 'add'), 'url' => ['/inventory/invtmain/create']],
                ['label' => Html::Icon('menu-right').' '.Yii::t( 'app', 'index'), 'url' => ['/inventory/invtmain']],
            ],
        ],
    ],
    'options' => ['class' =>'nav-pills bg-warning'], // set this to nav-tab to get tab-styled navigation
]);*/
        ?>
        <div class="<?php echo ArrayHelper::isIn($checkaction, $extendedpage) ? 'col-md-12' : 'col-md-9' ?>">
            <?= $content ?>
        </div>

        <?php /*if(isset($this->blocks['block1'])){
			$this->blocks['block1'];
		 }else{
			echo 'no block';
		 }*/ ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p>© 2016 PSU YII DEV <span class="label label-danger"><?php echo $modul->params['ModuleVers']; ?></span>
            <?php echo '  '.Yii::t( 'app', 'พบปัญหาในการใช้งาน ติดต่อ - ').Html::icon('envelope'); ?> :  <?php echo Html::mailto('อับดุลอาซิส ดือราแม', 'abdul-aziz.d@psu.ac.th'); ?><?php echo ' '.Html::icon('earphone').' : '.Yii::t( 'app', 'โทรศัพท์ภายใน : 2618'); ?>
            <a href="#" data-toggle="tooltip" title="<?php echo Yii::t( 'app', 'responsive_web'); ?>"><img src="<?php echo '/uploads/adzpireImages/responsive-icon.png'; ?>" width="30" height="30" /></a>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
