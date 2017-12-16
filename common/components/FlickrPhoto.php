<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\components;

/**
 * FlickrPhoto allows authentication via Flickr OAuth and then upload photos to flickr.
 *
 * In order to use Flickr OAuth you must register your application with flickr
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'flickr-photo' => [
 *                 'class' => 'common\components\FlickrAuth',
 *                 'perms'=>'write',
 *                 'consumerKey' => 'flickr_consumer_key',
 *                 'consumerSecret' => 'flickr_consumer_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 * @author Harry Tang <harry@powerkernel.com>
 */
class FlickrPhoto extends FlickrAuth
{
    public $perms='delete';


    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'flickr-photo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Flickr Photo';
    }

    /**
     * @param array $data
     * @param string $file
     * @param array $headers
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     */
    public function apiUpload($data = [], $file='', $headers = [])
    {
        $format=['format'=>'json', 'nojsoncallback'=>1];
        $params=array_merge($format, $data);
        $request = $this->createApiRequest()
            ->setMethod('POST')
            ->setUrl('https://up.flickr.com/services/upload/')
            ->addFile('photo', $file)
            ->addHeaders($headers);

        if (!empty($data)) {
            //if (is_array($params)) {
                $request->setData($params);
            //} else {
             //   $request->setContent($params);
            //}
        }


        return $this->sendRequest($request);
    }

    /**
     * get photo sizes
     * @param $photoId
     * @return bool
     */
    public function getPhotoSizes($photoId){
        $params=[
            'method'=>'flickr.photos.getSizes',
            'photo_id'=>$photoId
        ];
        $data=$this->api('', 'GET', $params);
        if(!empty($data['stat']) && $data['stat']=='ok'){
            return $data['sizes']['size'];
        }
        return false;
    }
}
