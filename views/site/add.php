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

            <div class="row">
                <div class="col-lg-5">

                    <?php $form = ActiveForm::begin([
                        'id' => 'train-form',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                            'inputOptions' => ['class' => 'col-lg-3 form-control'],
                            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                        ],
                    ]); ?>

                    <?= $form->field($model, 'number')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'train') ?>

                     <?= $form->field($model, 'station') ?>

                    <?= $form->field($model, 'arrive') ?>

                    <?= $form->field($model, 'departure') ?>

                    <div class="form-group">
                        <div>
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>

    </div>
</div>

