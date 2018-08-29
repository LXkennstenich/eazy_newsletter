<?php
/*
  Template Name: Activation Page
  Template Post Type: page
 */

/* @var $singleAddress EazyNewsletterEmailAddress */
/* @var $settings EazyNewsletterSettings */

if (!defined('ABSPATH')) {
    die();
}

try {
    $settings = EazyNewsletterSettings::getUpdatetInstance();
    $activation = false;
    $overTime = false;
    $validation = false;
    $action = null;
    $token = null;
    $address = null;


    if (isset($_GET['a']) && isset($_GET['tk']) && isset($_GET['u'])) {
        $action = filter_var($_GET['a'], FILTER_SANITIZE_STRING);
        $token = filter_var($_GET['tk'], FILTER_SANITIZE_STRING);
        $address = filter_var($_GET['u'], FILTER_SANITIZE_STRING);
    }

    $addressesArray = $settings->getEazyNewsletterAddresses();
    $newArray = array();

    get_header();


    if ($action === 'activate') {

        $maxTimeStamp = current_time('timestamp') + 86400;

        if (sizeof($addressesArray) > 0) {
            foreach ($addressesArray as $singleAddress) {
                if ($singleAddress->getAddress() === $address) {
                    if ($singleAddress->getTimestamp() <= $maxTimeStamp) {
                        if ($singleAddress->getToken() === $token && $singleAddress->isActive() == false) {
                            $singleAddress->setToken(bin2hex(openssl_random_pseudo_bytes(64)));
                            $singleAddress->setActive(true);
                            $validation = true;
                        }
                    } else {
                        $overTime = true;
                        continue;
                    }
                }

                $newArray[] = $singleAddress;
            }

            $settings->setEazyNewsletterAddresses($newArray);

            if ($validation) {
                $activation = true;
            }

            $settings->updateSettings();
        }
    }
} catch (Exception $ex) {
    if (EAZYLOGDATA) {
        EazyNewsletterSystem::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
    }
}
?>

<?php if ($activation === true) { ?>
    <div class="eazy-newsletter-activation-message">
        <p><?php echo __('Sie haben Ihre Eintragung zum Newsletter erfolgreich bestätigt!', 'eazy_newsletter'); ?></p>
    </div>
<?php } else if ($overTime === true) { ?>
    <div class="eazy-newsletter-activation-message">
        <p><?php echo __('Ihr Aktivierungslink ist abgelaufen', 'eazy_newsletter'); ?></p>
        <p><?php echo __('Tragen Sie sich bitte erneut ein!', 'eazy_newsletter'); ?></p>
    </div>
<?php } ?>





<?php get_footer(); ?>

<?php wp_die(); ?>
