<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Поезда';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="body-content">

        <div class="site-login">

            <div class="row" id="trainSchedule">
                <div id="currentTime"><?= Yii::$app->formatter->asTime('now', 'php:H:i') ?></div>
                <div class="schedule_tables">
                    <table class="table col-lg-4">
                        <caption>Сейчас на станциях</caption>
                        <? 
                        if (!empty($items)) {
                            foreach ($items as $item) { 
                                $class = "";
                                if ($item->isArrive) {
                                    $class = "success";
                                } elseif($item->isDeparture) {
                                    $class="departure";
                                }
                                    ?>
                                <tr <?= $class ? 'class="' . $class . '"' : '' ?>>
                                    <td><?= Html::encode($item->station->name) ?></td>
                                    <td><?= Html::encode($item->train->number) ?></td>
                                    <td><?= Html::encode($item->train->name) ?></td>
                                    <td><?= Yii::$app->formatter->asTime(Html::encode($item->arrive), 'php:H:i') ?></td>
                                    <td><?= Yii::$app->formatter->asTime(Html::encode($item->departure), 'php:H:i') ?></td>
                                </tr>
                            <? } 
                        } else {
                            ?>
                            <tbody><tr><td colspan="5">Нет поездов</td></tr></tbody>
                            <?php
                        }
                        ?>
                    </table>

                    <table class="table col-lg-4">
                        <caption>Отправления</caption>
                        <? 
                        if (!empty($departures)) {
                            foreach ($departures as $item) { 
                                ?>
                                <tr>
                                    <td><?= Html::encode($item->station->name) ?></td>
                                    <td><?= Html::encode($item->train->number) ?></td>
                                    <td><?= Html::encode($item->train->name) ?></td>
                                    <td><?= Yii::$app->formatter->asTime(Html::encode($item->departure), 'php:H:i') ?></td>
                                </tr>
                                <? 
                            }
                        } else {
                            ?>
                            <tbody><tr><td colspan="4">Нет поездов</td></tr></tbody>
                            <?php
                        }
                        ?>
                    </table>

                    <table class="table col-lg-4">
                        <caption>Прибытия</caption>
                        <? 
                        if (!empty($arrives)) {
                            foreach ($arrives as $item) { 
                                    ?>
                                <tr>
                                    <td><?= Html::encode($item->station->name) ?></td>
                                    <td><?= Html::encode($item->train->number) ?></td>
                                    <td><?= Html::encode($item->train->name) ?></td>
                                    <td><?= Yii::$app->formatter->asTime(Html::encode($item->arrive), 'php:H:i') ?></td>
                                </tr>
                            <? 
                            } 
                        } else {
                            ?>
                           <tbody><tr><td colspan="4">Нет поездов</td></tr></tbody>
                            <?php
                        }
                        ?>
                    </table>
                </div>

                <div>
                    <table class="table col-lg-12">
                        <caption>Самые нагруженные станции</caption>
                        <? 
                        if (!empty($countStations)) {
                            foreach ($countStations as $item) { 
                                    ?>
                                <tr>
                                    <td><?= Html::encode($item['name']) ?></td>
                                    <td><?= Html::encode($item['count']) ?></td>
                                </tr>
                            <? } 
                        } else {
                            ?>
                            <tbody><tr><td colspan="5">Нет поездов</td></tr></tbody>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>