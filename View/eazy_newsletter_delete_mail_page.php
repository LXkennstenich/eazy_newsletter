<?php
/*
  Template Name: Activation Page
  Template Post Type: page
 */

/* @var $singleAddress EmailAddress */
/* @var $settings Settings */

if (!defined('ABSPATH')) {
    die();
}

try {
    $settings = Settings::getUpdatetInstance();
    $action = null;
    $token = null;
    $address = null;
    $mailDeleted = false;
    $newArray = array();
    $unsubscribed = false;

    if (isset($_GET['a']) && isset($_GET['tk']) && isset($_GET['u'])) {
        $action = filter_var($_GET['a'], FILTER_SANITIZE_STRING);
        $token = filter_var($_GET['tk'], FILTER_SANITIZE_STRING);
        $address = filter_var($_GET['u'], FILTER_SANITIZE_STRING);
    }

    get_header();

    if ($action === 'delete') {

        $addressesArray = $settings->getEazyNewsletterAddresses();

        if (sizeof($addressesArray) > 0) {
            foreach ($addressesArray as $singleAddress) {
                if ($singleAddress->getAddress() === $address) {
                    if ($singleAddress->getToken() === $token && $singleAddress->isActive()) {
                        $mailDeleted = true;
                        continue;
                    }
                }

                $newArray[] = $singleAddress;
            }

            $settings->setEazyNewsletterAddresses($newArray);
            $settingsUpdated = $settings->updateSettings();
            $settings = Settings::getUpdatetInstance();
            $arrayChanged = $settings->getEazyNewsletterAddresses() == $addressesArray ? false : true;


            if ($settingsUpdated && $arrayChanged && $mailDeleted) {
                $unsubscribed = true;
            }
        }
    }
} catch (Exception $ex) {
    if (EAZYLOGDATA) {
        System::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
    }
}
?>

<?php if ($unsubscribed === true) { ?>
    <div class="eazy-newsletter-activation-message">
        <p><?php echo __('Sie haben sich erfolgreich aus unserem Newsletter ausgetragen', 'eazy_newsletter'); ?></p>
    </div>
<?php } else { ?>
    <div class="eazy-newsletter-activation-message">
        <p><?php echo __('Ihre E-Mail Adresse konnte nicht gelöscht werden', 'eazy_newsletter'); ?></p>
    </div>
<?php } ?>





<?php get_footer(); ?>

<?php wp_die(); ?>