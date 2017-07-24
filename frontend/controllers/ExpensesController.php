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
        foreach ($dataProvider->getModels() as $expenses) {
            $expensesArr = $expenses->toArray(['id', 'expenses_date', 'expenses_item', 'expenses_money', 'expenses_category', 'expenses_handler', 'expenses_remark']);
            $expensesArr['category'] = $expenses->category->category_name;
            $expensesArr['handler'] = $expenses->handler->handler_name;
            array_push($data, $expensesArr);
        }
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