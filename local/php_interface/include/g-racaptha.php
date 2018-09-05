<?php
/**
 * Created by PhpStorm.
 * User: gogolev
 * Date: 28.08.2018
 * Time: 15:03
 */
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnEpilog',
    'GRecapthaJsInit'
);
//а событие OnEpilog фешаем подключение js
function GRecapthaJsInit()
{
    global $APPLICATION;
        //подключаем js
        if (!CJSCore::IsExtRegistered('grecapthainit')) {
            CJSCore::RegisterExt("grecapthainit", Array(
                "js" => "/local/js/grecapthainit.js",
            ));
        }
        CJSCore::Init(array("grecapthainit"));
        //передаем в js ключ сайта
        $mess = array(
            'grecsitekey' => RE_SITE_KEY,
        );
        $APPLICATION->addheadstring('<script type="text/javascript">BX.message('.json_encode($mess, JSON_UNESCAPED_UNICODE).');</script>');
}