<?php

namespace frontend\controllers;

use Yii;
use common\filters\auth\HeaderParamAuth;
use yii\data\ActiveDataProvider;
use common\models\Expenses;
use common\models\Category;
use common\models\Handler;

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
        $query = Expenses::find()
            ->select(['id', 'expenses_date', 'expenses_item', 'expenses_money', 'expenses_category', 'expenses_handler', 'expenses_remark']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['expenses_date' => SORT_DESC, 'updated_at' => SORT_DESC]]
        ]);

        $data = $dataProvider->getModels();
        $meta = [
            'totalCount' => $dataProvider->pagination->totalCount,
            'pageCount' => $dataProvider->pagination->getPageCount(),
            'currentPage' => $dataProvider->pagination->getPage() + 1,
            'perPage' => $dataProvider->pagination->getPageSize(),
        ];

        // Category & Handler
        $extra = [];
        
        $category = Category::find()
            ->select(['id', 'category_name'])
            ->all();
        $extra['category'] = $category;

        $handler = Handler::find()
            ->select(['id', 'handler_name'])
            ->all();
        $extra['handler'] = $handler;

        return [
            'Ret' => 0,
            'Data' => $data,
            'Meta' => $meta,
            'Extra' => $extra,
        ];
    }

    public function actionUpdate()
    {
        $expenses = Yii::$app->request->post();
        $id = $expenses['id'];
        $model = Expenses::findOne($id);

        if (!$model) {
            return [
                'Ret' => 1,
                'Data' => [
                    'errors' => ['查无记录']
                ]
            ];
        }

        if ($model->load($expenses, '') && $model->validate()) {
            $model->last_editor = Yii::$app->user->id;
            if ($model->save(false)) {
                return [
                    'Ret' => 0,
                    'Data' => '保存成功',
                ];
            } else {
                return [
                    'Ret' => 2,
                    'Data' => [
                        'errors' => ['保存失败']
                    ]
                ];
            }
        }

        return [
            'Ret' => 3,
            'Data' => [
                'errors' => ['更新失败']
            ]
        ];
    }

    public function actionDelete()
    {
        //TODO
    }

}