<?php

namespace common;

use DateTime;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Exception\RuntimeException;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\web\ServerErrorHttpException;

/**
 * core functions
 * @author Harry Tang (giaduy@gmail.com)
 */
class Core
{

    /**
     * convert current yii date format to timestamp
     * @param string $date current date in Yii date format
     * @return bool|int
     */
    public static function date2timestamp($date)
    {
        $format = Yii::$app->formatter->dateFormat;
        if (strncmp($format, 'php:', 4) === 0) {
            $format = substr($format, 4);
        } else {
            $format = FormatConverter::convertDateIcuToPhp($format);
        }

        $d = DateTime::createFromFormat($format, $date);
        if ($d) {
            return $d->getTimestamp();
        }
        return false;
    }


    /**
     * @param array|string $m module
     * @param array|string $c controller
     * @param array|string $a action
     * @param mixed $params The $_GET
     * @return bool
     */
    public static function checkMCA($m, $c, $a, $params = null)
    {
        /* check $_GET */
        if (isset($params)) {
            foreach ($params as $key => $value) {
                if (Yii::$app->request->get($key) != $value) {
                    return false;
                }
            }
        }

        if (!is_array($a)) {
            $action[] = $a;
        } else {
            $action = $a;
        }
        if (!is_array($c)) {
            $controller[] = $c;
        } else {
            $controller = $c;
        }
        if (!is_array($m)) {
            $module[] = $m;
        } else {
            $module = $m;
        }

        /* go */
        if (!in_array(Yii::$app->controller->module->id, ['app-frontend', 'app-backend'])) {
            return (
                (in_array(Yii::$app->controller->module->id, $module) || in_array('*', $module)) &&
                (in_array(Yii::$app->controller->id, $controller) || in_array('*', $controller)) &&
                (in_array(Yii::$app->controller->action->id, $action) || in_array('*', $action))
            );
        }

        if (isset(Yii::$app->controller->id, Yii::$app->controller->action->id)) {

            return (
                (in_array(Yii::$app->controller->id, $controller) || in_array('*', $controller)) &&
                (in_array(Yii::$app->controller->action->id, $action) || in_array('*', $action))
            );
        }

        return false;
    }

    /**
     * new line string to array
     * @param string $s
     * @return array
     */
    public static function nlstring2array($s)
    {
        if ($s == '')
            return null;
        $a = array();
        $temp = preg_split('/\n/', $s, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($temp as $pair) {
            if (preg_match('/(\w+\s*)=(\s*\w+)/', trim($pair))) {
                list($key, $value) = preg_split('/=/', trim($pair), -1, PREG_SPLIT_NO_EMPTY);
                $a[trim($key)] = trim($value);
            }
        }
        return $a;
    }


    /**
     * Array to new line string
     * @param array $a
     * @return string
     */
    public static function array2nlstring($a)
    {
        $s = '';
        if (is_array($a))
            foreach ($a as $k => $v)
                $s .= $k . '=' . $v . '
';
        return $s;
    }


    /**
     * turn array params into query string
     * @param $params[]
     * @return string
     */
    public static function postParam2string($params)
    {
        $string = '';
        foreach ($params as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        rtrim($string, '&');
        return $string;
    }

    /**
     * generate quantity options
     * @param $max
     * @return array
     */
    public static function generateQuantityOptions($max)
    {
        $a = [];
        for ($i = 1; $i <= $max; $i++) {
            $a[$i] = $i;
        }
        return $a;
    }

    /**
     * get Php Max File Size Limit
     * @return mixed
     */
    public static function getPhpMaxFileSizeLimit()
    {
        /**
         * @param $str
         * @return int|string
         */
        $fn = function ($str) {
            $val = trim($str);
            $last = strtolower($str[strlen($str) - 1]);
            switch ($last) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
            return $val;
        };
        $post_max_size = $fn(ini_get('post_max_size'));
        $upload_max_filesize = $fn(ini_get('upload_max_filesize'));
        $max = $post_max_size;
        if ($max > $upload_max_filesize) {
            $max = $upload_max_filesize;
        }
        return $max;
    }


    /**
     * generate seo name
     * @param string $name
     * @return mixed|string
     */
    public static function generateSeoName($name)
    {
        $name = strtolower(trim($name));

        /* replace VI chars */
        $viChars = array('à', 'á', 'ả', 'ã', 'ạ', 'À', 'Á', 'Ả', 'Ã', 'Ạ', 'ă', 'ằ', 'ắ', 'ẳ', 'ẵ', 'ặ', 'Ă', 'Ằ', 'Ắ', 'Ẳ', 'Ẵ', 'Ặ', 'â', 'ầ', 'ấ', 'ẩ', 'ẫ', 'ậ', 'Â', 'Ầ', 'Ấ', 'Ẩ', 'Ẫ', 'Ậ', 'đ', 'Đ', 'è', 'é', 'ẻ', 'ẽ', 'ẹ', 'È', 'É', 'Ẻ', 'Ẽ', 'Ẹ', 'ê', 'ề', 'ế', 'ể', 'ễ', 'ệ', 'Ê', 'Ề', 'Ế', 'Ể', 'Ễ', 'Ệ', 'ì', 'í', 'ỉ', 'ĩ', 'ị', 'Ì', 'Í', 'Ỉ', 'Ĩ', 'Ị', 'ò', 'ó', 'ỏ', 'õ', 'ọ', 'Ò', 'Ó', 'Ỏ', 'Õ', 'Ọ', 'ô', 'ồ', 'ố', 'ổ', 'ỗ', 'ộ', 'Ô', 'Ồ', 'Ố', 'Ổ', 'Ỗ', 'Ộ', 'ơ', 'ờ', 'ớ', 'ở', 'ỡ', 'ợ', 'Ơ', 'Ờ', 'Ớ', 'Ở', 'Ỡ', 'Ợ', 'ù', 'ú', 'ủ', 'ũ', 'ụ', 'Ù', 'Ú', 'Ủ', 'Ũ', 'Ụ', 'ư', 'ừ', 'ứ', 'ử', 'ữ', 'ự', 'Ư', 'Ừ', 'Ứ', 'Ử', 'Ữ', 'Ự', 'ỳ', 'ý', 'ỷ', 'ỹ', 'ỵ', 'Ỳ', 'Ý', 'Ỷ', 'Ỹ', 'Ỵ');
        $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'd', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y');
        $name = str_replace($viChars, $replace, $name);

        $name = preg_replace('/[^a-z0-9]/', '-', $name); // convery to "-"
        $name = preg_replace('|-+|', '-', $name); // remove more then 1 "-"
        return $name;
    }


    /**
     * get bootstrap column
     * @param $col
     * @return float|null
     */
    public static function getBootstrapCol($col)
    {
        if (in_array($col, [1, 2, 3, 4, 6, 12])) {
            return 12 / $col;
        }
        return null;
    }

    /**
     * Get Yes/No text
     * @param $value
     * @return string
     */
    public static function getYesNoText($value)
    {
        if ($value == 1) {
            return Yii::t('app', 'Yes');
        }
        return Yii::t('app', 'No');
    }

    /**
     * Get Yes/No Option
     * @return array
     */
    public static function getYesNoOption()
    {
        return [
            '1' => Yii::t('app', 'Yes'),
            '0' => Yii::t('app', 'No')
        ];
    }


    /**
     * Grey Neuron Powered
     * @return string
     */
    public static function powered()
    {
        return 'Powered by <a target="_blank" href="http://www.greyneuron.com/" rel="external" title="Grey Neuron">Grey Neuron</a>';
    }


    /**
     * @param string $relativePath
     * @param string $file
     * @param null|array $thumbnails
     * @return array
     * @throws ServerErrorHttpException
     */
    public static function saveImageThumb($relativePath, $file, $thumbnails = null)
    {
        try {
            $imagine = Image::getImagine()->open($file);
        } catch (RuntimeException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }

        if ($thumbnails === null) {
            $thumbnails = Yii::$app->params['images'];
        }
        $images = [];
        foreach ($thumbnails as $name => $size) {
            $photo = $name . '.png';
            // open
            $s = $imagine->getSize(); // 3840x2160 px
            preg_match('/^(\d+)x(\d+) px$/', $s, $match);
            $w = $match[1];
            $h = $match[2];
            // landscape, portrait
            $width = $size['width'];
            $height = $size['height'];
            if ($w < $h) // portrait
            {
                $height = $size['width'];
                $width = $size['height'];
            }

            $box = new Box($width, $height);
            //$mode=ImageInterface::THUMBNAIL_INSET;
            $mode = ImageInterface::THUMBNAIL_OUTBOUND;

            if (in_array($name, ['thumbnail', 'original'])) {
                $mode = ImageInterface::THUMBNAIL_INSET;
            }
            $fullPath = Yii::$app->basePath . '/web/' . $relativePath;
            $imagine->thumbnail($box, $mode)->save($fullPath . '/' . $photo);
            // done
            $images[$name] = $relativePath . $photo;
            $box = null;
        }
        unlink($file);
        //file_put_contents('D:\log.txt', json_encode($images));
        return $images;

    }


    /**
     * Generate upload path (uploads/{NAME}/{DATE})
     * @param $name
     * @return array The generated upload path (basePath and uploadPath)
     */
    public static function generateUploadPath($name)
    {
        $date = date('Ym') . '/'; // 201506/
        $basePath = Yii::$app->basePath . '/web/';
        $uploadDir = 'uploads/';
        if (!is_dir($basePath . $uploadDir)) {
            mkdir($basePath . $uploadDir);
        }
        $dir = $uploadDir . $name . '/';
        if (!is_dir($basePath . $dir)) {
            mkdir($basePath . $dir);
        }
        $uploadPath = $dir . $date;
        if (!is_dir($basePath . $uploadPath)) {
            mkdir($basePath . $uploadPath);
        }
        return ['basePath' => $basePath, 'uploadPath' => $uploadPath];
    }

    /**
     * Translate certain characters
     * @param string $message
     * @param array $params
     * @return string translated message
     */
    public static function translateMessage($message, $params)
    {
        return is_array($params) ? strtr($message, $params) : $message;
    }

    /**
     * Get number dropDownList
     * @param integer $min
     * @param integer $max
     * @return array
     */
    public static function getNumberDropDownList($min, $max)
    {
        $list = [];
        for ($i = $min; $i <= $max; $i++) {
            $list[$i] = $i;
        }
        return $list;
    }

    /**
     * get header icon
     * @return string
     */
    public static function getHeaderIcon()
    {
        if (isset(Yii::$app->params['settings']['customTextIcon'])) {
            return Yii::$app->params['settings']['customTextIcon'];
        }
        return Html::img(Yii::$app->request->baseUrl . '/apple-touch-icon-72x72.png', ['height' => 24, 'alt' => Yii::$app->name]);
    }

    /**
     * get Timezone List
     */
    public static function getTimezoneList()
    {
        $zones = timezone_identifiers_list();
        $locations=[];
        foreach ($zones as $zone) {
            $zone = explode('/', $zone); // 0 => Continent, 1 => City

            // Only use "friendly" continent names
            if ($zone[0] == 'Africa' || $zone[0] == 'America' || $zone[0] == 'Antarctica' || $zone[0] == 'Arctic' || $zone[0] == 'Asia' || $zone[0] == 'Atlantic' || $zone[0] == 'Australia' || $zone[0] == 'Europe' || $zone[0] == 'Indian' || $zone[0] == 'Pacific') {
                if (isset($zone[1]) != '') {
                    $locations[$zone[0]][$zone[0] . '/' . $zone[1]] = str_replace('_', ' ', $zone[1]); // Creates array(DateTimeZone => 'Friendly name')
                }
            }
        }
        return $locations;
    }

    /**
     * get language for TinyMCE
     * @param $lang
     * @return string
     */
    public static function getTinyMCELang($lang)
    {
        $l = substr($lang, 0, 2);
        if ($l == 'en') {
            return 'en_GB';
        }
        return $l;
    }

    /**
     * get country array list
     * @return array list
     */
    public static function getCountryList()
    {
        return [
            'AF' => 'Afghanistan',
            'AX' => 'Åland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua & Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AC' => 'Ascension Island',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia & Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'IC' => 'Canary Islands',
            'CV' => 'Cape Verde',
            'BQ' => 'Caribbean Netherlands',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'EA' => 'Ceuta & Melilla',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo - Brazzaville',
            'CD' => 'Congo - Kinshasa',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Côte d’Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CW' => 'Curaçao',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DG' => 'Diego Garcia',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong SAR China',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'XK' => 'Kosovo',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macau SAR China',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar (Burma)',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'KP' => 'North Korea',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territories',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Réunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'São Tomé & Príncipe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SX' => 'Sint Maarten',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia & South Sandwich Islands',
            'KR' => 'South Korea',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'BL' => 'St. Barthélemy',
            'SH' => 'St. Helena',
            'KN' => 'St. Kitts & Nevis',
            'LC' => 'St. Lucia',
            'MF' => 'St. Martin',
            'PM' => 'St. Pierre & Miquelon',
            'VC' => 'St. Vincent & Grenadines',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad & Tobago',
            'TA' => 'Tristan da Cunha',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks & Caicos Islands',
            'TV' => 'Tuvalu',
            'UM' => 'U.S. Outlying Islands',
            'VI' => 'U.S. Virgin Islands',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis & Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];
    }

    /**
     * get country text
     * @param string $code country code
     * @return string country text
     */
    public static function getCountryText($code)
    {

        $list = self::getCountryList();
        if (!empty($code) && in_array($code, array_keys($list))) {
            return $list[$code];
        }
        return null;
    }

    /**
     * get gender list
     * @return array
     */
    public static function getGenderList()
    {
        return [
            0 => Yii::t('app', 'Male'),
            1 => Yii::t('app', 'Female'),
        ];
    }

    /**
     * get gender text
     * @param integer $gender
     * @return string gender text
     */
    public static function getGenderText($gender)
    {
        $list = self::getGenderList();
        if (is_numeric($gender) && in_array($gender, array_keys($list))) {
            return $list[$gender];
        }
        return null;
    }

    /**
     * Translate message
     * @param $module
     * @param $message
     * @return mixed
     */
    public static function t($module, $message)
    {
        return Yii::$app->getModule($module)->t($message);
    }

}
