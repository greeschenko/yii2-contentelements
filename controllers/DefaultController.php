<?php

namespace greeschenko\contentelements\controllers;

use Yii;
use yii\web\Controller;
use greeschenko\contentelements\models\Elements;

class DefaultController extends Controller
{
    public function actionIndex($req=false)
    {
        if ($req) {
            $list = explode('/',$req);
            $urld = end($list);
            $model = Elements::find()->where(['urld' => $urld])->one();
            if ($model != null) {
                if ($model->type == Elements::TYPE_DINAMIC) {
                    $this->renderDynamic($model);
                } else {
                    $this->renderStatic($model);
                }
            } else {
                throw new \yii\web\HttpException(404 ,Yii::t('cont_elem', 'Sorry, page not found.'));
            }
        } else {
            $this->renderRoot();
        }
    }

    public function renderRoot()
    {
        return $this->render('root');
    }

    public function renderDynamic($model)
    {
        return $this->render('dynamic',['model' => $model]);
    }

    public function renderStatic($model)
    {
        return $this->render('static',['model' => $model]);
    }
}
