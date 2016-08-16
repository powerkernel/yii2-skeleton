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

    /**
     * Displays a single Setting model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->key]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->key]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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
