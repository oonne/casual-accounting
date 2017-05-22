<?php

use yii\helpers\Html;
use backend\widgets\Alert;

$this->title = '统计图表';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
    
    <div class="fms-summary col-md-12">
        <div class="fms-summary-item col-md-2 fms-summary-income">
            <div class="fms-summary-attr">总收入</div>
            <div class="fms-summary-num"><?= $incomeTotal ?></div>
        </div>
        <div class="fms-summary-item col-md-2 fms-summary-expenses">
            <div class="fms-summary-attr">总支出</div>
            <div class="fms-summary-num"><?= $expensesTotal ?></div>
        </div>
        <div class="fms-summary-item col-md-2 fms-summary-balance">
            <div class="fms-summary-attr">结余</div>
            <div class="fms-summary-num"><?= ($incomeTotal-$expensesTotal) ?></div>
        </div>
    </div>
</div>
