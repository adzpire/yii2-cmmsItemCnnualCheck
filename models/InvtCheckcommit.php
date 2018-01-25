<?php

namespace backend\modules\iac\models;

use Yii;
use backend\modules\person\models\Person;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use backend\modules\iac\models\InvtCheck;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "invt_checkcommit".
 *
 
 * @property integer $id
 * @property integer $user_id
 * @property string $position
 * @property string $year
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property Person $user
 * @property Person $createdBy
 * @property Person $updatedBy
 */
class InvtCheckcommit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invt_checkcommit';
    }

    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

public $userName; 
public $createdByName; 
public $updatedByName;
/*add rule in [safe]
'userName', 'createdByName', 'updatedByName', 
join in searh()
$query->joinWith(['user', 'createdBy', 'updatedBy', ]);*/
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'position', 'year'], 'required'],
            [['user_id'], 'integer'],
            [['year'], 'safe'],
            [['position'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => InvtCheck::className(), 'targetAttribute' => ['id' => 'cc_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'user_id' => 'เจ้าหน้าที่',
            'position' => 'ตำแหน่ง',
            'year' => 'ปีที่ตรวจ',
            'created_at' => 'สร้างวันที่',
            'created_by' => 'สร้างโดย',
            'updated_at' => 'ปรับปรุงวันที่',
            'updated_by' => 'ปรับปรุงโดย',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'user_id']);
		
			/*
			$dataProvider->sort->attributes['userName'] = [
				'asc' => ['person.name' => SORT_ASC],
				'desc' => ['person.name' => SORT_DESC],
			];
			
			->andFilterWhere(['like', 'person.name', $this->userName])
			->orFilterWhere(['like', 'person.name1', $this->userName])
						in grid
			[
				'attribute' => 'userName',
				'value' => 'user.name',
				'label' => $searchModel->attributeLabels()['user_id']],
				'filter' => \Person::userList,
			],
			
			in view
			[
				'label' => $model->attributeLabels()[''user_id']'],
				'value' => $model->user->name,
				//'format' => ['date', 'long']
			],
			*/
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'created_by']);
		
			/*
			$dataProvider->sort->attributes['createdByName'] = [
				'asc' => ['person.name' => SORT_ASC],
				'desc' => ['person.name' => SORT_DESC],
			];
			
			->andFilterWhere(['like', 'person.name', $this->createdByName])
			->orFilterWhere(['like', 'person.name1', $this->createdByName])
						in grid
			[
				'attribute' => 'createdByName',
				'value' => 'createdBy.name',
				'label' => $searchModel->attributeLabels()['created_by']],
				'filter' => \Person::createdByList,
			],
			
			in view
			[
				'label' => $model->attributeLabels()[''created_by']'],
				'value' => $model->createdBy->name,
				//'format' => ['date', 'long']
			],
			*/
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Person::className(), ['user_id' => 'updated_by']);
		
			/*
			$dataProvider->sort->attributes['updatedByName'] = [
				'asc' => ['person.name' => SORT_ASC],
				'desc' => ['person.name' => SORT_DESC],
			];
			
			->andFilterWhere(['like', 'person.name', $this->updatedByName])
			->orFilterWhere(['like', 'person.name1', $this->updatedByName])
						in grid
			[
				'attribute' => 'updatedByName',
				'value' => 'updatedBy.name',
				'label' => $searchModel->attributeLabels()['updated_by']],
				'filter' => \Person::updatedByList,
			],
			
			in view
			[
				'label' => $model->attributeLabels()[''updated_by']'],
				'value' => $model->updatedBy->name,
				//'format' => ['date', 'long']
			],
			*/
    }

    public function getInvtCheckcommitList(){
            return ArrayHelper::map(self::find()->all(), 'id', 'title');
        }

    public static function getCcYearList($user){
        return ArrayHelper::map(self::find()->where(['user_id' => $user])->all(), 'id', 'year');
    }

    public static function getDistinctyear($staff = false){
        if($staff == true){
            $tmp = ArrayHelper::map(self::find()->select('year')->distinct()->all(), 'year', 'year');
            $lastyear = date('Y');
            if(!in_array(date('Y'), $tmp)){
                array_push($tmp, $lastyear);
                return $tmp;
            }
        }
        return ArrayHelper::map(self::find()->select('year')->distinct()->all(), 'year', 'year');
    }

    public function getInvtc()
    {
        return $this->hasOne(InvtCheck::className(), ['cc_id' => 'id']);
    }
    public static function getAvialablelist($params, $ccid = NULL){
        $tmp1 = Person::getPersonList();
        if(!empty($ccid)){
            $tmp2 = ArrayHelper::map(self::find()->where(['year' => $params])->andWhere(['not', 'user_id='.$ccid])->all(), 'user_id', 'user_id');
        }else{
            $tmp2 = ArrayHelper::map(self::find()->where(['year' => $params])->all(), 'user_id', 'user_id');
        }
        $result=array_diff_key($tmp1,$tmp2);
        return $result;
    }

    public static function getPositionautocomplete(){
        $q = self::find()->select('position')->limit(10)->distinct()->asArray()->all();
        $positionarr = [];
        foreach($q as $key => $value){
            array_push($positionarr,$value['position']);
        }
        return $positionarr;
    }

    public function getCheckleft(){
        $countmdl = self::find()->joinWith(['invtc'])
            ->andWhere(['invt_checkcommit.id'=> $this->id])
            ->andWhere(['invt_check.status'=>0])
            ->count();
        return $countmdl;
    }
    public function getUnchange(){
        $countmdl = self::find()->joinWith(['invtc'])
            ->andWhere(['invt_checkcommit.id'=> $this->id])
            ->andWhere(['invt_check.status'=>1])
            ->andWhere(' invt_check.old_stat = invt_check.stat_id and invt_check.old_loc = invt_check.loc_id and invt_check.old_occupy like invt_check.occupy_by ')
            ->count();
        return $countmdl;
    }
    public function getChange(){
        $countmdl = self::find()->joinWith(['invtc'])
            ->andWhere(['invt_checkcommit.id'=> $this->id])
            ->andWhere(['invt_check.status'=>1])
            ->andWhere(' invt_check.old_stat != invt_check.stat_id or invt_check.old_loc != invt_check.loc_id or invt_check.old_occupy not like invt_check.occupy_by ')
            ->count();
        return $countmdl;
    }
/*
public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'เสนอ'),
                2 => Yii::t('app', 'อนุมัติ'),
                3 => Yii::t('app', 'ไม่อนุมัติ'),
                4 => Yii::t('app', 'คืนแล้ว'),
            ],
            'statusCondition'=>[
                1 => Yii::t('app', 'อนุมัติ'),
                0 => Yii::t('app', 'ไม่อนุมัติ'),
            ]
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        $status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case 0 :
            case NULL :
                $str = '<span class="label label-warning">' . $status . '</span>';
                break;
            case 1 :
                $str = '<span class="label label-primary">' . $status . '</span>';
                break;
            case 2 :
                $str = '<span class="label label-success">' . $status . '</span>';
                break;
            case 3 :
                $str = '<span class="label label-danger">' . $status . '</span>';
                break;
            case 4 :
                $str = '<span class="label label-succes">' . $status . '</span>';
                break;
            default :
                $str = $status;
                break;
        }

        return $str;
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }
    
    public static function getItemStatusConsider() {
        return self::itemsAlias('statusCondition');       
    }
*/
}
