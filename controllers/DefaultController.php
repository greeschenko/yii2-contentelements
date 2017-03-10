<?php

namespace greeschenko\contentelements\controllers;

use Yii;
use yii\web\Controller;
use greeschenko\contentelements\models\Elements;
use greeschenko\contentelements\models\ElementsSearch;
use yii\helpers\ArrayHelper;

class DefaultController extends Controller
{
    public function actionIndex($req = false)
    {
        if ($req) {
            $list = explode('/', $req);
            $urld = end($list);
            $model = Elements::find()->where(['urld' => $urld])->one();
            if ($model != null) {
                if ($model->type == Elements::TYPE_DINAMIC) {
                    $searchModel = new ElementsSearch();
                    $t = ArrayHelper::merge(['ElementsSearch' => ['parent' => $model->id]], Yii::$app->request->queryParams);
                    $dataProvider = $searchModel->search($t);

                    return $this->render('dynamic', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                } else {
                    return $this->render('static', [
                        'model' => $model,
                    ]);
                }
            } else {
                throw new \yii\web\HttpException(404, Yii::t('cont_elem', 'Sorry, page not found.'));
            }
        } else {
            $searchModel = new ElementsSearch();
            $t = ArrayHelper::merge(['ElementsSearch' => ['parent' => 0]], Yii::$app->request->queryParams);
            $dataProvider = $searchModel->search($t);

            return $this->render('dynamic', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
}
