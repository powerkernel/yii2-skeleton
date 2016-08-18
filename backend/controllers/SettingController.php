<?php

namespace backend\controllers;

use common\Core;
use Yii;
use common\models\Setting;
use common\models\SettingSearch;
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
        $attributes=(new Query())->select('key')->from('{{%core_setting}}')->column();
        $tabs=(new Query())->select('group')->from('{{%core_setting}}')->distinct()->column();
        //var_dump($tabs);


        $model = new DynamicModel($attributes);
        $settings=[];
        foreach ($attributes as $attribute) {
            $setting=Setting::find()->where(['key'=>$attribute])->asArray()->one();
            $settings[$setting['group']][$attribute]=$setting;
            $model->$attribute=$setting['value'];

            if(!empty($rules=json_decode($setting['rules'], true))){
                foreach ($rules as $rule => $conf) {
                    //var_dump($conf);
                    $model->addRule($attribute, $rule, $conf);
                }
            }
            else {
                $model->addRule($attribute, 'required');
            }

        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach($attributes as $attribute){
                $s=Setting::findOne($attribute);
                $s->value=$model->$attribute;
                if($s->save(false)){
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Settings saved successfully.'));
                }
                else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, something went wrong. {ERRORS}.', ['ERRORS'=>json_encode($s->errors)]));
                    break;
                }

            }
        }

        return $this->render('index', [
            'model' => $model,
            'settings' => $settings,
            'tabs'=>$tabs
        ]);
    }

    public function actionUpdate(){
        /* delete */
        //Yii::$app->db->createCommand()->delete('{{%core_setting}}')->execute();
        /* insert */
        $s=[
            /* General */
            ['key'=>'language', 'value'=>'en-US', 'description'=>'Site language', 'group'=>'General', 'type'=>'dropDownList', 'data'=>'{LOCALE}', 'default'=>'en-US', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'timezone', 'value'=>'Asia/Ho_Chi_Minh', 'description'=>'Server Timezone', 'group'=>'General', 'type'=>'dropDownList', 'data'=>'{TIMEZONE}', 'default'=>'Asia/Ho_Chi_Minh', 'rules'=>json_encode(['required'=>[]])],

            /* Account */
            ['key'=>'maxNameChange', 'value'=>'1', 'description'=>'Max name change allowed', 'group'=>'Account', 'type'=>'textInput', 'data'=>'[]', 'default'=>'1', 'rules'=>json_encode(['required'=>[], 'number'=>['min'=>-1]])],
            ['key'=>'tokenExpiryTime', 'value'=>'3600', 'description'=>'Expiration time in seconds', 'group'=>'Account', 'type'=>'textInput', 'data'=>'[]', 'default'=>'3600', 'rules'=>json_encode(['required'=>[], 'number'=>['min'=>3600]])],

            /* Mail */
            ['key'=>'outgoingMail', 'value'=>'youremail@domain.com', 'description'=>'Outgoing email address', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['required'=>[], 'email'=>[]])],
            ['key'=>'mailProtocol', 'value'=>'php', 'description'=>'Outgoing email protocol', 'group'=>'Mail', 'type'=>'dropDownList', 'data'=>json_encode(['php'=>'php', 'smtp'=>'smtp']), 'default'=>'php', 'rules'=>json_encode(['required'=>[]])],
            ['key'=>'smtpHost', 'value'=>'', 'description'=>'SMTP host', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpUsername', 'value'=>'', 'description'=>'SMTP username', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpPassword', 'value'=>'', 'description'=>'SMTP password', 'group'=>'Mail', 'type'=>'passwordInput', 'data'=>'[]', 'default'=>'', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpPort', 'value'=>'', 'description'=>'SMTP port', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'25', 'rules'=>json_encode(['safe'=>[]])],
            ['key'=>'smtpEncryption', 'value'=>'', 'description'=>'SMTP port', 'group'=>'Mail', 'type'=>'textInput', 'data'=>'[]', 'default'=>'ssl', 'rules'=>json_encode(['safe'=>[]])],

        ];

        foreach($s as $setting){
            $conf=Setting::findOne($setting['key']);
            if(!$conf){
                $conf=new Setting();
                $conf->key=$setting['key'];
                $conf->value=$setting['value'];
            }


            $conf->description=$setting['description'];
            $conf->group=$setting['group'];
            $conf->type=$setting['type'];
            $conf->data=$setting['data'];
            $conf->default=$setting['default'];
            $conf->rules=$setting['rules'];
            $conf->save();
        }

        return $this->redirect(['index']);
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
}
