<?php

namespace greeschenko\contentelements\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
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

    /**
     * create base sitemap.
     */
    public function actionGenBaseSitemap()
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $data = [
            Url::toRoute('/', 'https'),
        ];

        $pages = Elements::find()
            ->where(['status' => 2])
            ->all();

        foreach ($pages as $page) {
            $data[] = $page->genUrl(true);
        }

        foreach ($data as $one) {
            $url = $dom->createElement('url');
            $item = [
               'loc' => $one,
            ];

            foreach ($item as $key => $value) {
                $elem = $dom->createElement($key);
                $elem->appendChild($dom->createTextNode($value));
                $url->appendChild($elem);
            }

            $urlset->appendChild($url);
        }

        $dom->appendChild($urlset);

        $dom->save('base.sitemap.xml');

        return $this->gzCompressFile('base.sitemap.xml');
    }

    public function gzCompressFile($source, $level = 9)
    {
        $dest = $source.'.gz';
        $mode = 'wb'.$level;
        $error = false;
        if ($fp_out = gzopen($dest, $mode)) {
            if ($fp_in = fopen($source, 'rb')) {
                while (!feof($fp_in)) {
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                }
                fclose($fp_in);
            } else {
                $error = true;
            }
            gzclose($fp_out);
        } else {
            $error = true;
        }
        if ($error) {
            return false;
        } else {
            return $dest;
        }
    }
}
