<?php

/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
namespace frontend\widgets;

use common\models\Banner;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\db\Query;
use yii\helpers\HtmlPurifier;

/**
 * Class CarouselBanner
 * @package frontend\widgets
 */
class CarouselBanner extends Widget
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        $query=(new Query())->select('*')
            ->from('{{%core_banner}}')
            ->where('`status`=:status AND (`lang` IS NULL OR `lang`=:lang)', [':status'=>Banner::STATUS_ACTIVE, ':lang'=>Yii::$app->language]);
            //->andwhere('lang IS NULL')
            //->orWhere(['lang'=>Yii::$app->language]);
        //echo $query->createCommand()->rawSql;

        //$banners = Banner::find()->where(['status' => Banner::STATUS_ACTIVE])->all();
        $banners=$query->all();


        if ($banners) {
            $items=[];
            $config=[
                'HTML.MaxImgLength'=>null,
                'CSS.MaxImgLength'=>null,
                'HTML.Trusted'=>true,
                'CSS.Trusted'=>true,
                'Filter.YouTube'=>true,
            ];


            foreach($banners as $banner){
                $img=Html::img($banner['banner_url'], ['alt'=>$banner['title'], 'class'=>'img-responsive']);
                if(!empty($banner['link_url'])){
                    $img=Html::a($img, $banner['link_url'], ['title'=>$banner['title'], 'target'=>$banner['link_option']]);
                }
                $text= $banner['text_content'];
                $style=$banner['text_style'];
                $html=<<<EOB
<div class="banner-box" style="position: relative">
    <div>{$img}</div>
    <div style="position: absolute; {$style}">{$text}</div>
</div>
EOB;

                $items[]=[
                    'content'=>HtmlPurifier::process($html, $config)
                ];
            }

            return $this->render('carouselBanner', ['items' => $items]);
        }
        return null;
    }

}
