<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use app\models\SignupForm;
use app\models\TrainForm;
use app\models\Items;
use app\models\Station;
use app\models\Train;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'add'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['add'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Items();
        
        $items = $model->getFullItems();

        $departures = $model->getDepartures();
        $arrives = $model->getArrives();
        $cntArrives = 0;
        $cntDepartures = 0;

        if (!$arrives || ($cntArrives = count($arrives)) < 5) {
            $newLimit = 5 - $cntArrives;
            $newArrives = $model->getArrives(false, $newLimit);
            if ($newArrives) {
                $arrives = $arrives ? array_merge($arrives, $newArrives) : $newArrives;
            }
        }

        if (!$departures || ($cntDepartures = count($departures)) < 5) {
            $newLimit = 5 - $cntDepartures;
            $newDepartures = $model->getDepartures(false, $newLimit);
            if ($newDepartures) {
                $departures = $departures ? array_merge($departures, $newDepartures) : $newDepartures;
            }
        }

        $countStations = $model->getCountStations();

        if (Yii::$app->request->isAjax) {
            $this->layout = false;
        }

        return $this->render('index', [
            'items' => $items,
            'departures' => $departures,
            'arrives' => $arrives,
            'countStations' => $countStations,
        ]);
    }

    public function actionSearch()
    {
        $data = [];

        $searchModel = new Items();
        
        if ($post = Yii::$app->request->post()) {
            $data = $searchModel->search($post);
            $search = $post['Items']['station'];
        }

        return $this->render('search', [
             'model' => $searchModel,
             'data' => $data,
             'search' => $search
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // $auth = Yii::$app->authManager;
            // $authorRole = $auth->getRole('admin');
            // $auth->assign($authorRole, Yii::$app->user->id);
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAdd()
    {
        if (Yii::$app->user->can('add')) {
            $model = new TrainForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
                Yii::$app->session->setFlash('Добавлено');

                if (!$model->add()) {
                    //throw new \Exception('Failed to save');
                }
            }

            return $this->render('add', [
                'model' => $model,
            ]);
        }
        return $this->goHome();
    }
	
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();
        
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $user = new User();
            $user->username = $model->username;
            $user->password = \Yii::$app->security->generatePasswordHash($model->password);
            
            if ($user->save()) {
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole('admin');
                $auth->assign($authorRole, $user->getId());

                return $this->goHome();
            }
        }

        return $this->render('signup', compact('model'));
    }
}
