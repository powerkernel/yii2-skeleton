<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace common\models;

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
            [['id', 'updated_at'], 'integer'],
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
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            //'pagination'=>['pageSize'=>20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'task', $this->task])
            ->andFilterWhere(['like', 'result', $this->result]);

        if(!empty($this->created_at)){
            $query->andFilterWhere([
                'DATE(CONVERT_TZ(FROM_UNIXTIME(`created_at`), :UTC, :ATZ))' => $this->created_at,
            ])->params([
                ':UTC'=>'+00:00',
                ':ATZ'=>date('P')
            ]);
        }


        return $dataProvider;
    }
}