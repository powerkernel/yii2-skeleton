<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace common\behaviors;
use MongoDB\BSON\UTCDateTime;

/**
 * Class UTCDateTimeBehavior
 * UTCDateTimeBehavior automatically fills the specified attributes with the current UTCDateTime.
 */
class UTCDateTimeBehavior extends \yii\behaviors\TimestampBehavior
{
    /**
     * @param \yii\base\Event $event
     * @return mixed|UTCDateTime
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return new UTCDateTime();
        }
        return parent::getValue($event);
    }
}