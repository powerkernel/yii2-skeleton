<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\components;

use yii\helpers\ArrayHelper;
use yii\i18n\MissingTranslationEvent;
use yii\mongodb\Query;

/**
 * Class MongoDbMessageSource
 * @package common\components
 */
class MongoDbMessageSource extends \yii\mongodb\i18n\MongoDbMessageSource
{

    public $collection = 'core_message';

    /**
     * Loads the messages from database.
     * You may override this method to customize the message storage in the database.
     *
     * @param string $category the message category.
     * @param string $language the target language.
     *
     * @return array the messages loaded from database.
     */
    protected function loadMessagesFromDb($category, $language)
    {
        $fallbackLanguage = substr($language, 0, 2);
        $fallbackSourceLanguage = substr($this->sourceLanguage, 0, 2);
        $languages = [
            $language,
            $fallbackLanguage,
            $fallbackSourceLanguage
        ];
        $rows = (new Query())
            ->select(['language', 'message', 'translation'])
            ->from($this->collection)
            ->andWhere(['category' => $category])
            ->andWhere(['language' => array_unique($languages)])
            ->all($this->db);

        if (count($rows) > 1) {
            $languagePriorities = [
                $language => 1
            ];
            $languagePriorities[$fallbackLanguage] = 2; // language key may be already taken
            $languagePriorities[$fallbackSourceLanguage] = 3; // language key may be already taken
            usort($rows, function ($a, $b) use ($languagePriorities) {
                $languageA = $a['language'];
                $languageB = $b['language'];
                if ($languageA === $languageB) {
                    return 0;
                }
                if ($languagePriorities[$languageA] < $languagePriorities[$languageB]) {
                    return +1;
                }
                return -1;
            });
        }

        $messages = ArrayHelper::map($rows, 'message', 'translation');

        return $messages;

    }


    /**
     * @param MissingTranslationEvent $event
     */
    public function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = $event->message;
        $collection = \Yii::$app->mongodb->getCollection($this->collection);
        $collection->insert(
            [
                'category' => $event->category,
                'language' => $event->language,
                'message' => $event->message,
                'translation' => $event->message,
                'is_translated' => false
            ]
        );
    }
}
