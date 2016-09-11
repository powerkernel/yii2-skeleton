<?php

use backend\controllers\SettingController;
use common\Core;
use common\models\Setting;
use yii\db\Migration;

/**
 * Class m160908_070337_update_setting
 */
class m160908_070337_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        SettingController::updateSetting();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m160908_070337_update_setting cannot be reverted.\n";
    }

}
