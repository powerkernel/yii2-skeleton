<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */


namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MessageSearch represents the model behind the search form about `common\models\mongodb\Message`.
 */
class MessageSearch extends Message
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'category', 'language', 'message', 'translation', 'is_translated'], 'safe'],
            //[['is_translated'], 'boolean'],
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
        $query = Message::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            //'pagination'=>['pageSize'=>20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['is_translated' => in_array($this->is_translated, [null, ''], true) ? null : (boolean)$this->is_translated]);

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'translation', $this->translation]);

        //if(!empty($this->created_at)){
        //    $query->andFilterWhere([
        //        'DATE(CONVERT_TZ(FROM_UNIXTIME(`created_at`), :UTC, :ATZ))' => $this->created_at,
        //    ])->params([
        //        ':UTC'=>'+00:00',
        //        ':ATZ'=>date('P')
        //    ]);
        //}

        return $dataProvider;
    }
}
