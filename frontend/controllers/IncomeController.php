<?php

namespace frontend\controllers;

use Yii;
use common\filters\auth\HeaderParamAuth;
use yii\data\ActiveDataProvider;
use common\models\Income;
use common\models\Handler;

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
            'add' => ['post'],
            'update' => ['post'],
            'delete' => ['post'],
        ];
    }

    public function actionAdd()
    {
        //TODO
    }

    public function actionIndex()
    {
        $query = Income::find()
            ->select(['id', 'income_item', 'income_date', 'income_money', 'income_handler', 'income_remark']);

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

        // Handler
        $extra = [];

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
        $income = Yii::$app->request->post();
        $id = $income['id'];
        $model = Income::findOne($id);

        if (!$model) {
            return [
                'Ret' => 1,
                'Data' => [
                    'errors' => ['查无记录']
                ]
            ];
        }

        if ($model->load($income, '') && $model->validate()) {
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