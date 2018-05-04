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
        $time = filter_var($_POST['time'], FILTER_DEFAULT);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
        $email2 = filter_var($_POST['email2'], FILTER_SANITIZE_STRING);
        $email3 = filter_var($_POST['email3'], FILTER_SANITIZE_STRING);

        $currentTime = current_time('timestamp');
        $totalTime = $currentTime - $time;


        if ($totalTime < 1 || $totalTime > 1800) {
            wp_die('Ihre E-Mail Addresse konnte nicht eingetragen werden! Bitte laden Sie die Seite neu!');
        }

        if ($email2 !== '' || $email3 !== '') {
            wp_die('Ihre E-Mail Addresse konnte nicht eingetragen werden!');
        }

        $duplicate = false;
        $eMail = $email;
        $arrayAddress = explode('@', $eMail);
        $serverAddress = array_pop($arrayAddress);

        if (!checkdnsrr($serverAddress, 'MX')) {
            $arrayAddresses = $settings->getEazyNewsletterAddresses();

            if (sizeof($arrayAddresses) > 0) {
                foreach ($arrayAddresses as $singleAddress) {
                    if ($singleAddress->getAddress() === $email) {
                        $duplicate = true;
                        echo 'Diese E-Mail wurde bereits eingetragen!';
                    }
                }
            }

            if ($duplicate === false) {
                $mail = new EmailAddress(false, $email, bin2hex(openssl_random_pseudo_bytes(64)), current_time('timestamp'));
                $arrayAddresses[] = $mail;
                $settings->setEazyNewsletterAddresses($arrayAddresses);

                if ($settings->updateOption('eazy_newsletter_addresses', $arrayAddresses)) {
                    if ($mail->sendValidationMail()) {
                        echo 'Sie haben sich erfolgreich eingetragen!';
                    }
                } else {
                    echo 'Ihre E-Mail Adresse konnte nicht eingetragen werden. Bitte versuchen Sie es erneut!';
                }
            }
        } else {
            echo 'Bitte eine gültige E-Mail Adresse eintragen!';
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
