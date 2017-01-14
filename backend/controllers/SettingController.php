<?php

namespace backend\controllers;

use common\Core;
use Yii;
use common\models\Setting;
use yii\base\DynamicModel;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $parent = parent::behaviors();
        return array_merge($parent, [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $tabs = (new Query())->select('group')->from('{{%core_setting}}')->orderBy('key_order')->distinct()->column();
        $attributes = (new Query())->select('key')->from('{{%core_setting}}')->orderBy('key_order')->column();

        //var_dump($tabs);


        $model = new DynamicModel($attributes);
        $settings = [];
        foreach ($attributes as $attribute) {
            $setting = Setting::find()->where(['key' => $attribute])->asArray()->one();
            $settings[$setting['group']][$attribute] = $setting;
            $model->$attribute = $setting['value'];
            //$model->la

            if (!empty($rules = json_decode($setting['rules'], true))) {
                foreach ($rules as $rule => $conf) {
                    //var_dump($conf);
                    $model->addRule($attribute, $rule, $conf);
                }
            } else {
                $model->addRule($attribute, 'required');
            }

        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($attributes as $attribute) {
                $s = Setting::findOne($attribute);
                $s->value = $model->$attribute;
                if ($s->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Settings saved successfully.'));

                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. {ERRORS}.', ['ERRORS' => json_encode($s->errors)]));
                    break;
                }

            }
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model,
            'settings' => $settings,
            'tabs' => $tabs
        ]);
    }


    /**
     * update settings
     * @return \yii\web\Response
     */
    public function actionUpdate()
    {
        SettingController::updateSetting();
        return $this->redirect(['index']);
    }

    /**
     * update settings
     */
    public static function updateSetting()
    {
        /* default */
        $s = [
            /* General */
            ['key' => 'title', 'value' => 'Yii2 Skeleton', 'title' => 'Title', 'description' => 'Homepage title', 'group' => 'General', 'type' => 'textInput', 'data' => '[]', 'default' => 'Yii2 Skeleton', 'rules' => json_encode(['required' => []])],
            ['key' => 'keywords', 'value' => 'Yii2, Skeleton', 'title' => 'Keywords', 'description' => 'Homepage keywords', 'group' => 'General', 'type' => 'textInput', 'data' => '[]', 'default' => 'Yii2, Skeleton', 'rules' => json_encode(['required' => []])],
            ['key' => 'description', 'value' => 'Skeleton for Yii Framework', 'title' => 'Description', 'description' => 'Homepage description', 'group' => 'General', 'type' => 'textInput', 'data' => '[]', 'default' => 'Skeleton for Yii Framework', 'rules' => json_encode(['required' => []])],
            ['key' => 'language', 'value' => 'en-US', 'title' => 'Language', 'description' => 'Site language', 'group' => 'General', 'type' => 'dropDownList', 'data' => '{LOCALE}', 'default' => 'en-US', 'rules' => json_encode(['required' => []])],
            ['key' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh', 'title' => 'Timezone', 'description' => 'Server Timezone', 'group' => 'General', 'type' => 'dropDownList', 'data' => '{TIMEZONE}', 'default' => 'Asia/Ho_Chi_Minh', 'rules' => json_encode(['required' => []])],
            ['key' => 'languageUrlCode', 'value' => '0', 'title' => 'Language URL', 'description' => 'Include language code in URL', 'group' => 'General', 'type' => 'dropDownList', 'data' => json_encode(Core::getYesNoOption()), 'default' => '0', 'rules' => json_encode(['required' => [], 'boolean' => []])],
            ['key' => 'debug', 'value' => '0', 'title' => 'Debug Mode', 'description' => 'Turn debug mode ON/OFF', 'group' => 'General', 'type' => 'dropDownList', 'data' => json_encode(Core::getYesNoOption()), 'default' => '0', 'rules' => json_encode(['required' => [], 'boolean' => []])],


            /* Account */
            ['key' => 'maxNameChange', 'value' => '1', 'title' => 'Max Name Change', 'description' => 'Max name change allowed', 'group' => 'Account', 'type' => 'textInput', 'data' => '[]', 'default' => '1', 'rules' => json_encode(['required' => [], 'number' => ['min' => -1]])],
            ['key' => 'tokenExpiryTime', 'value' => '3600', 'title' => 'Token Expiry Time', 'description' => 'Expiration time in seconds', 'group' => 'Account', 'type' => 'textInput', 'data' => '[]', 'default' => '3600', 'rules' => json_encode(['required' => [], 'number' => ['min' => 3600]])],
            ['key' => 'rememberMeDuration', 'value' => '2592000', 'title' => 'Remember Me Duration', 'description' => 'Customize the duration of the Remember Me in seconds', 'group' => 'Account', 'type' => 'textInput', 'data' => '[]', 'default' => '2592000', 'rules' => json_encode(['required' => [], 'number' => ['min' => 86400]])],

            /* Blog */
            ['key' => 'blogTitle', 'value' => 'My Blog', 'title' => 'Title', 'description' => 'Blog page title', 'group' => 'Blog', 'type' => 'textInput', 'data' => json_encode(Core::getYesNoOption()), 'default' => 'My Blog', 'rules' => json_encode(['required' => []])],
            ['key' => 'blogDesc', 'value' => 'Welcome to my world.', 'title' => 'Description', 'description' => 'Blog page description', 'group' => 'Blog', 'type' => 'textInput', 'data' => json_encode(Core::getYesNoOption()), 'default' => 'Welcome to my world.', 'rules' => json_encode(['required' => []])],
            ['key' => 'blogKeywords', 'value' => 'blog, my blog', 'title' => 'Keywords', 'description' => 'Blog page keywords', 'group' => 'Blog', 'type' => 'textInput', 'data' => json_encode(Core::getYesNoOption()), 'default' => 'blog, my blog', 'rules' => json_encode(['required' => []])],
            ['key' => 'blogThumbnail', 'value' => '', 'title' => 'Thumbnail Image', 'description' => 'Blog page thumbnail', 'group' => 'Blog', 'type' => 'textInput', 'data' => json_encode(Core::getYesNoOption()), 'default' => '', 'rules' => json_encode(['url' => []])],

            /* Mail */
            ['key' => 'adminMail', 'value' => 'youremail@domain.com', 'title' => 'Administrator\'s Mail', 'description' => 'Admin email address', 'group' => 'Mail', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'email' => []])],
            ['key' => 'outgoingMail', 'value' => 'youremail@domain.com', 'title' => 'Outgoing Mail', 'description' => 'Outgoing email address', 'group' => 'Mail', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['required' => [], 'email' => []])],
            ['key' => 'mailProtocol', 'value' => 'php', 'title' => 'Mail Protocol', 'description' => 'Outgoing email protocol', 'group' => 'Mail', 'type' => 'dropDownList', 'data' => json_encode(['php' => 'php', 'smtp' => 'smtp']), 'default' => 'php', 'rules' => json_encode(['required' => []])],
            ['key' => 'smtpHost', 'value' => '', 'title' => 'SMTP Host', 'description' => 'SMTP host', 'group' => 'Mail', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'smtpUsername', 'value' => '', 'title' => 'SMTP Username', 'description' => 'SMTP username', 'group' => 'Mail', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'smtpPassword', 'value' => '', 'title' => 'SMTP Password', 'description' => 'SMTP password', 'group' => 'Mail', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'smtpPort', 'value' => '', 'title' => 'SMTP Port', 'description' => 'SMTP port', 'group' => 'Mail', 'type' => 'textInput', 'data' => '[]', 'default' => '25', 'rules' => json_encode(['safe' => [], 'number' => []])],
            ['key' => 'smtpEncryption', 'value' => '', 'title' => 'SMTP Encryption', 'description' => 'SMTP Encryption', 'group' => 'Mail', 'type' => 'textInput', 'data' => '[]', 'default' => 'ssl', 'rules' => json_encode(['safe' => [], 'string' => []])],

            /* Social */
            ['key' => 'fbPageUrl', 'value' => '', 'title' => 'Facebook Page URL', 'description' => 'Facebook Page URL', 'group' => 'Social', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'url' => []])],
            ['key' => 'fbAppId', 'value' => '', 'title' => 'Facebook App ID', 'description' => 'Facebook App ID', 'group' => 'Social', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'fbAdmins', 'value' => '', 'title' => 'Facebook Admins', 'description' => 'Facebook Admins ID', 'group' => 'Social', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'gpPageUrl', 'value' => '', 'title' => 'Google+ Page URL', 'description' => 'Google+ Page URL', 'group' => 'Social', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'url' => []])],
            ['key' => 'twitterSite', 'value' => '', 'title' => 'Twitter Card Site', 'description' => '@username for the website used', 'group' => 'Social', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],

            /* API */
            ['key' => 'reCaptchaKey', 'value' => '', 'title' => 'reCaptcha Site Key', 'description' => 'reCaptcha Site Key', 'group' => 'API', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'reCaptchaSecret', 'value' => '', 'title' => 'reCaptcha Secret', 'description' => 'reCaptcha Secret', 'group' => 'API', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],

            ['key' => 'facebookAppId', 'value' => '', 'title' => 'Facebook App ID', 'description' => 'Facebook App ID', 'group' => 'API', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'facebookAppSecret', 'value' => '', 'title' => 'Facebook App Secret', 'description' => 'Facebook App Secret', 'group' => 'API', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],

            ['key' => 'googleClientId', 'value' => '', 'title' => 'Google Client ID', 'description' => 'Google Client ID', 'group' => 'API', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'googleClientSecret', 'value' => '', 'title' => 'Google Client Secret', 'description' => 'Google Client Secret', 'group' => 'API', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],

            ['key' => 'flickrClientKey', 'value' => '', 'title' => 'Flickr Client Key', 'description' => 'Flickr Client Key', 'group' => 'API', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'flickrClientSecret', 'value' => '', 'title' => 'Flickr Client Secret', 'description' => 'Flickr Client Secret', 'group' => 'API', 'type' => 'passwordInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],

            /* Theme */
            ['key' => 'androidThemeColor', 'value' => '#3c8dbc', 'title' => 'Android Theme Color', 'description' => 'Android theme color', 'group' => 'Theme', 'type' => 'textInput', 'data' => '[]', 'default' => '#3c8dbc', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'msTileColor', 'value' => '#3c8dbc', 'title' => 'MS Tile Color', 'description' => 'Background color for a live tile', 'group' => 'Theme', 'type' => 'textInput', 'data' => '[]', 'default' => '#3c8dbc', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'safariMaskColor', 'value' => '#3c8dbc', 'title' => 'Safari Mask Color', 'description' => 'Safari pinned tab color', 'group' => 'Theme', 'type' => 'textInput', 'data' => '[]', 'default' => '#3c8dbc', 'rules' => json_encode(['safe' => [], 'string' => []])],

            /* Enhancements */
            ['key' => 'googleCustomSearch', 'value' => '', 'title' => 'Google Custom Search', 'description' => 'CX code: 123456789012345678901:abcdefjh123', 'group' => 'Enhancements', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'googleAnalytics', 'value' => '', 'title' => 'Google Analytics', 'description' => 'Tracking ID: UA-1111111-22', 'group' => 'Enhancements', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'disqus', 'value' => '', 'title' => 'Disqus', 'description' => 'Disqus shortname', 'group' => 'Enhancements', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],


            ['key' => 'zopim', 'value' => '', 'title' => 'Zopim Chat', 'description' => 'Zopim ID: 5d8f1e3c8f77c45608ada76d51256aad', 'group' => 'Enhancements', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'addthis', 'value' => '', 'title' => 'Addthis', 'description' => 'Addthis ID: ra-123a1234567890b1', 'group' => 'Enhancements', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'headJs', 'value' => '', 'title' => 'Header JS', 'description' => 'Header Javascript', 'group' => 'Enhancements', 'type' => 'textarea', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],

            /* Ads */
            ['key' => 'adsense', 'value' => '', 'title' => 'Google Adsense', 'description' => 'Client ID: ca-pub-1234567890123456', 'group' => 'Ads', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'amzTracking', 'value' => '', 'title' => 'Amazon Tracking ID', 'description' => 'Tracking ID: yourname-11', 'group' => 'Ads', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'amzAds', 'value' => '', 'title' => 'Amazon Native Ads', 'description' => 'Ad Instance ID: 12abc1ab-ab12-1234-a123-1abcd123456a', 'group' => 'Ads', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
            ['key' => 'amzSearchAds', 'value' => '', 'title' => 'Amazon Search Ads', 'description' => 'Link ID: 12abc1ab-ab12-1234-a123-1abcd123456a', 'group' => 'Ads', 'type' => 'textInput', 'data' => '[]', 'default' => '', 'rules' => json_encode(['safe' => [], 'string' => []])],
        ];
        /* merge module setting */
        $s = array_merge($s, self::loadModuleSetting());
        //var_dump($s);
        //return;


        /* delete old settings */
        $settings = Setting::find()->all();
        foreach ($settings as $setting) {
            if (!in_array($setting->key, array_column($s, 'key'))) {
                $setting->delete();
            }
        }

        /* sync */
        $unsave=[];
        foreach ($s as $i => $setting) {
            $conf = Setting::findOne($setting['key']);
            if (!$conf) {
                $conf = new Setting();
                $conf->key = $setting['key'];
                $conf->value = $setting['value'];
            }

            $conf->title = $setting['title'];
            $conf->description = $setting['description'];
            $conf->group = $setting['group'];
            $conf->type = $setting['type'];
            $conf->data = $setting['data'];
            $conf->default = $setting['default'];
            $conf->rules = $setting['rules'];
            $conf->key_order = $i;
            if(!$conf->save()){
                $unsave[]=$conf->key;
            }
        }

		if(is_a(Yii::$app, '\yii\web\Application')){
			if(empty($unsave)){
				Yii::$app->session->setFlash('success', Yii::t('app', 'All settings has been updated.'));
			}
			else {
				Yii::$app->session->setFlash('warning', Yii::t('app', 'Some setting(s) can not be updated: {SETTINGS}', ['SETTINGS'=>implode(', ', $unsave)]));
			}					
		}
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * check if setting tab is enabled
     * @param $tab
     * @return bool
     */
    public function isTabEnable($tab)
    {
        if ($tab == 'Blog' && Yii::$app->params['enableBlog'] === false) {
            return false;
        }
        return true;
    }

    /**
     * load module settings
     * @return array all modules settings
     */
    public static function loadModuleSetting()
    {
        $file = 'settings.php';
        $vendors = [
            'harrytang',
            'modernkernel'
        ];
        $settings = []; // all modules settings save here
        foreach ($vendors as $vendor) {
            if (file_exists(Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor)) {
                $modules = scandir(Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor);
                foreach ($modules as $module) {
                    if (!preg_match('/[\.]+/', $module)) {
                        $moduleSettingFile = \Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $file;
                        if (is_file($moduleSettingFile)) {
                            $s = require($moduleSettingFile);
                            if (is_array($s)) {
                                $settings = array_merge($settings, $s);
                            }
                        }
                    }
                }
            }
        }
        return $settings;
    }
}
