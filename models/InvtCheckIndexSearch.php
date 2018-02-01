<?php

namespace backend\modules\iac\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\iac\models\InvtCheck;

/**
 * InvtCheckSearch represents the model behind the search form about `backend\modules\inventory\models\InvtCheck`.
 */
class InvtCheckIndexSearch extends InvtCheck
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
    public $searchstring;
    public $checkyear;
    public function rules()
    {
        return [
            [['id', 'cc_id', 'invt_id', 'old_stat', 'stat_id', 'old_loc', 'loc_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'status'], 'integer'],
            [['old_occupy','occupy_by', 'note',
                'invtName', 'locName','statName', 'searchstring', 'checkyear' ], 'safe'],
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
        $session = Yii::$app->session;
        $year = $session->get('staffccyear');
        //echo $year;
        $query = InvtCheck::find();
        $query->joinWith(['invt', 'invtChecks', 'loc', 'stat']);
        // add conditions that should always apply here
        $query->andWhere(['invt_checkcommit.year'=> $year ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->load($params);
        $dataProvider->sort->attributes['invtName'] = [
            'asc' => ['invt_main.invt_name' => SORT_ASC],
            'desc' => ['invt_main.invt_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['locName'] = [
            'asc' => ['main_location.loc_name' => SORT_ASC],
            'desc' => ['main_location.loc_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['statName'] = [
            'asc' => ['invt_status.invt_sname' => SORT_ASC],
            'desc' => ['invt_status.invt_sname' => SORT_DESC],
        ];
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cc_id' => $this->cc_id,
            'invt_id' => $this->invt_id,
            'stat_id' => $this->stat_id,
            'loc_id' => $this->loc_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);
        $query->andWhere(' (invt_main.invt_name LIKE \'%'.$this->searchstring.'%\' OR invt_main.invt_code LIKE \'%'.$this->searchstring.'%\' OR invt_main.invt_brand LIKE \'%'.$this->searchstring.'%\' OR invt_main.invt_detail LIKE \'%'.$this->searchstring.'%\') ');
//        $query->orFilterWhere(['like', 'invt_main.invt_name', $this->searchstring])
//            ->orFilterWhere(['like', 'invt_main.invt_code', $this->searchstring])
//            ->orFilterWhere(['like', 'invt_main.invt_brand', $this->searchstring])
//            ->orFilterWhere(['like', 'invt_main.invt_detail', $this->searchstring]);
//        echo $query->createCommand()->getRawSql();exit();
        return $dataProvider;
    }
}
