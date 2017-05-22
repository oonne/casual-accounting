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

        // monthly data
        $monthlyExpenses = [];
        $monthlyExpensesQuery = (new Query())->select([
                'month' => "DATE_FORMAT(T0.expenses_date, '%Y-%m')",
                'money' => 'SUM(T0.expenses_money)'])
            ->from(['T0' => Expenses::tableName()])
            ->groupBy(["DATE_FORMAT(T0.expenses_date, '%Y-%m')"]);
        $monthlyExpensesResult = $monthlyExpensesQuery->createCommand()->queryAll();

        $monthlyIncome = [];
        $monthlyIncomeQuery = (new Query())->select([
                'month' => "DATE_FORMAT(T0.income_date, '%Y-%m')",
                'money' => 'SUM(T0.income_money)'])
            ->from(['T0' => Income::tableName()])
            ->groupBy(["DATE_FORMAT(T0.income_date, '%Y-%m')"]);
        $monthlyIncomeResult = $monthlyIncomeQuery->createCommand()->queryAll();

        $month = $this->_getMonth($monthlyExpensesResult, $monthlyIncomeResult);
        foreach ($month as $m) {
            $expenses = '0.00';
            foreach ($monthlyExpensesResult as $expensesResult) {
                if ($expensesResult['month'] == $m) {
                    $expenses = $expensesResult['money'];
                }
            }
            $monthlyExpenses[] = $expenses;

            $income = '0.00';
            foreach ($monthlyIncomeResult as $incomeResult) {
                if ($incomeResult['month'] == $m) {
                    $income = $incomeResult['money'];
                }
            }
            $monthlyIncome[] = $income;
        }

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
            'month' => $month,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpenses' => $monthlyExpenses,
            'expensesCategory' => $expensesCategory,
            'incomeHandler' => $incomeHandler,
        ]);
    }

    
    protected function _getMonth($expensesResult, $incomeResult)
    {
        $mergeMonth = array_merge($expensesResult, $incomeResult);
        $uniqueMonth = [];
        foreach ($mergeMonth as $k => $v) {
            if(in_array($v['month'], $uniqueMonth)){
                unset($mergeMonth[$k]);
            }else{
                $uniqueMonth[] = $v['month'];
            }
        }
        sort($uniqueMonth);

        return $uniqueMonth;
    }
}
