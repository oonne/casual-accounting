<?php

namespace frontend\controllers;

use Yii;
use common\filters\auth\HeaderParamAuth;
use yii\data\ActiveDataProvider;
use common\models\Income;

class IncomeController extends Controller
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
        $query = Income::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['income_date' => SORT_DESC, 'updated_at' => SORT_DESC]]
        ]);

        $data = $dataProvider->getModels();
        $meta = [
            'totalCount' => $dataProvider->pagination->totalCount,
            'pageCount' => $dataProvider->pagination->getPageCount(),
            'currentPage' => $dataProvider->pagination->getPage() + 1,
            'perPage' => $dataProvider->pagination->getPageSize(),
        ];

        return [
            'Ret' => 0,
            'Data' => $data,
            'Meta' => $meta,
        ];
    }

}