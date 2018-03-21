<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */


namespace common\models;

use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TaskLogSearch represents the model behind the search form about `common\models\TaskLog`.
 */
class TaskLogSearch extends TaskLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            Yii::$app->params['mongodb']['taskLog'] ? [['_id'], 'safe'] : [['id'], 'integer'],
            [['updated_at'], 'integer'],
            [['task', 'result', 'created_at'], 'safe'],
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
        $query = TaskLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            //'pagination'=>['pageSize'=>20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id]);

        // grid filtering conditions
        $query->andFilterWhere([
            'updated_at' => $this->updated_at,
        ]);


        $query->andFilterWhere(['like', 'task', $this->task])
            ->andFilterWhere(['like', 'result', $this->result]);

        if (!empty($this->created_at)) {

            $query->andFilterWhere([
                'created_at' => ['$gte' => new UTCDateTime(strtotime($this->created_at) * 1000)],
            ])->andFilterWhere([
                'created_at' => ['$lt' => new UTCDateTime((strtotime($this->created_at) + 86400) * 1000)],
            ]);

        }


        return $dataProvider;
    }
}
