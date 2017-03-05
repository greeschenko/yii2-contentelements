<?php

namespace greeschenko\contentelements\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

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
    const STATUS_DRAFT=1;
    const STATUS_PUBLISHED=2;
    const STATUS_ARCHIVED=3;

    const TYPE_STATIC=1;
    const TYPE_DINAMIC=2;

    public $statuslist;
    public $typelist;

    public function init()
    {
        parent::init();

        $this->statuslist = [
            self::STATUS_DRAFT => Yii::t('cont_elem', 'Draft'),
            self::STATUS_PUBLISHED => Yii::t('cont_elem', 'Published'),
            self::STATUS_ARCHIVED =>  Yii::t('cont_elem', 'Archived'),
        ];

        $this->typelist = [
            self::TYPE_STATIC=>Yii::t('cont_elem', 'Static'),
            self::TYPE_DINAMIC=>Yii::t('cont_elem', 'Dinamic'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%elements}}';
    }

    /**
     * @inheritdoc
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

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['urld'], 'required'],
            [['urld'], 'unique'],
            [['user_id', 'parent', 'created_at', 'updated_at', 'type', 'status'], 'integer'],
            [['preview', 'content'], 'string'],
            [['title', 'urld', 'tags', 'meta_title', 'meta_descr', 'meta_keys', 'atachments'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'urld' => Yii::t('app', 'Urld'),
            'user_id' => Yii::t('app', 'User ID'),
            'parent' => Yii::t('app', 'Parent'),
            'preview' => Yii::t('app', 'Preview'),
            'content' => Yii::t('app', 'Content'),
            'tags' => Yii::t('app', 'Tags'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_descr' => Yii::t('app', 'Meta Descr'),
            'meta_keys' => Yii::t('app', 'Meta Keys'),
            'atachments' => Yii::t('app', 'Atachments'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getParentList($parent=0,$lvl=0)
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
                $lvlstr = str_repeat('--',$lvl);
                $res[$one->id] = $lvlstr.' '.$one->title;
                $res = ArrayHelper::merge($res,$this->getParentList($one->id,$lvl+1));
            }
        }

        return $res;
    }
}
