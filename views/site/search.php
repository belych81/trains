<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Поиск';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="body-content">

        <div class="site-login">

            <div class="row">
                <div class="col-lg-12">

                    <?php $form = ActiveForm::begin([
                        'id' => 'search-form',
                        'method' => 'post',
                        'action' => ['site/search'],
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                            'inputOptions' => ['class' => 'col-lg-3 form-control'],
                            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                        ],
                    ]); ?>

                    <?= $form->field($model, 'station')->textInput(['value' => $search]) ?>

                    <div class="form-group">
                        <div>
                            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>


                    <table class="table">
                        <caption>Расписание станции</caption>
                        <? 
                        if (!empty($data)) {
                            foreach ($data as $station) { 
                                foreach ($station as $item) { 
                                    ?>
                                    <tr>
                                        <td><?= Html::encode($item->station->name) ?></td>
                                        <td><?= Html::encode($item->train->number) ?></td>
                                        <td><?= Html::encode($item->train->name) ?></td>
                                        <td><?= Yii::$app->formatter->asTime(Html::encode($item->arrive), 'php:H:i') ?></td>
                                        <td><?= Yii::$app->formatter->asTime(Html::encode($item->departure), 'php:H:i') ?></td>
                                    </tr>
                                <? 
                                }
                            } 
                        } else {
                            ?>
                           <tbody><tr><td colspan="4">Нет поездов</td></tr></tbody>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>