<?php

use yii\helpers\Html;
use daixianceng\echarts\ECharts;

$this->title = '统计图表';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<div class="row">
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

<div class="row">
    <div class="text-center col-md-6">
        <h3>消费统计</h3>
        <?= ECharts::widget([
            'responsive' => true,
            'options' => [
                'style' => 'height: 400px;'
            ],
            'pluginOptions' => [
                'option' => [
                    'tooltip' => [
                        'trigger' => 'item',
                        'formatter' => '{a} <br/>{b}: {c} ({d}%)'
                    ],
                    'series' => [
                        'name' => '金额',
                        'type' => 'pie',
                        'radius' => ['66%', '80%'],
                        'data' => $expensesCategory
                    ]
                ]
            ]
        ]) ?>
    </div>
    <div class="text-center col-md-6">
        <h3>存钱统计</h3>
        <?= ECharts::widget([
            'responsive' => true,
            'options' => [
                'style' => 'height: 400px;'
            ],
            'pluginOptions' => [
                'option' => [
                    'tooltip' => [
                        'trigger' => 'item',
                        'formatter' => '{a} <br/>{b}: {c} ({d}%)'
                    ],
                    'series' => [
                        'name' => '金额',
                        'type' => 'pie',
                        'radius' => ['66%', '80%'],
                        'data' => $incomeHandler
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>
