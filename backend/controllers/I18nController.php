<?php
namespace backend\controllers;

use common\Core;
use common\models\Message;
use common\models\MessageSearch;
use Yii;
use yii\filters\VerbFilter;


/**
 * I18nController
 */
class I18nController extends BackendController
{
    /**
     * add new language
     * @return \yii\web\Response
     */
    public function actionAdd(){
        $curLang=Yii::$app->language;
        Yii::$app->language=Yii::$app->request->post('language');
        Yii::t('app', 'Home');
        Yii::$app->language=$curLang;
        Yii::$app->session->setFlash('success', Yii::t('app', 'New language has been successfully added.'));
        return $this->redirect(['index']);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                    'delete' => ['post'],
                ],
            ],

        ];
    }

    /**
     * Index
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /* new lang list */
        $langs=Core::getLocaleList();
        $currents=Message::getLocaleList([Yii::$app->sourceLanguage]);
        foreach ($currents as $key=>$name){
            unset($langs[$key]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'langs'=>$langs
        ]);
    }

    /**
     * ajax save translation
     */
    public function actionSaveTranslation()
    {
        // POST: id, value
        if (Yii::$app->request->isPost) {
            $parts = explode('_', Yii::$app->request->post('id'));
            $id = $parts[1];
            $language = $parts[2];

            $value = Yii::$app->request->post('value');
            if (!empty($id)) {
                Yii::$app->db->createCommand()->update('{{%core_message}}', ['translation' => $value, 'is_translated' => 1], ['id' => $id, 'language' => $language])->execute();
                echo $value;
            }

        }
    }

    /**
     * delete translate
     * @param $id
     * @param $language
     * @return \yii\web\Response
     */
    public function actionDelete($id, $language){
        Yii::$app->db->createCommand()->delete('{{%core_message}}', ['id' => $id, 'language' => $language])->execute();
        return $this->redirect(['index']);
    }
}
