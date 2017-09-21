<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>
/**
* @author Harry Tang <harry@powerkernel.com>
* @link https://powerkernel.com
* @copyright Copyright (c) <?= date('Y') ?> Modern Kernel
*/

namespace <?= $generator->ns ?>;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{


    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;


    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
         self::STATUS_ACTIVE => <?= $generator->enableI18N?"Yii::\$app->getModule('$generator->messageCategory')->t('Active')":"'Active'" ?>,
         self::STATUS_INACTIVE => <?= $generator->enableI18N?"Yii::\$app->getModule('$generator->messageCategory')->t('Inactive')":"'Inactive'" ?>,
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
        $status=$this->status;
        $list=self::getStatusOption();
        if(!empty($status) && in_array($status, array_keys($list))){
            return $list[$status];
        }
        return Yii::$app->getModule('<?= $generator->messageCategory ?>')->t('Unknown');
    }

    /**
     * get status color text
     * @return string
     */
    public function getStatusColorText(){
        $status = $this->status;
        $list = self::getStatusOption();

        $color='default';
        if($status==self::STATUS_ACTIVE){
            $color='primary';
        }
        if($status==self::STATUS_INACTIVE){
            $color='danger';
        }

        if (!empty($status) && in_array($status, array_keys($list))) {
            return '<span class="label label-'.$color.'">'.$list[$status].'</span>';
        }
        return '<span class="label label-'.$color.'">'.Yii::$app->getModule('<?= $generator->messageCategory ?>')->t('Unknown').'</span>';
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => Yii::\$app->getModule('$generator->messageCategory')->t('$label'),\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
