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
        $settings = Settings::getUpdatetInstance();
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
        $arrayAddresses = $settings->getEazyNewsletterAddresses();

        $newArray = array();
        if (sizeof($arrayAddresses) > 0) {
            foreach ($arrayAddresses as $singleAddress) {
                if ($singleAddress->getAddress() === $email) {
                    continue;
                }

                $newArray[] = $singleAddress;
            }
        }

        if ($newArray !== $arrayAddresses) {
            $settings->setEazyNewsletterAddresses($newArray);
            if ($settings->updateSettings()) {
                echo 'E-Mail Addresse erfolgreich gelöscht!';
            } else {
                echo 'E-Mail Addresse konnte nicht gelöscht werden!';
            }
        } else {
            echo 'E-Mail Addresse wurde bereits gelöscht oder existiert nicht!';
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
