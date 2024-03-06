<?php

require_once('./vendor/autoload.php');
$params = require('config.php');

class Translate {

    /** @var \GoogleTranslate\Client */
    protected static $translate_api_client;

    public static function run($source_lang = 'en') {
        $base_path = __DIR__ . '/target/';
        $dirs = scandir($base_path);
        foreach ($dirs as $dir) {
            if ($dir == '.' || $dir == '..' || !is_dir($base_path . $dir)) {
                continue;
            }
            $module = $dir;
            $module_path = $base_path . $dir . '/';
            echo sprintf("Module path found [%s]\n", $module_path);
            $dirs2 = scandir($module_path);
            foreach ($dirs2 as $dir2) {
                if ($dir2 == '.' || $dir2 == '..' || !is_dir($module_path . $dir2)) {
                    continue;
                }
                $lang = $dir2;
                $module_lang_path = $module_path . $dir2 . '/';
                echo sprintf("Lang path found [%s]\n", $module_lang_path);
                $dirs3 = scandir($module_lang_path);
                foreach ($dirs3 as $dir3) {
                    if (!is_file($module_lang_path . $dir3) || strpos($dir3, '.translated') !== false) {
                        continue;
                    }
                    $full_path = $module_lang_path . $dir3;
                    $translated_path = $full_path . '.translated';
                    $skip_translation = $lang == $source_lang;
                    $array = require_once($full_path);
                    if ($skip_translation) {
                        echo sprintf("Skip translating [%s]\n", $full_path);
                    } else {
                        echo sprintf("Translating [%s] (%s entries)\n", $full_path, count($array));
                    }
                    $str = "<?php\n\nreturn [";
                    file_put_contents($translated_path, $str);
                    foreach ($array as $key => $value) {
                        if (!$value && !$skip_translation) {
                            exit;
                            $value = static::getTranslateApiClient()->translate($key, $lang, $source_lang);
                        }
                        $key = addslashes($key);
                        $value = addslashes($value);
                        file_put_contents($translated_path, "\n    '$key' => '$value',", FILE_APPEND);
                    }
                    file_put_contents($translated_path, "\n];", FILE_APPEND);
                    //Smoke test
                    include_once($translated_path);
                }
            }
        }
    }

    protected static function getTranslateApiClient() {
        if (!static::$translate_api_client) {
            global $params;
            static::$translate_api_client = new \GoogleTranslate\Client($params['googleapi_key']);
        }
        return static::$translate_api_client;
        
    }
}
