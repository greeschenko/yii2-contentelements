<?php

namespace greeschenko\contentelements\controllers;

use Yii;
use greeschenko\contentelements\models\Elements;
use greeschenko\contentelements\models\ElementsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ElementsController implements the CRUD actions for Elements model.
 */
class ElementsController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return (!Yii::$app->user->isGuest
                                && Yii::$app->user->identity->role == 'admin');
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Elements models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ElementsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Elements model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Elements model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Elements();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Element successfully created');

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Elements model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Element successfully update');

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionCopy($id)
    {
        $model = $this->findModel($id);

        $clone = new Elements();
        $clone->attributes = $model->attributes;
        $clone->urld = strtolower($clone->urld);
        $clone->urld = str_replace(['.',' ','_'],'-',$clone->urld);
        $clone->urld = time().'-copy';
        $clone->title = $clone->title.'-copy';
        if ($clone->save()) {
            return $this->redirect(['update', 'id' => $clone->id]);
        } else {
            throw new \yii\web\HttpException(500 ,Yii::t('cont_elem', 'Element copy error.'));
        }
    }

    /**
     * Deletes an existing Elements model.
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
     * Finds the Elements model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Elements the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Elements::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
