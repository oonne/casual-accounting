<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\db\Query;
use common\models\Expenses;
use common\models\Income;

class ChartController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        // sum data
        $incomeQuery = Income::find()
                    ->select(['summary' => 'SUM(income_money)'])
                    ->from([Income::tableName()]);
        $incomeTotal = ($incomeQuery->createCommand()->queryOne())['summary'];
        
        $expensesQuery = Expenses::find()
                    ->select(['summary' => 'SUM(expenses_money)'])
                    ->from([Expenses::tableName()]);
        $expensesTotal = ($expensesQuery->createCommand()->queryOne())['summary'];

        // proportion data
        // TODO

        return $this->render('index', [
            'incomeTotal' => $incomeTotal,
            'expensesTotal' => $expensesTotal,
        ]);
    }

    
}
