<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\components;


use nirvana\jsonld\JsonLDHelper;
use Yii;
use yii\web\Controller;

/**
 * Class MainController
 * @package common\components
 */
class MainController extends Controller
{
    /**
     * register metaTags and JsonLD info
     * @param array $data
     */
    public function registerMetaTagJsonLD($data = [])
    {
        $this->view->title = !empty($data['title']) ? $data['title'] : Yii::$app->name;

        if (!empty($data['jsonLd'])) {
            JsonLDHelper::add($data['jsonLd']);
        }
        if (!empty($data['metaTags'])) {
            foreach ($data['metaTags'] as $tag) {
                $this->view->registerMetaTag($tag);
            }
        }
    }
}