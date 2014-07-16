<?php

namespace bariew\eventModule\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use bariew\eventModule\models\Item;

/**
 * ItemSearch represents the model behind the search form about `bariew\eventModule\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'safe'],
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
        $query = Item::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'trigger_class', $this->trigger_class])
            ->andFilterWhere(['like', 'trigger_event', $this->trigger_event])
            ->andFilterWhere(['like', 'handler_class', $this->handler_class])
            ->andFilterWhere(['like', 'handler_method', $this->handler_method]);

        return $dataProvider;
    }
}
