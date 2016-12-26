<?php

use common\Core;
use common\models\Setting;
use yii\db\Migration;

/**
 * Class m160819_133744_update_setting
 */
class m160819_133744_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%core_setting}}', 'key_order', $this->integer()->notNull()->defaultValue(0));
        $this->insertSettings();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%core_setting}}', 'key_order');
    }

    protected function insertSettings(){
        $s=[
            /* General */
            ['key'=>'language', 'value'=>'en-US', 'title'=>'Language', 'description'=>'Site language', 'group'=>'General', 'type'=>'dropDownList', 'data'=>'{LOCALE}', 'default'=>'en-US', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'timezone', 'value'=>'Asia/Ho_Chi_Minh', 'title'=>'Timezone', 'description'=>'Server Timezone', 'group'=>'General', 'type'=>'dropDownList', 'data'=>'{TIMEZONE}', 'default'=>'Asia/Ho_Chi_Minh', 'rules'=>json_encode(['required'=>[]])],


            /* Account */
            ['key'=>'maxNameChange', 'value'=>'1', 'title'=>'Max Name Change', 'description'=>'Max name change allowed', 'group'=>'Account', 'type'=>'textInput', 'data'=>'[]', 'default'=>'1', 'rules'=>json_encode(['required'=>[], 'number'=>['min'=>-1]])],
            ['key'=>'tokenExpiryTime', 'value'=>'3600', 'title'=>'Token Expiry Time', 'description'=>'Expiration time in seconds', 'group'=>'Account', 'type'=>'textInput', 'data'=>'[]', 'default'=>'3600', 'rules'=>json_encode(['required'=>[], 'number'=>['min'=>3600]])],
            ['key'=>'rememberMeDuration', 'value'=>'2592000', 'title'=>'Remember Me Duration', 'description'=>'Customize the duration of the Remember Me in seconds', 'group'=>'Account', 'type'=>'textInput', 'data'=>'[]', 'default'=>'2592000', 'rules'=>json_encode(['required'=>[], 'number'=>['min'=>86400]])],

            /* SEO */
            ['key'=>'title', 'value'=>'Yii2 Skeleton', 'title'=>'Title', 'description'=>'Homepage title', 'group'=>'SEO', 'type'=>'textInput', 'data'=>'[]', 'default'=>'Yii2 Skeleton', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'keywords', 'value'=>'Yii2, Skeleton', 'title'=>'Keywords', 'description'=>'Homepage keywords', 'group'=>'SEO', 'type'=>'textInput', 'data'=>'[]', 'default'=>'Yii2, Skeleton', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'description', 'value'=>'Skeleton for Yii Framework', 'title'=>'Description', 'description'=>'Homepage description', 'group'=>'SEO', 'type'=>'textInput', 'data'=>'[]', 'default'=>'Skeleton for Yii Framework', 'rules'=>json_encode(['required'=>[]])],

            /* Mail */
            ['key'=>'outgoingMail', 'value'=>'youremail@domain.com', 'title'=>'Outgoing Mail', 'description'=>'Outgoing email address', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['required'=>[], 'email'=>[]])],
            ['key'=>'mailProtocol', 'value'=>'php', 'title'=>'Mail Protocol', 'description'=>'Outgoing email protocol', 'group'=>'Mail', 'type'=>'dropDownList', 'data'=>json_encode(['php'=>'php', 'smtp'=>'smtp']), 'default'=>'php', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'smtpHost', 'value'=>'', 'title'=>'SMTP Host', 'description'=>'SMTP host', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpUsername', 'value'=>'', 'title'=>'SMTP Username', 'description'=>'SMTP username', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpPassword', 'value'=>'', 'title'=>'SMTP Password', 'description'=>'SMTP password', 'group'=>'Mail', 'type'=>'passwordInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpPort', 'value'=>'', 'title'=>'SMTP Port', 'description'=>'SMTP port', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'25', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpEncryption', 'value'=>'', 'title'=>'SMTP Encryption', 'description'=>'SMTP port', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'ssl', 'rules'=>json_encode(['safe'=>[]])],

            /* API */
            ['key'=>'reCaptchaKey', 'value'=>'', 'title'=>'reCaptcha Site Key', 'description'=>'reCaptcha Site Key', 'group'=>'API', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'reCaptchaSecret', 'value'=>'', 'title'=>'reCaptcha Secret', 'description'=>'reCaptcha Secret', 'group'=>'API', 'type'=>'passwordInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],

            ['key'=>'facebookAppId', 'value'=>'', 'title'=>'Facebook App ID', 'description'=>'Facebook App ID', 'group'=>'API', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'facebookAppSecret', 'value'=>'', 'title'=>'Facebook App Secret', 'description'=>'Facebook App Secret', 'group'=>'API', 'type'=>'passwordInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],

            ['key'=>'googleClientId', 'value'=>'', 'title'=>'Google Client ID', 'description'=>'Google Client ID', 'group'=>'API', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'googleClientSecret', 'value'=>'', 'title'=>'Google Client Secret', 'description'=>'Google Client Secret', 'group'=>'API', 'type'=>'passwordInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],

            ['key'=>'flickrClientKey', 'value'=>'', 'title'=>'Flickr Client Key', 'description'=>'Flickr Client Key', 'group'=>'API', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'flickrClientSecret', 'value'=>'', 'title'=>'Flickr Client Secret', 'description'=>'Flickr Client Secret', 'group'=>'API', 'type'=>'passwordInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],


            /* System */
            //['key'=>'language', 'value'=>'en-US', 'title'=>'Language', 'description'=>'Site language', 'group'=>'General', 'type'=>'dropDownList', 'data'=>'{LOCALE}', 'default'=>'en-US', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'debug', 'value'=>'0', 'title'=>'Debug Mode', 'description'=>'Turn on debug mode', 'group'=>'System', 'type'=>'dropDownList', 'data'=>json_encode(Core::getYesNoOption()), 'default'=>'0', 'rules'=>json_encode(['required'=>[]])],
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
