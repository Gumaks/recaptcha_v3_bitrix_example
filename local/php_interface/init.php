<?
//подключаем бибилиотеку рекапчи
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/recaptcha/autoload.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/recaptcha/autoload.php");
//константы с ключами рекапчи
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php");
//подключение js по событию
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/g-racaptha.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/g-racaptha.php");

//функция обработки события добавления нового результата в веб формы
function checkGoogleCaptchaForm($formid, &$arFields, &$arrVALUES)
{
    global $APPLICATION;
    if (isset($_POST['g_recaptcha_token'])) {
        $gRecaptchaResponse = trim($_POST['g_recaptcha_token']);
        $recaptcha = new \ReCaptcha\ReCaptcha(RE_SEC_KEY);
        //проверка рекапчи
        $resp = $recaptcha->verify($gRecaptchaResponse);
        if (!$resp->isSuccess()) {
            $APPLICATION->ThrowException('Ваши действия нам кажутся подозрительными. Попробуйте перезагрузить страницу и повторно заполнить форму');
            //тормозим добавление если рекапча не прошла
        }
    } else {
        $APPLICATION->ThrowException('Ваши действия нам кажутся подозрительными. Попробуйте перезагрузить страницу и повторно заполнить форму');
        //тормозим добавление если рекапча не прошла
    }

}

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'form',
    'onBeforeResultAdd',
    'checkGoogleCaptchaForm'
);

