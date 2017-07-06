<?php

namespace frontend\controllers;

use Yii;
use common\filters\auth\HeaderParamAuth;
use yii\data\ActiveDataProvider;
use common\models\Expenses;

class ExpensesController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HeaderParamAuth::className(),
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['get'],
        ];
    }

    public function actionIndex()
    {
        $query = Expenses::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['expenses_date' => SORT_DESC, 'updated_at' => SORT_DESC]]
        ]);

        $data = [];
        foreach ($dataProvider->getModels() as $model) {
            array_push($data, $model);
        }

        return [
            'Ret' => 0,
            'Data' => $data
        ];
    }

}