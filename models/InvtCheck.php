<?php

namespace backend\modules\iac\models;

use Yii;
use backend\modules\location\models\MainLocation;
use backend\modules\person\models\Person;
use backend\modules\inventory\models\InvtMain;
use backend\modules\inventory\models\InvtStatus;
use backend\modules\iac\models\InvtCheckcommit;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "invt_check".
 *
 
 * @property integer $id
 * @property integer $cc_id
 * @property integer $invt_id
 * @property integer $old_stat
 * @property integer $stat_id
 * @property integer $old_loc
 * @property integer $loc_id
 * @property string $old_occupy
 * @property string $occupy_by
 * @property string $note
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $status
 * @property InvtCheck $cc
 * @property InvtCheck[] $invtChecks
 * @property InvtMain $invt
 * @property MainLocation $loc
 * @property Person $createdBy
 * @property Person $updatedBy
 */
class InvtCheck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invt_check';
    }

    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

    public $ccName;
    public $invtChecksName;
    public $invtName;
    public $locName;
    public $statName;
    public $createdByName;
    public $updatedByName;

    public $invttotal;
    public $notetext;
/*add rule in [safe]
'ccName', 'invtChecksName', 'invtName', 'locName', 'createdByName', 'updatedByName', 
join in searh()
$query->joinWith(['cc', 'invtChecks', 'invt', 'loc', 'createdBy', 'updatedBy', ]);*/
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_id', 'old_stat', 'old_loc'], 'required', 'on' => 'copycreate'],
            //[['cc_id', 'invt_id', 'loc_id'], 'required'],
            [['cc_id', 'invt_id', 'old_stat', 'stat_id', 'old_loc', 'loc_id', 'status'], 'integer'],
            [['old_occupy', 'occupy_by', 'note'], 'string'],
            ['occupy_by', 'default', 'value' => null],
            [['cc_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvtCheckcommit::className(), 'targetAttribute' => ['cc_id' => 'id']],
            [['invt_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvtMain::className(), 'targetAttribute' => ['invt_id' => 'id']],
            [['old_stat'], 'exist', 'skipOnError' => true, 'targetClass' => InvtStatus::className(), 'targetAttribute' => ['old_stat' => 'id']],
            [['stat_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvtStatus::className(), 'targetAttribute' => ['stat_id' => 'id']],
            [['old_loc'], 'exist', 'skipOnError' => true, 'targetClass' => MainLocation::className(), 'targetAttribute' => ['old_loc' => 'id']],
            [['loc_id'], 'exist', 'skipOnError' => true, 'targetClass' => MainLocation::className(), 'targetAttribute' => ['loc_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cc_id' => 'คณะกรรมการ',
            'invt_id' => 'พัสดุ',
            'old_stat' => 'สถานะเดิม',
            'stat_id' => 'สถานะ',
            'old_loc' => 'สถานที่เดิม',
            'loc_id' => 'สถานที่',
            'old_occupy' => 'การครอบคอง/อื่นๆเดิม',
            'occupy_by' => 'การครอบครอง/อื่นๆ',
            'note' => 'หมายเหตุ(การตรวจ)',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'บันทึกวันที่',
            'updated_by' => 'Updated By',
            'status' => 'สถานะการตรวจ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvtChecks()
    {
        return $this->hasOne(InvtCheckcommit::className(), ['id' => 'cc_id']);
		
			/*
			$dataProvider->sort->attributes['invtChecksName'] = [
				'asc' => ['invt_check.name' => SORT_ASC],
				'desc' => ['invt_check.name' => SORT_DESC],
			];
			
			->andFilterWhere(['like', 'invt_check.name', $this->invtChecksName])
			->orFilterWhere(['like', 'invt_check.name1', $this->invtChecksName])
						in grid
			[
				'attribute' => 'invtChecksName',
				'value' => 'invtChecks.name',
				'label' => $searchModel->attributeLabels()['id']],
				'filter' => \InvtCheck::invtChecksList,
			],
			
			in view
			[
				'label' => $model->attributeLabels()[''id']'],
				'value' => $model->invtChecks->name,
				//'format' => ['date', 'long']
			],
			*/
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getUsedinvt($id)
    {
        $usedinvt = self::find()
            ->joinWith(['invtChecks'])
            ->select('invt_id')
            ->andWhere(['invt_checkcommit.year'=> $id])
            //->asArray()
            ->all();
        $arr = [];
        foreach ($usedinvt as $key => $value) {
            array_push($arr, $value['invt_id']);
        }
        return $arr;
    }

    public static function getTotalperuser($id)
    {
        $count = self::find()
            ->where([
                'cc_id' => $id,
            ])
            ->count();

        return $count;
    }
    public function getInvt()
    {
        return $this->hasOne(InvtMain::className(), ['id' => 'invt_id']);
		
			/*
			$dataProvider->sort->attributes['invtName'] = [
				'asc' => ['invt_main.name' => SORT_ASC],
				'desc' => ['invt_main.name' => SORT_DESC],
			];
			
			->andFilterWhere(['like', 'invt_main.name', $this->invtName])
			->orFilterWhere(['like', 'invt_main.name1', $this->invtName])
						in grid
			[
				'attribute' => 'invtName',
				'value' => 'invt.name',
				'label' => $searchModel->attributeLabels()['invt_id']],
				'filter' => \InvtMain::invtList,
			],
			
			in view
			[
				'label' => $model->attributeLabels()[''invt_id']'],
				'value' => $model->invt->name,
				//'format' => ['date', 'long']
			],
			*/
    }

    public function getOldStat()
    {
        return $this->hasOne(InvtStatus::className(), ['id' => 'old_stat']);

        /*
        $dataProvider->sort->attributes['oldStatName'] = [
            'asc' => ['invt_status.name' => SORT_ASC],
            'desc' => ['invt_status.name' => SORT_DESC],
        ];

        ->andFilterWhere(['like', 'invt_status.name', $this->oldStatName])
        ->orFilterWhere(['like', 'invt_status.name1', $this->oldStatName])
                    in grid
        [
            'attribute' => 'oldStatName',
            'value' => 'oldStat.name',
            'label' => $searchModel->attributeLabels()['old_stat'],
            'filter' => \InvtStatus::oldStatList,
        ],

        in view
        [
            'label' => $model->attributeLabels()['old_stat'],
            'value' => $model->oldStat->name,
            //'format' => ['date', 'long']
        ],
        */
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStat()
    {
        return $this->hasOne(InvtStatus::className(), ['id' => 'stat_id']);

        /*
        $dataProvider->sort->attributes['statName'] = [
            'asc' => ['invt_status.name' => SORT_ASC],
            'desc' => ['invt_status.name' => SORT_DESC],
        ];

        ->andFilterWhere(['like', 'invt_status.name', $this->statName])
        ->orFilterWhere(['like', 'invt_status.name1', $this->statName])
                    in grid
        [
            'attribute' => 'statName',
            'value' => 'stat.name',
            'label' => $searchModel->attributeLabels()['stat_id'],
            'filter' => \InvtStatus::statList,
        ],

        in view
        [
            'label' => $model->attributeLabels()['stat_id'],
            'value' => $model->stat->name,
            //'format' => ['date', 'long']
        ],
        */
    }

    public function getOldLoc()
    {
        return $this->hasOne(MainLocation::className(), ['id' => 'old_loc']);

        /*
        $dataProvider->sort->attributes['oldLocName'] = [
            'asc' => ['main_location.name' => SORT_ASC],
            'desc' => ['main_location.name' => SORT_DESC],
        ];

        ->andFilterWhere(['like', 'main_location.name', $this->oldLocName])
        ->orFilterWhere(['like', 'main_location.name1', $this->oldLocName])
                    in grid
        [
            'attribute' => 'oldLocName',
            'value' => 'oldLoc.name',
            'label' => $searchModel->attributeLabels()['old_loc'],
            'filter' => \MainLocation::oldLocList,
        ],

        in view
        [
            'label' => $model->attributeLabels()['old_loc'],
            'value' => $model->oldLoc->name,
            //'format' => ['date', 'long']
        ],
        */
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoc()
    {
        return $this->hasOne(MainLocation::className(), ['id' => 'loc_id']);
		
			/*
			$dataProvider->sort->attributes['locName'] = [
				'asc' => ['main_location.name' => SORT_ASC],
				'desc' => ['main_location.name' => SORT_DESC],
			];
			
			->andFilterWhere(['like', 'main_location.name', $this->locName])
			->orFilterWhere(['like', 'main_location.name1', $this->locName])
						in grid
			[
				'attribute' => 'locName',
				'value' => 'loc.name',
				'label' => $searchModel->attributeLabels()['loc_id']],
				'filter' => \MainLocation::locList,
			],
			
			in view
			[
				'label' => $model->attributeLabels()[''loc_id']'],
				'value' => $model->loc->name,
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

public function getInvtCheckList(){
		return ArrayHelper::map(self::find()->all(), 'id', 'title');
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
