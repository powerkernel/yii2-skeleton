<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace common\models;

use common\Core;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlogSearch represents the model behind the search form about `common\models\Blog`.
 */
class BlogSearch extends Blog
{

    public $fullname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'views', 'status'], 'integer'],
            [['language', 'title', 'desc', 'content', 'tags', 'created_at', 'updated_at'], 'safe'],
            [['fullname'], 'safe']
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
        $query = Blog::find();
        $query->joinWith(['author']);



        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['fullname'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['{{%core_account}}.fullname' => SORT_ASC],
            'desc' => ['{{%core_account}}.fullname' => SORT_DESC],
        ];

        /* user's blog */
        if (Core::checkMCA(null, 'blog', 'manage')) {
            $query->andFilterWhere([
                '{{%core_blog}}.created_by' => Yii::$app->user->id,
            ]);
        }

        /* list all public blog */
        if (Core::checkMCA(null, 'blog', 'index')) {
            $query->andFilterWhere([
                '{{%core_blog}}.status' => Blog::STATUS_PUBLISHED,
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%core_blog}}.id' => $this->id,
            '{{%core_blog}}.created_by' => $this->created_by,
            '{{%core_blog}}.views' => $this->views,
            '{{%core_blog}}.status' => $this->status,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', '{{%core_blog}}.language', $this->language])
            ->andFilterWhere(['like', '{{%core_blog}}.title', $this->title])
            ->andFilterWhere(['like', '{{%core_account}}.fullname', $this->fullname])
            ->andFilterWhere(['like', '{{%core_blog}}.desc', $this->desc])
            ->andFilterWhere(['like', '{{%core_blog}}.content', $this->content])
            ->andFilterWhere(['like', '{{%core_blog}}.tags', $this->tags]);

        $query->andFilterWhere([
            'DATE(FROM_UNIXTIME(`{{%core_blog}}.updated_at`))' => $this->updated_at,
        ]);

        return $dataProvider;
    }


}
