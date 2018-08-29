<?php

/* @var $isAjax bool */
/* @var $singleAddress EazyNewsletterEmailAddress */
/* @var $settings EazyNewsletterSettings */
/* @var $system EazyNewsletterSystem */

spl_autoload_register(function($class) {
    include EAZYROOTDIR . 'Classes/' . $class . '.php';
});


if ($isAjax) {
    try {

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $mail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $html = filter_var($_POST['html'], FILTER_VALIDATE_INT);
        $header = $_POST['header'];
        $body = $_POST['body'];
        $footer = $_POST['footer'];
        $automatic = filter_var($_POST['automatic'], FILTER_VALIDATE_INT);
        $pageID = filter_var($_POST['activationPageID'], FILTER_VALIDATE_INT);
        $deleteMailPageID = filter_var($_POST['deleteMailPageID'], FILTER_VALIDATE_INT);
        $sendTime = filter_var($_POST['sendTime'], FILTER_SANITIZE_STRING);

        $system->getSettings()->setEazyNewsletterName($name);
        $system->getSettings()->setEazyNewsletterMail($mail);
        $system->getSettings()->setEazyNewsletterHtml($html);
        $system->getSettings()->setEazyNewsletterCustomHtmlHeader($header);
        $system->getSettings()->setEazyNewsletterCustomHtmlBody($body);
        $system->getSettings()->setEazyNewsletterCustomHtmlFooter($footer);
        $system->getSettings()->setEazyNewsletterAutomatic($automatic);
        $system->getSettings()->setEazyNewsletterActivationPageID($pageID);
        $system->getSettings()->setEazyNewsletterSendTime($sendTime);
        $system->getSettings()->setEazyNewsletterDeleteMailPageID($deleteMailPageID);

        if ($system->getSettings()->updateSettings()) {
            echo __('Einstellungen erfolgreich gespeichert!', 'eazy_newsletter');
        } else {
            echo __('Einstellungen konnten nicht gespeichert werden!', 'eazy_newsletter');
        }
    } catch (Exception $ex) {
        if (EAZYLOGDATA) {
            EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
        }
    }
}
