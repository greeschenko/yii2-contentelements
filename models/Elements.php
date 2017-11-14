<?php

namespace greeschenko\contentelements\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use greeschenko\file\models\Attachments;

/**
 * This is the model class for table "{{%elements}}".
 *
 * @property int $id
 * @property string $title
 * @property string $urld
 * @property int $user_id
 * @property int $parent
 * @property string $preview
 * @property string $content
 * @property string $tags
 * @property string $meta_title
 * @property string $meta_descr
 * @property string $meta_keys
 * @property string $atachments
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $status
 */
class Elements extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    const TYPE_STATIC = 1;
    const TYPE_DINAMIC = 2;

    public $statuslist;
    public $typelist;
    private $module;

    public function init()
    {
        parent::init();

        $this->statuslist = [
            self::STATUS_DRAFT => Yii::t('cont_elem', 'Draft'),
            self::STATUS_PUBLISHED => Yii::t('cont_elem', 'Published'),
            self::STATUS_ARCHIVED => Yii::t('cont_elem', 'Archived'),
        ];

        $this->typelist = [
            self::TYPE_STATIC => Yii::t('cont_elem', 'Static'),
            self::TYPE_DINAMIC => Yii::t('cont_elem', 'Dynamic'),
        ];

        $this->module = Yii::$app->getModule('pages');
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%elements}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->identity->id;
        $this->urld = str_replace(' ', '-', $this->urld);

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],

            [['urld'], 'required'],
            [['urld'], 'unique'],
            ['urld', 'match', 'pattern' => '/^[a-z0-9-]+$/',
                'message' => Yii::t('cont_elem', 'urld can only contain characters a-z, numbers 0-9 and "-".'), ],
            [['urld'], 'string', 'max' => 64, 'min' => '3'],

            [['user_id', 'parent', 'created_at', 'updated_at', 'type', 'status'], 'integer'],
            [['content'], 'string'],
            [['title', 'tags', 'meta_title', 'meta_descr', 'meta_keys', 'atachments', 'preview'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cont_elem', 'ID'),
            'title' => Yii::t('cont_elem', 'Title'),
            'urld' => Yii::t('cont_elem', 'Urld'),
            'user_id' => Yii::t('cont_elem', 'Creator'),
            'parent' => Yii::t('cont_elem', 'Parent'),
            'preview' => Yii::t('cont_elem', 'Preview'),
            'content' => Yii::t('cont_elem', 'Content'),
            'tags' => Yii::t('cont_elem', 'Tags'),
            'meta_title' => Yii::t('cont_elem', 'Meta Title'),
            'meta_descr' => Yii::t('cont_elem', 'Meta Descr'),
            'meta_keys' => Yii::t('cont_elem', 'Meta Keys'),
            'atachments' => Yii::t('cont_elem', 'Atachments'),
            'created_at' => Yii::t('cont_elem', 'Created At'),
            'updated_at' => Yii::t('cont_elem', 'Updated At'),
            'type' => Yii::t('cont_elem', 'Type'),
            'status' => Yii::t('cont_elem', 'Status'),
        ];
    }

    public function getParentList($parent = 0, $lvl = 0)
    {
        $res = [];
        if ($parent == 0) {
            $res = ['0' => Yii::t('cont_elem', 'Root Page')];
        }
        $data = self::find()
            ->select('id, title')
            ->where(['parent' => $parent])
            ->andWhere(['status' => self::STATUS_PUBLISHED])
            ->andWhere(['type' => self::TYPE_DINAMIC])
            ->all();

        if (count($data) > 0) {
            foreach ($data as $one) {
                $lvlstr = str_repeat('--', $lvl);
                $res[$one->id] = $lvlstr.' '.$one->title;
                $res = ArrayHelper::merge($res, $this->getParentList($one->id, $lvl + 1));
            }
        }

        return $res;
    }

    public function getUser()
    {
        return $this->hasOne($this->module->userclass, ['id' => 'user_id']);
    }

    public function getParentData()
    {
        return $this->hasOne(self::classname(), ['id' => 'parent']);
    }

    public function getAdminsList()
    {
        $usermodel = $this->module->userclass;
        $data = $usermodel::find()
            ->select('id, username')
            ->andWhere(['role' => 'admin'])
            ->all();

        return ArrayHelper::map($data, 'id', 'username');
    }

    public function genFullPathArray()
    {
        $res[] = [
            'title' => $this->title,
            'urld' => $this->urld,
        ];
        $data = $this->parentData;

        while (isset($data)) {
            $res[] = [
                'title' => $data->title,
                'urld' => $data->urld,
            ];
            $data = $data->parentData;
        }

        $res = array_reverse($res);

        return $res;
    }

    public function genBreacrumbs()
    {
        $res[] = [
            'label' => $this->title,
        ];
        $data = $this->parentData;

        while (isset($data)) {
            $res[] = [
                'label' => $data->title,
                'url' => $data->genUrl(),
            ];
            $data = $data->parentData;
        }

        $res = array_reverse($res);

        return $res;
    }

    public function genUrl($absolute = false)
    {
        $res = [];
        $root = '/';
        $data = $this->genFullPathArray();
        foreach ($data as $one) {
            $res[] = $one['urld'];
        }

        if ($absolute) {
            $root = Url::toRoute('/', 'https');
        }

        $res = $root.implode('/', $res).'.html';

        return $res;
    }

    public function getTumb()
    {
        $data = Attachments::find()
            ->joinWith('file')
            ->where(['group' => $this->atachments])
            ->andWhere(['files.type' => '1'])
            ->one();

        if ($data != null) {
            return $data->file->getData();
        }

        return null;
    }
}
