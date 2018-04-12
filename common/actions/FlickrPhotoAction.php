<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\actions;

use Yii;
use yii\helpers\Html;


/**
 * Class FlickrPhotoAction
 * @package common\components
 */
class FlickrPhotoAction extends FlickrAction
{
    /**
     * run action
     * @return string
     */
    public function run()
    {

        if (Yii::$app->request->isAjax) {
            $photos = Yii::$app->session->get('flickr');
            if (!empty($photos)) {
                $client = $this->getFlickr();
                if ($client) {
                    $items = [];
                    foreach ($photos as $id) {
                        $key = 'flickr-photo-' . $id;
                        $data = Yii::$app->cache->get($key);
                        if ($data === false) {

                            $params = [
                                'method' => 'flickr.photos.getSizes',
                                'photo_id' => $id
                            ];
                            $data = $client->api('', 'GET', $params);

                            /* cache time */
                            if ($data['stat'] == 'ok') {
                                $items[$id] = $data;
                                Yii::$app->cache->set($key, $data, 600);
                            }

                        } else {
                            $items[$id] = $data;
                        }

                    }
                    return $this->renderHtml($items);
                }
            }
        }
        return null;
    }

    /**
     * @param $photos
     * @return string
     */
    public function renderHtml($photos)
    {
        $html = Html::beginTag('div', ['class' => 'row']);
        foreach ($photos as $id => $photo) {
            $html .= Html::beginTag('div', ['class' => 'col-sm-2 text-center']);
                $html .= Html::img(
                    $photo['sizes']['size'][array_search('Thumbnail', array_column($photo['sizes']['size'], 'label'))]['source'],
                    [
                        'style' => 'width: 100%; cursor: pointer',
                        'data-toggle' => 'modal',
                        'data-target' => '#flickr-' . $id,
                        'class'=>'flickr-photo img-responsive img-thumbnail'
                    ]
                );
                $html .= Html::a(
                    Html::tag('span', Yii::t('app', 'Delete'), ['class' => 'label label-danger']),
                    '#delete',
                    [
                        'class' => 'btn-flickr-delete',
                        'data-flickr' => $id,
                    ]
                );

                $html .= Html::beginTag('div', ['class'=>'modal fade', 'id'=>'flickr-'.$id, 'tabindex'=>-1, 'role'=>'dialog']);
                    $html .= Html::beginTag('div', ['class'=>'modal-dialog', 'role'=>'document']);
                        $html .= Html::beginTag('div', ['class'=>'modal-content']);
                            $html .= Html::beginTag('div', ['class'=>'modal-header']);
                                $html .= Html::tag('button', Html::tag('span', '&times;', ['aria-hidden'=>'true']), ['type'=>'button', 'class'=>'close', 'data-dismiss'=>'modal', 'aria-label'=>'Close']);
                                $html .= Html::tag('h4', $id, ['class'=>'modal-title']);
                            $html .= Html::endTag('div');
                            $html .= Html::beginTag('div', ['class'=>'modal-body']);
                                $html .= Html::beginTag('div', ['role'=>'form', 'class'=>'form-horizontal']);
                                foreach ($photo['sizes']['size'] as $size){
                                    $html .= Html::beginTag('div', ['class'=>'form-group']);
                                        $html .= Html::tag('label', $size['label'].' : '.$size['width'].'x'.$size['height'], ['for'=>$size['label'], 'class'=>'col-sm-4 control-label']);
                                        $html .= Html::tag('div', Html::textInput($size['label'], $size['source'], ['class' => 'form-control input-sm photo-url', 'data-copy-text' => Yii::t('app', 'URL copied, closing...'), 'data-modal-id'=>'flickr-'.$id, 'readonly' => true]), ['class'=>'col-sm-8']);
                                    $html .= Html::endTag('div');
                                }
                                $html .= Html::endTag('div');
                            $html .= Html::endTag('div');
                            $html .= Html::beginTag('div', ['class'=>'modal-footer']);
                                $html .= Html::tag('button', Yii::t('app', 'Close'), ['type'=>'button', 'class'=>'btn btn-default', 'data-dismiss'=>'modal']);
                            $html .= Html::endTag('div');
                        $html .= Html::endTag('div');
                    $html .= Html::endTag('div');
                $html .= Html::endTag('div');

            $html .= Html::endTag('div');
        }
        $html .= Html::endTag('div');
        return $html;
    }
}
