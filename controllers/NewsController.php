<?php

namespace app\controllers;

use Yii;
use app\models\News;
use app\models\NewsSearch;
use app\models\Statistics;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 5;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNews()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('allNews', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $cookies = Yii::$app->request->cookies;
        $unique_clicks = 0;
        $clicks = 0;
        $model_statistics = Statistics::find()
            ->where(['news_id' => $id, 'country_code' => $this->get_visitors_country()])
            ->one();

        if (isset($model_statistics)) {
            $unique_clicks = $model_statistics->unique_clicks;
            $clicks = $model_statistics->clicks;
        } else {
            $model_statistics = new Statistics;
        }
        if (!$cookies->has('last_visit_news_' . $id)) {
            ++$unique_clicks;
            $cookie = new \yii\web\Cookie(array('name' => 'last_visit_news_' . $id, 'value' => 'true', 'expire' => time() + 86400 * 365));
            \Yii::$app->response->cookies->add($cookie);
        }

        $model_statistics->news_id = $id;
        $model_statistics->unique_clicks = $unique_clicks;
        $model_statistics->clicks = ++$clicks;
        $model_statistics->country_code = $this->get_visitors_country();
        $model_statistics->save();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function get_visitors_country()
    {
        include_once(dirname(__DIR__) . '/components/GeoIP/geoip.inc');
        $ip = $_SERVER['REMOTE_ADDR'];
        if ((strpos($ip, ":") === false)) {
            //ipv4
            $gi = geoip_open(dirname(__DIR__) . "/components/GeoIP/GeoIP.dat", GEOIP_STANDARD);
            $country = geoip_country_code_by_addr($gi, $ip);
        } else {
            //ipv6
            $gi = geoip_open(dirname(__DIR__) . "/components/GeoIP/GeoIPv6.dat", GEOIP_STANDARD);
            $country = geoip_country_code_by_addr_v6($gi, $ip);
        }
        return empty($country) ? 'UA' : $country;
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
