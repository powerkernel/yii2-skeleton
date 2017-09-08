<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AccountSearch represents the model behind the search form about `common\models\Account`.
 */
class AccountSearch extends Account
{

    //public $created_at_picker;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname_changed', 'email_verified', 'role', 'status'], 'safe'],
            [['seo_name', 'fullname', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'new_email', 'change_email_token', 'language', 'timezone'], 'safe'],
            [['created_at'], 'safe']
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
        $query = Account::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            'fullname_changed' => $this->fullname_changed,
            'email_verified' => $this->email_verified,
            'role' => $this->role,
            'status' => $this->status,
            //'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'seo_name', $this->seo_name])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'new_email', $this->new_email])
            ->andFilterWhere(['like', 'change_email_token', $this->change_email_token])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'timezone', $this->timezone]);

//        if(!empty($this->created_at)){
//            $query->andFilterWhere([
//                'DATE(CONVERT_TZ(FROM_UNIXTIME(`created_at`), :UTC, :ATZ))' => $this->created_at,
//            ])->params([
//                ':UTC'=>'+00:00',
//                ':ATZ'=>date('P')
//            ]);
//        }

        return $dataProvider;
    }
}
