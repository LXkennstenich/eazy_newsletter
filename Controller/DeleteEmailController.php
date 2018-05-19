<?php

/* @var $isAjax bool */
/* @var $singleAddress EmailAddress */
/* @var $settings Settings */
/* @var $system System */

spl_autoload_register(function($class) {
    include EAZYROOTDIR . 'Classes/' . $class . '.php';
});


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
                echo __('E-Mail Addresse erfolgreich gelöscht!', 'eazy_newsletter');
            } else {
                echo __('E-Mail Addresse konnte nicht gelöscht werden!', 'eazy_newsletter');
            }
        } else {
            echo __('E-Mail Addresse wurde bereits gelöscht oder existiert nicht!', 'eazy_newsletter');
        }
    } catch (Exception $ex) {
        if (EAZYLOGDATA) {
            System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
        }
    }
}
