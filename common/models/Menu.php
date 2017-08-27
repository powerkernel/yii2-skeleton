<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\models;

use common\Core;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for Menu.
 *
 * @property integer|string $id
 * @property integer|string $id_parent
 * @property string $label
 * @property string $active_route
 * @property string $url
 * @property string $class
 * @property string $position
 * @property string $order
 * @property integer $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 * @property integer|string $created_by
 * @property integer|string $updated_by
 *
 * @property Menu $parent
 * @property Account $createdBy
 * @property Account $updatedBy
 */
class Menu extends MenuBase
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;

    private $_route;
    private $_path;
    private $_query;

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $url = $this->url;
        $this->_path = parse_url($url, PHP_URL_PATH);
        $this->_query = parse_url($url, PHP_URL_QUERY);
    }

    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get status text
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->status;
        $list = self::getStatusOption();
        if (!empty($status) && in_array($status, array_keys($list))) {
            return $list[$status];
        }
        return Yii::t('app', 'Unknown');
    }

    /**
     * color status text
     * @return mixed|string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        if ($status == self::STATUS_ACTIVE) {
            return '<span class="label label-success">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_INACTIVE) {
            return '<span class="label label-default">' . $this->statusText . '</span>';
        }
        return $this->statusText;
    }

    /**
     * get menu list
     * @return array
     */
    public static function getMenuPosition()
    {
        $option = [
            'header' => Yii::t('app', 'Header'),
            'footer' => Yii::t('app', 'Footer'),
        ];
        return $option;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_parent', 'class', 'active_route'], 'default', 'value' => null],
            [['label', 'url', 'position'], 'required'],

            [['order', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],

            [['label', 'active_route', 'url', 'class', 'position'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_parent' => Yii::t('app', 'Parent'),
            'label' => Yii::t('app', 'Label'),
            'active_route' => Yii::t('app', 'Active Route'),
            'url' => Yii::t('app', 'Url'),
            'position' => Yii::t('app', 'Position'),
            'order' => Yii::t('app', 'Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getParent()
    {
        if (is_a($this, '\yii\mongodb\ActiveRecord')) {
            return $this->hasOne(Menu::className(), ['_id' => 'id_parent']);
        } else {
            return $this->hasOne(Menu::className(), ['id' => 'id_parent']);
        }
    }

    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!empty(Yii::$app->user)) {
            if (empty($this->created_by)) {
                $this->created_by = Yii::$app->user->id;
            }
            $this->updated_by = Yii::$app->user->id;
        }
        if ($insert) {
            $order = Menu::find()->count();
            $this->order = $order;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * get menu active status
     * @return bool|null
     */
    public function getActiveStatus()
    {
        $path = $this->_path;
        if (!empty($this->active_route)) {
            $path = $this->active_route;
        }

        if (!empty($path)) {
            $count = substr_count($path, '/');
            $m = null;
            $c = null;
            $a = null;
            if ($count == 1) {
                list($c, $a) = explode('/', $path);
            } elseif ($count == 2) {
                list($m, $c, $a) = explode('/', $path);
            } else {
                return null;
            }
            $params = null;

            if (empty($this->active_route) && !empty($this->_query)) {
                parse_str($this->_query, $params);
            }
            return Core::checkMCA(
                explode('|', $m),
                explode('|', $c),
                explode('|', $a),
                $params
            );
        }
        return false;
    }


    /**
     * get menu route
     * @return array
     */
    public function getRoute()
    {
        if (empty($this->_route)) {
            $params = [];
            if (!empty($this->_query)) {
                parse_str($this->_query, $params);
            }
            $route = array_merge([$this->_path], $params);
            $this->_route = $route;
        }
        return $this->_route;
    }


    /**
     * @param integer $e
     * @return array
     */
    public static function getRootMenu($e = null)
    {
        if (empty($e)) {
            $menus = Menu::find()->where(['id_parent' => null])->all();
        } else {
            if (Yii::$app->params['mongodb']['menu']) {
                $menus = Menu::find()->where(['id_parent' => null])->andFilterCompare('_id', $e, '!=')->all();
            } else {
                $menus = Menu::find()->where(['id_parent' => null])->andFilterCompare('id', $e, '!=')->all();
            }
        }

        if (Yii::$app->params['mongodb']['menu']) {
            return ArrayHelper::map($menus, function ($model) {
                return (string)$model->_id;
            }, 'label');
        } else {
            return ArrayHelper::map($menus, 'id', 'label');
        }

    }

    /**
     * get sub menu
     * @return array
     */
    public function generateSubNavItem()
    {
        $items = [];
        $menus = Menu::find()->where(['status' => Menu::STATUS_ACTIVE, 'id_parent' => $this->id])->orderBy('order')->all();
        foreach ($menus as $menu) {
            $items[] = [
                'active' => $menu->getActiveStatus(),
                'label' => Yii::t('main', $menu->label),
                'url' => preg_match('/\/\//', $menu->url) ? $menu->url : $menu->route,
                'linkOptions' => ['class' => $menu->class],
            ];
        }
        return $items;
    }
}
