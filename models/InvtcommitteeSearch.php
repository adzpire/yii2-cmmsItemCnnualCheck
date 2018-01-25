<?php

namespace backend\modules\iac\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\inventory\models\InvtMain;

/**
 * InvtMainSearch represents the model behind the search form about `backend\modules\inventory\models\InvtMain`.
 */
class InvtcommitteeSearch extends InvtMain
{
    /**
     * @inheritdoc
     */
	  
	 /* adzpire gridview relation sort-filter
		public $weu;
		public $wecr;
	 
		add rule
		[['weu', 'wecr'], 'safe'],

		in function search()  //weU = wasterecycle_user userPro = user_profile
		$query->joinWith(['weU', 'weCr.userPro']); // weCr.userPro - 2layer relation
		$dataProvider->sort->attributes['weu'] = [
			'asc' => ['wasterecycle_user.wu_name' => SORT_ASC],
			'desc' => ['wasterecycle_user.wu_name' => SORT_DESC],
		];
		$dataProvider->sort->attributes['wecr'] = [
			'asc' => ['user_profile.firstname' => SORT_ASC],
			'desc' => ['user_profile.firstname' => SORT_DESC],
		];
		//add grid filter condition ->orFilterWhere for search wu_name or wu_lastname
		->andFilterWhere(['like', 'wasterecycle_user.wu_name', $this->weu])
		->orFilterWhere(['like', 'wasterecycle_user.wu_lastname', $this->weu])
		->andFilterWhere(['like', 'user_profile.firstname', $this->wecr])
		->orFilterWhere(['like', 'user_profile.lastname', $this->wecr]);
        
	 */
    public $tname;
    public $lname;
    public $sname;
    public $iacyear;
    public function rules()
    {
        return [
            [['id', 'invt_locationID', 'invt_typeID', 'invt_bdgttypID', 'invt_statID', 'invt_ppp', 'invt_contact'], 'integer'],
            [
                [
                'invt_code',
                'invt_name',
                'invt_brand',
                'invt_detail',
                'invt_image',
                'invt_budgetyear',
                'invt_occupyby',
                'invt_note',
                'invt_buyfrom',
                'invt_buydate',
                'invt_checkindate',
                'invt_guarunteedateend',
                'invt_takeoutdate',
                //'invt_date',
                'tname',
                'lname',
                'sname',
                ],
            'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InvtMain::find();
        $query->joinWith(['invtLocation', 'invtType', 'invtStat']); // weCr.userPro - 2layer relation
        $query->andWhere(['not in', 'invt_main.id', InvtCheck::getUsedinvt($this->iacyear)]);
        $query->andWhere('invt_status.id != 9');
        //$query->where(self::tableName().'.status = 1 AND '.self::tableName().'.id NOT IN ( SELECT v.id FROM vehicle v left join vehicle_borrow vb on v.id = vb.vehicle_id where vb.status =2 AND (date(vb.date_start) <= date(\''.$this->end.'\') AND date(vb.date_end) >= date(\''.$this->stt.'\') ))');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['tname'] = [
            'asc' => ['invt_type.invt_tname' => SORT_ASC],
            'desc' => ['invt_type.invt_tname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['lname'] = [
            'asc' => ['main_location.loc_name' => SORT_ASC],
            'desc' => ['main_location.loc_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['sname'] = [
            'asc' => ['invt_status.invt_sname' => SORT_ASC],
            'desc' => ['invt_status.invt_sname' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'invt_locationID' => $this->invt_locationID,
            'invt_typeID' => $this->invt_typeID,
            'invt_bdgttypID' => $this->invt_bdgttypID,
            'invt_statID' => $this->invt_statID,
            'invt_ppp' => $this->invt_ppp,
            'invt_budgetyear' => $this->invt_budgetyear,
            'invt_contact' => $this->invt_contact,
            'invt_buydate' => $this->invt_buydate,
            'invt_checkindate' => $this->invt_checkindate,
            'invt_guarunteedateend' => $this->invt_guarunteedateend,
            'invt_takeoutdate' => $this->invt_takeoutdate,
            //'invt_date' => $this->invt_date,
        ]);

        $query->andFilterWhere(['like', 'invt_code', $this->invt_code])
            ->andFilterWhere(['like', 'invt_name', $this->invt_name])
            ->andFilterWhere(['like', 'invt_brand', $this->invt_brand])
            ->andFilterWhere(['like', 'invt_detail', $this->invt_detail])
            ->andFilterWhere(['like', 'invt_image', $this->invt_image])
            ->andFilterWhere(['like', 'invt_occupyby', $this->invt_occupyby])
            ->andFilterWhere(['like', 'invt_note', $this->invt_note])
            ->andFilterWhere(['like', 'invt_buyfrom', $this->invt_buyfrom])
        ->andFilterWhere(['like', 'main_location.id', $this->lname])
        ->andFilterWhere(['like', 'invt_status.id', $this->sname])
        ->andFilterWhere(['like', 'invt_type.id', $this->tname]);

        return $dataProvider;
    }
}
