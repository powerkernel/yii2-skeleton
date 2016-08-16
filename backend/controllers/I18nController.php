<?php
namespace backend\controllers;

use common\models\MessageSearch;
use Yii;


/**
 * I18nController
 */
class I18nController extends BackendController
{


    /**
     * Index
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
}
