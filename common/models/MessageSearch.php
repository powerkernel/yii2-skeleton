<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Message;

/**
 * MessageSearch represents the model behind the search form about `common\models\Message`.
 */
class MessageSearch extends Message
{
    public $message;
    public $category;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_translated'], 'integer'],
            [['language', 'translation', 'message', 'category'], 'safe'],
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
        $query->joinWith(['source']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
            'sort' => ['defaultOrder' => ['is_translated' => SORT_ASC]]
        ]);

        $dataProvider->sort->attributes['message'] = [
            'asc' => ['{{%core_source_message}}.message' => SORT_ASC],
            'desc' => ['{{%core_source_message}}.message' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['category'] = [
            'asc' => ['{{%core_source_message}}.category' => SORT_ASC],
            'desc' => ['{{%core_source_message}}.category' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_translated' => $this->is_translated,
        ]);

        $query->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'translation', $this->translation])
            ->andFilterWhere(['like', '{{%core_source_message}}.message', $this->message])
            ->andFilterWhere(['like', '{{%core_source_message}}.category', $this->category]);
        ;

        //$query->andFilterWhere([
        //    'DATE(FROM_UNIXTIME(`created_at`))' => $this->created_at,
        //]);

        return $dataProvider;
    }
}
