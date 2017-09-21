<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
namespace common\components;

use common\models\SourceMessage;
use Yii;
use yii\i18n\MissingTranslationEvent;

/**
 * Class DbMessageSource
 * @package common\components
 */
class DbMessageSource extends \yii\i18n\DbMessageSource
{

    public $messageTable = '{{%core_message}}';
    public $sourceMessageTable = '{{%core_source_message}}';
    public $enableCaching=true;

    /**
     * handle missing translation
     * @param $event MissingTranslationEvent
     */
    public function handleMissingTranslation($event)
    {
        Yii::$app->cache->flush();
        /* find source */
        $source=SourceMessage::find()
            ->where('`category`=:category AND BINARY `message`=:message')
            ->params([':category' => $event->category, ':message' => $event->message])
            ->one();


        /* create source message if no exist */
        if (!$source) {
            $source=new SourceMessage();
            $source->category=$event->category;
            $source->message=$event->message;
            $source->save();
        }

        //file_put_contents('d:\log1.txt', $event->message);

        /* add translate message */
        try {
            Yii::$app->db->createCommand()->insert($this->messageTable, ['id' => $source->id, 'language' => $event->language, 'translation' => $event->message])->execute();
        }catch (yii\db\Exception $e){
            // just do nothing
        }
        //file_put_contents('D:\log.txt', 'mission');

    }

} 