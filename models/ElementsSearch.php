<?php

namespace greeschenko\contentelements\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ElementsSearch represents the model behind the search form of `greeschenko\contentelements\models\Elements`.
 */
class ElementsSearch extends Elements
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'parent', 'created_at', 'updated_at', 'type', 'status'], 'integer'],
            [['title', 'urld', 'preview', 'content', 'tags', 'meta_title', 'meta_descr', 'meta_keys', 'atachments'], 'safe'],
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
        $query = Elements::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'parent' => $this->parent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'urld', $this->urld])
            ->andFilterWhere(['like', 'preview', $this->preview])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_descr', $this->meta_descr])
            ->andFilterWhere(['like', 'meta_keys', $this->meta_keys])
            ->andFilterWhere(['like', 'atachments', $this->atachments]);

        return $dataProvider;
    }
}
