<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\db\Query;
use common\models\Expenses;
use common\models\Income;
use common\models\Category;
use common\models\Handler;

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
        $expensesCategory = [];
        $expensesCategoryQuery = (new Query())->select([
                'category' => 'T0.expenses_category',
                'value' => 'SUM(T0.expenses_money)'])
            ->from(['T0' => Expenses::tableName()])
            ->groupBy(['T0.expenses_category']);
        $expensesCategoryResult = $expensesCategoryQuery->createCommand()->queryAll();

        $categoryList = Category::getCategoryList();
        foreach ($categoryList as $category) {
            foreach ($expensesCategoryResult as $item)  
            {
                if($item['category'] == $category['id']){
                    array_push($expensesCategory, [
                        'name' => $category['category_name'],
                        'value' => $item['value']
                    ]);
                }
            }
        }

        $incomeHandler = [];
        $incomeHandlerQuery = (new Query())->select([
                'handler' => 'T0.income_handler',
                'value' => 'SUM(T0.income_money)'])
            ->from(['T0' => Income::tableName()])
            ->groupBy(['T0.income_handler']);
        $incomeHandlerResult = $incomeHandlerQuery->createCommand()->queryAll();

        $handlerList = Handler::getHandlerList();
        foreach ($handlerList as $handler) {
            foreach ($incomeHandlerResult as $item)  
            {
                if($item['handler'] == $handler['id']){
                    array_push($incomeHandler, [
                        'name' => $handler['handler_name'],
                        'value' => $item['value']
                    ]);
                }
            }
        }

        return $this->render('index', [
            'incomeTotal' => $incomeTotal,
            'expensesTotal' => $expensesTotal,
            'expensesCategory' => $expensesCategory,
            'incomeHandler' => $incomeHandler,
        ]);
    }

    
}
