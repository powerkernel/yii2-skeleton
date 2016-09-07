<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\components;
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
    public function run(){
        if(Yii::$app->request->isAjax){
            $photos=Yii::$app->session->get('flickr');
            if(!empty($photos)){
                $client=$this->getFlickr();
                if($client){
                    $items=[];
                    foreach($photos as $id){
                        $key='flickr-photo-'.$id;
                        $data = Yii::$app->cache->get($key);
                        if($data===false){
                            $params=[
                                'method'=>'flickr.photos.getSizes',
                                'photo_id'=>$id
                            ];
                            $data=$client->api('','GET', $params);

                            /* cache time */
                            if($data['stat']=='ok'){
                                $items[$id]=$data;
                                Yii::$app->cache->set($key, $data, 600);
                            }

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
     */
    public function renderHtml($photos){
        ?>
        <div class="row">
            <?php foreach ($photos as $id => $photo): ?>
                <div class="col-sm-2">
                    <img style="width: 100%; cursor: pointer" data-toggle="modal" data-target="#flickr-<?= $id ?>" src="<?= $photo['sizes']['size'][array_search('Thumbnail', array_column($photo['sizes']['size'], 'label'))]['source'] ?>" class="flickr-photo img-responsive img-thumbnail"/>
                    <div id="flickr-<?= $id ?>" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><?= $id ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form role="form" class="form-horizontal">
                                        <?php foreach ($photo['sizes']['size'] as $size): ?>
                                            <div class="form-group">
                                                <label for="<?= $size['label'] ?>" class="col-sm-4 control-label"><?= $size['label'] ?>: <?= $size['width'] ?>x<?= $size['height'] ?></label>
                                                <div class="col-sm-8">
                                                    <?= Html::textInput($size['label'], $size['source'], ['class' => 'form-control  input-sm photo-url', 'readonly' => true]) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
            <?php endforeach; ?>
        </div>

        <?php
    }
}