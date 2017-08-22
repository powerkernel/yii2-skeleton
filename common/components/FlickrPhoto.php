<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
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
 * @author Harry Tang <harry@modernkernel.com>
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
            if (is_array($params)) {
                $request->setData($params);
            } else {
                $request->setContent($params);
            }
        }


        return $this->sendRequest($request);
    }
}
