<?php

use common\Core;
use common\models\Setting;
use yii\db\Migration;

/**
 * Class m160830_163425_insert_settings
 */
class m160830_163425_insert_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insertSettings();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function down()
    {
        echo "m160830_163425_insert_settings cannot be reverted.\n";

        return false;
    }

    /**
     * insert settings
     */
    protected function insertSettings(){
        $s=[
            ['key'=>'languageUrlCode', 'value'=>'0', 'title'=>'Language URL', 'description'=>'Include language code in URL', 'group'=>'SEO', 'type'=>'dropDownList', 'data'=>json_encode(Core::getYesNoOption()), 'default'=>'0', 'rules'=>json_encode(['required'=>[], 'boolean'=>[]])],
        ];

        foreach($s as $i=>$setting){
            $conf=Setting::findOne($setting['key']);
            if(!$conf){
                $conf=new Setting();
                $conf->key=$setting['key'];
                $conf->value=$setting['value'];
            }

            $conf->title=$setting['title'];
            $conf->description=$setting['description'];
            $conf->group=$setting['group'];
            $conf->type=$setting['type'];
            $conf->data=$setting['data'];
            $conf->default=$setting['default'];
            $conf->rules=$setting['rules'];
            $conf->key_order=$i;
            $conf->save();
        }

        //return $this->redirect(['index']);
    }
}
