<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) <?= date('Y') ?> Power Kernel
 */

namespace <?= $ns ?>;

use Yii;


/**
 * <?= $generator->moduleID ?> module definition class
 */
class <?= $className ?> extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        //\Yii::configure($this, require(__DIR__ . '/config.php'));
        $this->registerTranslations();
        $this->registerMailer();
    }

    /**
     * Config Mailer for the Module
     */
    public function registerMailer()
    {
        Yii::$app->mailer->setViewPath($this->basePath . '/mail');
        Yii::$app->mailer->htmlLayout='@common/mail/layouts/html';
        Yii::$app->mailer->textLayout='@common/mail/layouts/text';
    }

    /**
     * Register translation for the Module
     */
    public function registerTranslations()
    {
        $class='common\components\MongoDbMessageSource';
        Yii::$app->i18n->translations['<?= $generator->moduleID ?>'] = [
            'class' => $class,
            'on missingTranslation' => function ($event) {
                $event->sender->handleMissingTranslation($event);
            },
        ];
    }

    /**
     * Translate message
     * @param $message
     * @param array $params
     * @param null $language
     * @return mixed
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::$app->getModule('<?= $generator->moduleID ?>')->translate($message, $params, $language);
    }

    /**
     * Translate message
     * @param $message
     * @param array $params
     * @param null $language
     * @return mixed
     */
    public static function translate($message, $params = [], $language = null)
    {
        return Yii::t('<?= $generator->moduleID ?>', $message, $params, $language);
    }
}
