<?php

spl_autoload_register(function($class) {
    include EAZYROOTDIR . 'Classes/' . $class . '.php';
});

/* @var $isAjax bool */
/* @var $singleAddress EmailAddress */
/* @var $settings Settings */
/* @var $system System */

if ($isAjax) {
    try {

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $mail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $html = filter_var($_POST['html'], FILTER_VALIDATE_INT);
        $header = filter_var($_POST['header'], FILTER_DEFAULT);
        $body = filter_var($_POST['body'], FILTER_DEFAULT);
        $footer = filter_var($_POST['footer'], FILTER_DEFAULT);
        $automatic = filter_var($_POST['automatic'], FILTER_VALIDATE_INT);
        $pageID = filter_var($_POST['activationPageID'], FILTER_VALIDATE_INT);
        $sendTime = filter_var($_POST['sendTime'], FILTER_SANITIZE_STRING);

        $settings->setEazyNewsletterName($name);
        $settings->setEazyNewsletterMail($mail);
        $settings->setEazyNewsletterHtml($html);
        $settings->setEazyNewsletterCustomHtmlHeader($header);
        $settings->setEazyNewsletterCustomHtmlBody($body);
        $settings->setEazyNewsletterCustomHtmlFooter($footer);
        $settings->setEazyNewsletterAutomatic($automatic);
        $settings->setEazyNewsletterActivationPageID($pageID);
        $settings->setEazyNewsletterSendTime($sendTime);

        if ($settings->updateSettings()) {
            echo 'Einstellungen erfolgreich gespeichert!';
        } else {
            echo 'Einstellungen konnten nicht gespeichert werden!';
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
