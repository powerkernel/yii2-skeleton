<?php

namespace common;

use common\models\Setting;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\Inflector;

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

        /* in module */
        if (!in_array(Yii::$app->controller->module->id, ['app-frontend', 'app-backend'])) {
            return (
                (in_array(Yii::$app->controller->module->id, $module) || in_array('*', $module)) &&
                (in_array(Yii::$app->controller->id, $controller) || in_array('*', $controller)) &&
                (in_array(Yii::$app->controller->action->id, $action) || in_array('*', $action))
            );
        }

        /* in frontend/backend */
        if (isset(Yii::$app->controller->id, Yii::$app->controller->action->id)) {
            return (
                (in_array('', $module) || in_array('*', $module)) &&
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
     * @param $params []
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
        if (in_array($value, [1, '1', 'YES', 'yes', true, 'true'], true)) {
            return Yii::t('app', 'Yes');
        }
        return Yii::t('app', 'No');
    }

    /**
     * Get Yes/No Option
     * @param string $type
     * @return array
     */
    public static function getYesNoOption($type='number')
    {
        if($type=='number'){
            return [
                '1' => Yii::t('app', 'Yes'),
                '0' => Yii::t('app', 'No')
            ];
        }
        return [
            'YES' => Yii::t('app', 'Yes'),
            'NO' => Yii::t('app', 'No')
        ];
    }


    /**
     * Grey Neuron Powered
     * @return string
     */
    public static function powered()
    {
        return 'Powered by <a target="_blank" href="https://powerkernel.com/" rel="external" title="Power Kernel">Power Kernel</a>';
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
        $locations = [];
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
     * get city list
     * @param string $country
     * @param null $state
     * @return array
     */
    public static function getCityList($country, $state=null){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require_once($file);
            return ArrayHelper::map($data['city'], 'id', 'name');
        }
        return [];
    }

    /**
     * get city text
     * @param string $country
     * @param string $id
     * @return mixed
     */
    public static function getCityText($country, $id){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require_once($file);
            $key = array_search($id, array_column($data['city'], 'id'));
            if($key){
                return $data['city'][$key]['name'];
            }
            return [];
        }
        return [];
    }

    /**
     * get state list
     * @param $country
     * @return array
     */
    public static function getStateList($country){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require($file);
            return ArrayHelper::map($data['state'], 'id', 'name');
        }
        return [];
    }



    /**
     * get district list
     * @param string $country
     * @param string $city
     * @return array
     */
    public static function getDistrictList($country, $city=null){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require_once($file);
            if($data){
                if($city){
                    $result = ArrayHelper::index($data['district'], 'id', 'id_city');
                    ArrayHelper::multisort($result[$city], 'name');
                    return $result[$city];
                }
                ArrayHelper::multisort($data['district'], 'name');
                return ArrayHelper::map($data['district'], 'id', 'name');
            }
            return [];
        }
        return [];
    }

    /**
     * get ward list
     * @param $country
     * @param null $district
     * @return array
     */
    public static function getWardList($country, $district=null){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require_once($file);
            if($data){
                if($district){
                    $result = ArrayHelper::index($data['ward'], 'id', 'id_district');
                    ArrayHelper::multisort($result[$district], 'name');
                    return $result[$district];
                }
                ArrayHelper::multisort($data['ward'], 'name');
                return ArrayHelper::map($data['ward'], 'id', 'name');
            }
            return [];
        }
        return [];
    }

    /**
     * get state text
     * @param $country
     * @param $id
     * @return array
     */
    public static function getDistrictText($country,$id){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require($file);
            if($data){
                $key = array_search($id, array_column($data['district'], 'id'));
                if($key){
                    return $data['district'][$key]['name'];
                }
            }
            return [];
        }
        return [];
    }

    /**
     * get ward text
     * @param $country
     * @param $id
     * @return array
     */
    public static function getWardText($country, $id){
        $file=Yii::$aliases['@common'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'location'.DIRECTORY_SEPARATOR.$country.'.php';
        if(file_exists($file)){
            $data=require($file);
            if($data){
                $key = array_search($id, array_column($data['ward'], 'id'));
                if($key){
                    return $data['ward'][$key]['name'];
                }
            }
            return [];
        }
        return [];
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


    /**
     * @param array $only
     * @return array
     */
    public static function getLocaleList($only = [])
    {
        $list = [
            'af-NA' => 'Afrikaans (Namibia)',
            'af-ZA' => 'Afrikaans (South Africa)',
            'ak-GH' => 'Akan (Ghana)',
            'sq-AL' => 'Albanian (Albania)',
            'sq-XK' => 'Albanian (Kosovo)',
            'sq-MK' => 'Albanian (Macedonia)',
            'am-ET' => 'Amharic (Ethiopia)',
            'ar-DZ' => 'Arabic (Algeria)',
            'ar-BH' => 'Arabic (Bahrain)',
            'ar-TD' => 'Arabic (Chad)',
            'ar-KM' => 'Arabic (Comoros)',
            'ar-DJ' => 'Arabic (Djibouti)',
            'ar-EG' => 'Arabic (Egypt)',
            'ar-ER' => 'Arabic (Eritrea)',
            'ar-IQ' => 'Arabic (Iraq)',
            'ar-IL' => 'Arabic (Israel)',
            'ar-JO' => 'Arabic (Jordan)',
            'ar-KW' => 'Arabic (Kuwait)',
            'ar-LB' => 'Arabic (Lebanon)',
            'ar-LY' => 'Arabic (Libya)',
            'ar-MR' => 'Arabic (Mauritania)',
            'ar-MA' => 'Arabic (Morocco)',
            'ar-OM' => 'Arabic (Oman)',
            'ar-PS' => 'Arabic (Palestinian Territories)',
            'ar-QA' => 'Arabic (Qatar)',
            'ar-SA' => 'Arabic (Saudi Arabia)',
            'ar-SO' => 'Arabic (Somalia)',
            'ar-SS' => 'Arabic (South Sudan)',
            'ar-SD' => 'Arabic (Sudan)',
            'ar-SY' => 'Arabic (Syria)',
            'ar-TN' => 'Arabic (Tunisia)',
            'ar-AE' => 'Arabic (United Arab Emirates)',
            'ar-EH' => 'Arabic (Western Sahara)',
            'ar-YE' => 'Arabic (Yemen)',
            'hy-AM' => 'Armenian (Armenia)',
            'as-IN' => 'Assamese (India)',
            'az-AZ' => 'Azerbaijani (Azerbaijan)',
            'eu-ES' => 'Basque (Spain)',
            'be-BY' => 'Belarusian (Belarus)',
            'bn-BD' => 'Bengali (Bangladesh)',
            'bn-IN' => 'Bengali (India)',
            'bs-BA' => 'Bosnian (Bosnia & Herzegovina)',
            'br-FR' => 'Breton (France)',
            'bg-BG' => 'Bulgarian (Bulgaria)',
            'my-MM' => 'Burmese (Myanmar (Burma))',
            'ca-AD' => 'Catalan (Andorra)',
            'ca-FR' => 'Catalan (France)',
            'ca-IT' => 'Catalan (Italy)',
            'ca-ES' => 'Catalan (Spain)',
            'zh-CN' => 'Chinese (China)',
            'zh-HK' => 'Chinese (Hong Kong SAR China)',
            'zh-MO' => 'Chinese (Macau SAR China)',
            'zh-SG' => 'Chinese (Singapore)',
            'zh-TW' => 'Chinese (Taiwan)',
            'kw-GB' => 'Cornish (United Kingdom)',
            'hr-BA' => 'Croatian (Bosnia & Herzegovina)',
            'hr-HR' => 'Croatian (Croatia)',
            'cs-CZ' => 'Czech (Czech Republic)',
            'da-DK' => 'Danish (Denmark)',
            'da-GL' => 'Danish (Greenland)',
            'nl-AW' => 'Dutch (Aruba)',
            'nl-BE' => 'Dutch (Belgium)',
            'nl-BQ' => 'Dutch (Caribbean Netherlands)',
            'nl-CW' => 'Dutch (Curaçao)',
            'nl-NL' => 'Dutch (Netherlands)',
            'nl-SX' => 'Dutch (Sint Maarten)',
            'nl-SR' => 'Dutch (Suriname)',
            'dz-BT' => 'Dzongkha (Bhutan)',
            'en-AS' => 'English (American Samoa)',
            'en-AI' => 'English (Anguilla)',
            'en-AG' => 'English (Antigua & Barbuda)',
            'en-AU' => 'English (Australia)',
            'en-BS' => 'English (Bahamas)',
            'en-BB' => 'English (Barbados)',
            'en-BE' => 'English (Belgium)',
            'en-BZ' => 'English (Belize)',
            'en-BM' => 'English (Bermuda)',
            'en-BW' => 'English (Botswana)',
            'en-IO' => 'English (British Indian Ocean Territory)',
            'en-VG' => 'English (British Virgin Islands)',
            'en-CM' => 'English (Cameroon)',
            'en-CA' => 'English (Canada)',
            'en-KY' => 'English (Cayman Islands)',
            'en-CX' => 'English (Christmas Island)',
            'en-CC' => 'English (Cocos (Keeling) Islands)',
            'en-CK' => 'English (Cook Islands)',
            'en-DG' => 'English (Diego Garcia)',
            'en-DM' => 'English (Dominica)',
            'en-ER' => 'English (Eritrea)',
            'en-FK' => 'English (Falkland Islands)',
            'en-FJ' => 'English (Fiji)',
            'en-GM' => 'English (Gambia)',
            'en-GH' => 'English (Ghana)',
            'en-GI' => 'English (Gibraltar)',
            'en-GD' => 'English (Grenada)',
            'en-GU' => 'English (Guam)',
            'en-GG' => 'English (Guernsey)',
            'en-GY' => 'English (Guyana)',
            'en-HK' => 'English (Hong Kong SAR China)',
            'en-IN' => 'English (India)',
            'en-IE' => 'English (Ireland)',
            'en-IM' => 'English (Isle of Man)',
            'en-JM' => 'English (Jamaica)',
            'en-JE' => 'English (Jersey)',
            'en-KE' => 'English (Kenya)',
            'en-KI' => 'English (Kiribati)',
            'en-LS' => 'English (Lesotho)',
            'en-LR' => 'English (Liberia)',
            'en-MO' => 'English (Macau SAR China)',
            'en-MG' => 'English (Madagascar)',
            'en-MW' => 'English (Malawi)',
            'en-MY' => 'English (Malaysia)',
            'en-MT' => 'English (Malta)',
            'en-MH' => 'English (Marshall Islands)',
            'en-MU' => 'English (Mauritius)',
            'en-FM' => 'English (Micronesia)',
            'en-MS' => 'English (Montserrat)',
            'en-NA' => 'English (Namibia)',
            'en-NR' => 'English (Nauru)',
            'en-NZ' => 'English (New Zealand)',
            'en-NG' => 'English (Nigeria)',
            'en-NU' => 'English (Niue)',
            'en-NF' => 'English (Norfolk Island)',
            'en-MP' => 'English (Northern Mariana Islands)',
            'en-PK' => 'English (Pakistan)',
            'en-PW' => 'English (Palau)',
            'en-PG' => 'English (Papua New Guinea)',
            'en-PH' => 'English (Philippines)',
            'en-PN' => 'English (Pitcairn Islands)',
            'en-PR' => 'English (Puerto Rico)',
            'en-RW' => 'English (Rwanda)',
            'en-WS' => 'English (Samoa)',
            'en-SC' => 'English (Seychelles)',
            'en-SL' => 'English (Sierra Leone)',
            'en-SG' => 'English (Singapore)',
            'en-SX' => 'English (Sint Maarten)',
            'en-SB' => 'English (Solomon Islands)',
            'en-ZA' => 'English (South Africa)',
            'en-SS' => 'English (South Sudan)',
            'en-SH' => 'English (St. Helena)',
            'en-KN' => 'English (St. Kitts & Nevis)',
            'en-LC' => 'English (St. Lucia)',
            'en-VC' => 'English (St. Vincent & Grenadines)',
            'en-SD' => 'English (Sudan)',
            'en-SZ' => 'English (Swaziland)',
            'en-TZ' => 'English (Tanzania)',
            'en-TK' => 'English (Tokelau)',
            'en-TO' => 'English (Tonga)',
            'en-TT' => 'English (Trinidad & Tobago)',
            'en-TC' => 'English (Turks & Caicos Islands)',
            'en-TV' => 'English (Tuvalu)',
            'en-UM' => 'English (U.S. Outlying Islands)',
            'en-VI' => 'English (U.S. Virgin Islands)',
            'en-UG' => 'English (Uganda)',
            'en-GB' => 'English (United Kingdom)',
            'en-US' => 'English (United States)',
            'en-VU' => 'English (Vanuatu)',
            'en-ZM' => 'English (Zambia)',
            'en-ZW' => 'English (Zimbabwe)',
            'et-EE' => 'Estonian (Estonia)',
            'ee-GH' => 'Ewe (Ghana)',
            'ee-TG' => 'Ewe (Togo)',
            'fo-FO' => 'Faroese (Faroe Islands)',
            'fi-FI' => 'Finnish (Finland)',
            'fr-DZ' => 'French (Algeria)',
            'fr-BE' => 'French (Belgium)',
            'fr-BJ' => 'French (Benin)',
            'fr-BF' => 'French (Burkina Faso)',
            'fr-BI' => 'French (Burundi)',
            'fr-CM' => 'French (Cameroon)',
            'fr-CA' => 'French (Canada)',
            'fr-CF' => 'French (Central African Republic)',
            'fr-TD' => 'French (Chad)',
            'fr-KM' => 'French (Comoros)',
            'fr-CG' => 'French (Congo - Brazzaville)',
            'fr-CD' => 'French (Congo - Kinshasa)',
            'fr-CI' => 'French (Côte d’Ivoire)',
            'fr-DJ' => 'French (Djibouti)',
            'fr-GQ' => 'French (Equatorial Guinea)',
            'fr-FR' => 'French (France)',
            'fr-GF' => 'French (French Guiana)',
            'fr-PF' => 'French (French Polynesia)',
            'fr-GA' => 'French (Gabon)',
            'fr-GP' => 'French (Guadeloupe)',
            'fr-GN' => 'French (Guinea)',
            'fr-HT' => 'French (Haiti)',
            'fr-LU' => 'French (Luxembourg)',
            'fr-MG' => 'French (Madagascar)',
            'fr-ML' => 'French (Mali)',
            'fr-MQ' => 'French (Martinique)',
            'fr-MR' => 'French (Mauritania)',
            'fr-MU' => 'French (Mauritius)',
            'fr-YT' => 'French (Mayotte)',
            'fr-MC' => 'French (Monaco)',
            'fr-MA' => 'French (Morocco)',
            'fr-NC' => 'French (New Caledonia)',
            'fr-NE' => 'French (Niger)',
            'fr-RE' => 'French (Réunion)',
            'fr-RW' => 'French (Rwanda)',
            'fr-SN' => 'French (Senegal)',
            'fr-SC' => 'French (Seychelles)',
            'fr-BL' => 'French (St. Barthélemy)',
            'fr-MF' => 'French (St. Martin)',
            'fr-PM' => 'French (St. Pierre & Miquelon)',
            'fr-CH' => 'French (Switzerland)',
            'fr-SY' => 'French (Syria)',
            'fr-TG' => 'French (Togo)',
            'fr-TN' => 'French (Tunisia)',
            'fr-VU' => 'French (Vanuatu)',
            'fr-WF' => 'French (Wallis & Futuna)',
            'ff-CM' => 'Fulah (Cameroon)',
            'ff-GN' => 'Fulah (Guinea)',
            'ff-MR' => 'Fulah (Mauritania)',
            'ff-SN' => 'Fulah (Senegal)',
            'gl-ES' => 'Galician (Spain)',
            'lg-UG' => 'Ganda (Uganda)',
            'ka-GE' => 'Georgian (Georgia)',
            'de-AT' => 'German (Austria)',
            'de-BE' => 'German (Belgium)',
            'de-DE' => 'German (Germany)',
            'de-LI' => 'German (Liechtenstein)',
            'de-LU' => 'German (Luxembourg)',
            'de-CH' => 'German (Switzerland)',
            'el-CY' => 'Greek (Cyprus)',
            'el-GR' => 'Greek (Greece)',
            'gu-IN' => 'Gujarati (India)',
            'ha-GH' => 'Hausa (Ghana)',
            'ha-NE' => 'Hausa (Niger)',
            'ha-NG' => 'Hausa (Nigeria)',
            'he-IL' => 'Hebrew (Israel)',
            'hi-IN' => 'Hindi (India)',
            'hu-HU' => 'Hungarian (Hungary)',
            'is-IS' => 'Icelandic (Iceland)',
            'ig-NG' => 'Igbo (Nigeria)',
            'id-ID' => 'Indonesian (Indonesia)',
            'ga-IE' => 'Irish (Ireland)',
            'it-IT' => 'Italian (Italy)',
            'it-SM' => 'Italian (San Marino)',
            'it-CH' => 'Italian (Switzerland)',
            'ja-JP' => 'Japanese (Japan)',
            'kl-GL' => 'Kalaallisut (Greenland)',
            'kn-IN' => 'Kannada (India)',
            'ks-IN' => 'Kashmiri (India)',
            'kk-KZ' => 'Kazakh (Kazakhstan)',
            'km-KH' => 'Khmer (Cambodia)',
            'ki-KE' => 'Kikuyu (Kenya)',
            'rw-RW' => 'Kinyarwanda (Rwanda)',
            'ko-KP' => 'Korean (North Korea)',
            'ko-KR' => 'Korean (South Korea)',
            'ky-KG' => 'Kyrgyz (Kyrgyzstan)',
            'lo-LA' => 'Lao (Laos)',
            'lv-LV' => 'Latvian (Latvia)',
            'ln-AO' => 'Lingala (Angola)',
            'ln-CF' => 'Lingala (Central African Republic)',
            'ln-CG' => 'Lingala (Congo - Brazzaville)',
            'ln-CD' => 'Lingala (Congo - Kinshasa)',
            'lt-LT' => 'Lithuanian (Lithuania)',
            'lu-CD' => 'Luba-Katanga (Congo - Kinshasa)',
            'lb-LU' => 'Luxembourgish (Luxembourg)',
            'mk-MK' => 'Macedonian (Macedonia)',
            'mg-MG' => 'Malagasy (Madagascar)',
            'ms-BN' => 'Malay (Brunei)',
            'ms-MY' => 'Malay (Malaysia)',
            'ms-SG' => 'Malay (Singapore)',
            'ml-IN' => 'Malayalam (India)',
            'mt-MT' => 'Maltese (Malta)',
            'gv-IM' => 'Manx (Isle of Man)',
            'mr-IN' => 'Marathi (India)',
            'mn-MN' => 'Mongolian (Mongolia)',
            'ne-IN' => 'Nepali (India)',
            'ne-NP' => 'Nepali (Nepal)',
            'nd-ZW' => 'North Ndebele (Zimbabwe)',
            'se-FI' => 'Northern Sami (Finland)',
            'se-NO' => 'Northern Sami (Norway)',
            'se-SE' => 'Northern Sami (Sweden)',
            'no-NO' => 'Norwegian (Norway)',
            'nb-NO' => 'Norwegian Bokmål (Norway)',
            'nb-SJ' => 'Norwegian Bokmål (Svalbard & Jan Mayen)',
            'nn-NO' => 'Norwegian Nynorsk (Norway)',
            'or-IN' => 'Oriya (India)',
            'om-ET' => 'Oromo (Ethiopia)',
            'om-KE' => 'Oromo (Kenya)',
            'os-GE' => 'Ossetic (Georgia)',
            'os-RU' => 'Ossetic (Russia)',
            'ps-AF' => 'Pashto (Afghanistan)',
            'fa-AF' => 'Persian (Afghanistan)',
            'fa-IR' => 'Persian (Iran)',
            'pl-PL' => 'Polish (Poland)',
            'pt-AO' => 'Portuguese (Angola)',
            'pt-BR' => 'Portuguese (Brazil)',
            'pt-CV' => 'Portuguese (Cape Verde)',
            'pt-GW' => 'Portuguese (Guinea-Bissau)',
            'pt-MO' => 'Portuguese (Macau SAR China)',
            'pt-MZ' => 'Portuguese (Mozambique)',
            'pt-PT' => 'Portuguese (Portugal)',
            'pt-ST' => 'Portuguese (São Tomé & Príncipe)',
            'pt-TL' => 'Portuguese (Timor-Leste)',
            'pa-IN' => 'Punjabi (India)',
            'pa-PK' => 'Punjabi (Pakistan)',
            'qu-BO' => 'Quechua (Bolivia)',
            'qu-EC' => 'Quechua (Ecuador)',
            'qu-PE' => 'Quechua (Peru)',
            'ro-MD' => 'Romanian (Moldova)',
            'ro-RO' => 'Romanian (Romania)',
            'rm-CH' => 'Romansh (Switzerland)',
            'rn-BI' => 'Rundi (Burundi)',
            'ru-BY' => 'Russian (Belarus)',
            'ru-KZ' => 'Russian (Kazakhstan)',
            'ru-KG' => 'Russian (Kyrgyzstan)',
            'ru-MD' => 'Russian (Moldova)',
            'ru-RU' => 'Russian (Russia)',
            'ru-UA' => 'Russian (Ukraine)',
            'sg-CF' => 'Sango (Central African Republic)',
            'gd-GB' => 'Scottish Gaelic (United Kingdom)',
            'sr-BA' => 'Serbian (Bosnia & Herzegovina)',
            'sr-XK' => 'Serbian (Kosovo)',
            'sr-ME' => 'Serbian (Montenegro)',
            'sr-RS' => 'Serbian (Serbia)',
            'sh-BA' => 'Serbo-Croatian (Bosnia & Herzegovina)',
            'sn-ZW' => 'Shona (Zimbabwe)',
            'ii-CN' => 'Sichuan Yi (China)',
            'si-LK' => 'Sinhala (Sri Lanka)',
            'sk-SK' => 'Slovak (Slovakia)',
            'sl-SI' => 'Slovenian (Slovenia)',
            'so-DJ' => 'Somali (Djibouti)',
            'so-ET' => 'Somali (Ethiopia)',
            'so-KE' => 'Somali (Kenya)',
            'so-SO' => 'Somali (Somalia)',
            'es-AR' => 'Spanish (Argentina)',
            'es-BO' => 'Spanish (Bolivia)',
            'es-IC' => 'Spanish (Canary Islands)',
            'es-EA' => 'Spanish (Ceuta & Melilla)',
            'es-CL' => 'Spanish (Chile)',
            'es-CO' => 'Spanish (Colombia)',
            'es-CR' => 'Spanish (Costa Rica)',
            'es-CU' => 'Spanish (Cuba)',
            'es-DO' => 'Spanish (Dominican Republic)',
            'es-EC' => 'Spanish (Ecuador)',
            'es-SV' => 'Spanish (El Salvador)',
            'es-GQ' => 'Spanish (Equatorial Guinea)',
            'es-GT' => 'Spanish (Guatemala)',
            'es-HN' => 'Spanish (Honduras)',
            'es-MX' => 'Spanish (Mexico)',
            'es-NI' => 'Spanish (Nicaragua)',
            'es-PA' => 'Spanish (Panama)',
            'es-PY' => 'Spanish (Paraguay)',
            'es-PE' => 'Spanish (Peru)',
            'es-PH' => 'Spanish (Philippines)',
            'es-PR' => 'Spanish (Puerto Rico)',
            'es-ES' => 'Spanish (Spain)',
            'es-US' => 'Spanish (United States)',
            'es-UY' => 'Spanish (Uruguay)',
            'es-VE' => 'Spanish (Venezuela)',
            'sw-KE' => 'Swahili (Kenya)',
            'sw-TZ' => 'Swahili (Tanzania)',
            'sw-UG' => 'Swahili (Uganda)',
            'sv-AX' => 'Swedish (Åland Islands)',
            'sv-FI' => 'Swedish (Finland)',
            'sv-SE' => 'Swedish (Sweden)',
            'tl-PH' => 'Tagalog (Philippines)',
            'ta-IN' => 'Tamil (India)',
            'ta-MY' => 'Tamil (Malaysia)',
            'ta-SG' => 'Tamil (Singapore)',
            'ta-LK' => 'Tamil (Sri Lanka)',
            'te-IN' => 'Telugu (India)',
            'th-TH' => 'Thai (Thailand)',
            'bo-CN' => 'Tibetan (China)',
            'bo-IN' => 'Tibetan (India)',
            'ti-ER' => 'Tigrinya (Eritrea)',
            'ti-ET' => 'Tigrinya (Ethiopia)',
            'to-TO' => 'Tongan (Tonga)',
            'tr-CY' => 'Turkish (Cyprus)',
            'tr-TR' => 'Turkish (Turkey)',
            'uk-UA' => 'Ukrainian (Ukraine)',
            'ur-IN' => 'Urdu (India)',
            'ur-PK' => 'Urdu (Pakistan)',
            'ug-CN' => 'Uyghur (China)',
            'uz-AF' => 'Uzbek (Afghanistan)',
            'uz-UZ' => 'Uzbek (Uzbekistan)',
            'vi-VN' => 'Vietnamese (Vietnam)',
            'cy-GB' => 'Welsh (United Kingdom)',
            'fy-NL' => 'Western Frisian (Netherlands)',
            'yo-BJ' => 'Yoruba (Benin)',
            'yo-NG' => 'Yoruba (Nigeria)',
        ];
        if (!empty($only)) {
            $new=[];
            foreach ($only as $l) {
                if(isset($list[$l])){
                    $new[$l] = $list[$l];
                }
            }
            return $new;
        }
        return $list;
    }

    /**
     * check if reCaptcha enabled
     * @return bool
     */
    public static function isReCaptchaEnabled()
    {
        $rcKey = Setting::getValue('reCaptchaKey');
        $rcSecret = Setting::getValue('reCaptchaSecret');
        if (!empty($rcKey) && !empty($rcSecret)) {
            return true;
        }
        return false;
    }

    /**
     * Disable show addthis
     * @return bool
     */
    public static function isInMemberAreaPage()
    {
        $a = [
            'SiteError',
            'SiteSearch',

            'AccountLogin',
            'AccountReset',
            'AccountSignup',
            'AccountIndex',
            'AccountEmail',
            'AccountPhone',
            'AccountPassword',
            'AccountLinked',
            'AccountResetConfirm',
            'AccountEmailConfirm',

            'BlogManage',
            'BlogCreate',
            'BlogUpdate'
        ];


        if (in_array(Core::getMCA(), $a)) {
            return true;
        }
        return false;
    }

    /**
     * get current MCA
     * @return string
     */
    public static function getMCA()
    {
        $m = Yii::$app->controller->module->id;
        if (in_array($m, ['app-frontend', 'app-backend'])) {
            $m = '';
        };
        $c=Yii::$app->controller->id;
        $a=Yii::$app->controller->action->id;


        return Inflector::id2camel($m) . Inflector::id2camel($c) . Inflector::id2camel($a);
    }

    /**
     * Get US States List
     * @return array
     */
    public static function getUSStateList()
    {
        return [
            'AL'=>'Alabama',
            'AK'=>'Alaska',
            'AZ'=>'Arizona',
            'AR'=>'Arkansas',
            'CA'=>'California',
            'CO'=>'Colorado',
            'CT'=>'Connecticut',
            'DE'=>'Delaware',
            'DC'=>'District Of Columbia',
            'FL'=>'Florida',
            'GA'=>'Georgia',
            'HI'=>'Hawaii',
            'ID'=>'Idaho',
            'IL'=>'Illinois',
            'IN'=>'Indiana',
            'IA'=>'Iowa',
            'KS'=>'Kansas',
            'KY'=>'Kentucky',
            'LA'=>'Louisiana',
            'ME'=>'Maine',
            'MD'=>'Maryland',
            'MA'=>'Massachusetts',
            'MI'=>'Michigan',
            'MN'=>'Minnesota',
            'MS'=>'Mississippi',
            'MO'=>'Missouri',
            'MT'=>'Montana',
            'NE'=>'Nebraska',
            'NV'=>'Nevada',
            'NH'=>'New Hampshire',
            'NJ'=>'New Jersey',
            'NM'=>'New Mexico',
            'NY'=>'New York',
            'NC'=>'North Carolina',
            'ND'=>'North Dakota',
            'OH'=>'Ohio',
            'OK'=>'Oklahoma',
            'OR'=>'Oregon',
            'PA'=>'Pennsylvania',
            'RI'=>'Rhode Island',
            'SC'=>'South Carolina',
            'SD'=>'South Dakota',
            'TN'=>'Tennessee',
            'TX'=>'Texas',
            'UT'=>'Utah',
            'VT'=>'Vermont',
            'VA'=>'Virginia',
            'WA'=>'Washington',
            'WV'=>'West Virginia',
            'WI'=>'Wisconsin',
            'WY'=>'Wyoming'
        ];
    }

    /**
     * @return bool
     */
    public static function isLocalhost(){
        return file_exists(__DIR__.'/config/localhost.php');
    }

    /**
     * @return array
     */
    public static function getCurrencyList(){
        $currencies = array("AFA","ALL","DZD","USD","EUR","AOA","XCD","NOK","XCD","ARA","AMD","AWG","AUD","EUR","AZM","BSD","BHD","BDT","BBD","BYR","EUR","BZD","XAF","BMD","BTN","BOB","BAM","BWP","NOK","BRL","GBP","BND","BGN","XAF","BIF","KHR","XAF","CAD","CVE","KYD","XAF","XAF","CLF","CNY","AUD","AUD","COP","KMF","CDZ","XAF","NZD","CRC","HRK","CUP","EUR","CZK","DKK","DJF","XCD","DOP","TPE","USD","EGP","USD","XAF","ERN","EEK","ETB","FKP","DKK","FJD","EUR","EUR","EUR","EUR","XPF","EUR","XAF","GMD","GEL","EUR","GHC","GIP","EUR","DKK","XCD","EUR","USD","GTQ","GNS","GWP","GYD","HTG","AUD","EUR","HNL","HKD","HUF","ISK","INR","IDR","IRR","IQD","EUR","ILS","EUR","XAF","JMD","JPY","JOD","KZT","KES","AUD","KPW","KRW","KWD","KGS","LAK","LVL","LBP","LSL","LRD","LYD","CHF","LTL","EUR","MOP","MKD","MGF","MWK","MYR","MVR","XAF","EUR","USD","EUR","MRO","MUR","EUR","MXN","USD","MDL","EUR","MNT","XCD","MAD","MZM","MMK","NAD","AUD","NPR","EUR","ANG","XPF","NZD","NIC","XOF","NGN","NZD","AUD","USD","NOK","OMR","PKR","USD","PAB","PGK","PYG","PEI","PHP","NZD","PLN","EUR","USD","QAR","EUR","ROL","RUB","RWF","XCD","XCD","XCD","WST","EUR","STD","SAR","XOF","EUR","SCR","SLL","SGD","EUR","EUR","SBD","SOS","ZAR","GBP","EUR","LKR","SHP","EUR","SDG","SRG","NOK","SZL","SEK","CHF","SYP","TWD","TJR","TZS","THB","XAF","NZD","TOP","TTD","TND","TRY","TMM","USD","AUD","UGS","UAH","SUR","AED","GBP","USD","USD","UYU","UZS","VUV","VEF","VND","USD","USD","XPF","XOF","MAD","ZMK","USD");
        return array_combine($currencies, $currencies);
    }


}
