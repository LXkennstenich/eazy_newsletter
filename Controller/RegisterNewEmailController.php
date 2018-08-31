<?php

/**
 * Eazy Newsletter
 *
 * @package     eazy_newsletter
 * @author      Alexander Weese
 * @copyright   2018 Alexander Weese Webdesign
 * @license     GPL-3.0+
 */
/* @var $isAjax bool */
/* @var $singleAddress EazyNewsletterEmailAddress */
/* @var $settings EazyNewsletterSettings */
/* @var $system EazyNewsletterSystem */

spl_autoload_register(function($class) {
    include EAZYROOTDIR . 'Classes/' . $class . '.php';
});


if ($isAjax) {
    try {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
        $email2 = filter_var($_POST['email2'], FILTER_SANITIZE_STRING);
        $email3 = filter_var($_POST['email3'], FILTER_SANITIZE_STRING);

        $timeSend = new DateTime(date('H:i:s', $_POST['time']));
        $timeRequested = new DateTime(date('H:i:s', current_time('timestamp')));

        $interval = $timeSend->diff($timeRequested);

        $timeInSeconds = intval($interval->format('%s'));

        if ($timeInSeconds < 1 || $timeInSeconds > 300) {
            wp_die(__('Ihre E-Mail Addresse konnte nicht eingetragen werden! Bitte laden Sie die Seite neu!', 'eazy_newsletter'));
        }

        if ($email2 !== '' || $email3 !== '') {
            wp_die(__('Ihre E-Mail Addresse konnte nicht eingetragen werden!', 'eazy_newsletter'));
        }

        $duplicate = false;

        if ($system->mailExists($email)) {
            $arrayAddresses = $system->getSettings()->getEazyNewsletterAddresses();

            if (sizeof($arrayAddresses) > 0) {
                foreach ($arrayAddresses as $singleAddress) {
                    if ($singleAddress->getAddress() === $email) {
                        $duplicate = true;
                        echo __('Diese E-Mail wurde bereits eingetragen!', 'eazy_newsletter');
                    }
                }
            }

            if ($duplicate === false) {
                $mail = new EazyNewsletterEmailAddress(false, $email, bin2hex(openssl_random_pseudo_bytes(64)), current_time('timestamp'));
                $arrayAddresses[] = $mail;
                $system->getSettings()->setEazyNewsletterAddresses($arrayAddresses);

                if ($system->getSettings()->updateOption('eazy_newsletter_addresses', $arrayAddresses)) {
                    if ($mail->sendValidationMail()) {
                        echo __('Sie haben sich erfolgreich eingetragen!', 'eazy_newsletter');
                    }
                } else {
                    echo __('Ihre E-Mail Adresse konnte nicht eingetragen werden. Bitte versuchen Sie es erneut!', 'eazy_newsletter');
                }
            }
        } else {
            echo __('Bitte eine gültige E-Mail Adresse eintragen!', 'eazy_newsletter');
        }
    } catch (Exception $ex) {
        if (EAZYLOGDATA) {
            EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
        }
    }
}
