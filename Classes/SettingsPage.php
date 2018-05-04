<?php
/* @var $mailObject EmailAddress */
/* @var $singleAddress EmailAddress */
/* @todo ajaxRequest zum speichern */
if (!defined('ABSPATH')) {
    die();
}

class SettingsPage {

    /**
     *
     * @var Settings
     */
    var $settings;

    function __construct() {
        $this->setSettings(Settings::getInstance());
    }

    /**
     * 
     * @param Settings $settings
     */
    public function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * 
     * @return Settings
     */
    public function getSettings() {
        return $this->settings;
    }

    public function render() {
        add_action('admin_menu', array($this, 'eazy_newsletter_add_admin_menu'));
        add_action('admin_init', array($this, 'eazy_newsletter_settings_init'));
    }

    public function eazy_newsletter_add_admin_menu() {

        add_options_page('eazy_newsletter', 'eazy_newsletter', 'manage_options', 'eazy_newsletter', array($this, 'eazy_newsletter_options_page'));
    }

    public function eazy_newsletter_settings_init() {


        register_setting('eazy_newsletter', 'eazy_newsletter_settings');


        add_settings_section('eazy_newsletter_settings_section', __('Einstellungen', 'eazy_newsletter'), array($this, 'eazy_newsletter_settings_section_callback'), 'eazy_newsletter');

        add_settings_field(
                'eazy_newsletter_name', __('Der Name von dem aus Newsletter versendet werden', 'eazy_newsletter'), array($this, 'eazy_newsletter_name_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_mail', __('Die E-Mail Adresse von der aus Newsletter versendet werden', 'eazy_newsletter'), array($this, 'eazy_newsletter_mail_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_html', __('Newsletter im HTML-Format versenden ?', 'eazy_newsletter'), array($this, 'eazy_newsletter_html_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_custom_html_header', __('Custom Wrapper für den Header des Newsletter', 'eazy_newsletter'), array($this, 'eazy_newsletter_custom_html_header_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_custom_html_body', __('Custom Wrapper für den Body des Newsletter', 'eazy_newsletter'), array($this, 'eazy_newsletter_custom_html_body_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_custom_html_footer', __('Custom Wrapper für den Footer des Newsletter', 'eazy_newsletter'), array($this, 'eazy_newsletter_custom_html_footer_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_automatic', __('Sollen Newsletter automatisch zu einem bestimmten Datum versendet werden?', 'eazy_newsletter'), array($this, 'eazy_newsletter_automatic_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_activation_page_id', __('Aktivierungsseite', 'eazy_newsletter'), array($this, 'eazy_newsletter_activation_page_id_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_send_time', __('Zu welcher Uhrzeit sollen Newsletter versendet werden?', 'eazy_newsletter'), array($this, 'eazy_newsletter_send_time_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );

        add_settings_field(
                'eazy_newsletter_addresses', __('Eingetragene Adressen:', 'eazy_newsletter'), array($this, 'eazy_newsletter_addresses_render'), 'eazy_newsletter', 'eazy_newsletter_settings_section'
        );
    }

    public function eazy_newsletter_name_render() {
        ?>
        <input type='text' id="eazy_newsletter_name" name='eazy_newsletter_name' value='<?php echo $this->getSettings()->getEazyNewsletterName(); ?>'>
        <?php
    }

    public function eazy_newsletter_mail_render() {
        ?>
        <input type='text' id="eazy_newsletter_mail" name='eazy_newsletter_mail' value='<?php echo $this->getSettings()->getEazyNewsletterMail(); ?>'>
        <?php
    }

    public function eazy_newsletter_html_render() {
        ?>
        <input type='checkbox' id="eazy_newsletter_html" name='eazy_newsletter_html' <?php checked($this->getSettings()->getEazyNewsletterHtml(), 1, true); ?> value="<?php echo!$this->getSettings()->getEazyNewsletterHtml(); ?>">
        <?php
    }

    public function eazy_newsletter_custom_html_header_render() {
        ?>
        <textarea type='text' id="eazy_newsletter_custom_html_header" name='eazy_newsletter_custom_html_header' value='<?php echo $this->getSettings()->getEazyNewsletterCustomHtmlHeader(); ?>'></textarea>
        <?php
    }

    public function eazy_newsletter_custom_html_body_render() {
        ?>
        <textarea type='text' id="eazy_newsletter_custom_html_body" name='eazy_newsletter_custom_html_body' value='<?php echo $this->getSettings()->getEazyNewsletterCustomHtmlBody(); ?>'></textarea>
        <?php
    }

    public function eazy_newsletter_custom_html_footer_render() {
        ?>
        <textarea type='text' id="eazy_newsletter_custom_html_footer" name='eazy_newsletter_custom_html_footer' value='<?php echo $this->getSettings()->getEazyNewsletterCustomHtmlFooter(); ?>'></textarea>
        <?php
    }

    public function eazy_newsletter_automatic_render() {
        ?>
        <input type='checkbox' id="eazy_newsletter_automatic" name='eazy_newsletter_automatic' <?php checked($this->getSettings()->getEazyNewsletterAutomatic(), 1, true); ?> value="<?php echo!$this->getSettings()->getEazyNewsletterAutomatic(); ?>">
        <?php
    }

    public function eazy_newsletter_activation_page_id_render() {

        $pageId = $this->getSettings()->getEazyNewsletterActivationPageID();

        $args = array(
            'depth' => 3,
            'child_of' => 0,
            'selected' => $pageId,
            'echo' => 1,
            'name' => 'eazy_newsletter_activation_page_id',
            'post_type' => 'page',
            'id' => 'eazy_newsletter_activation_page_id'
        );

        wp_dropdown_pages($args);
        ?>

        <?php
    }

    public function eazy_newsletter_send_time_render() {

        $sendTime = $this->getSettings()->getEazyNewsletterSendTime();
        ?>
        <select name="eazy_newsletter_send_time" id="eazy_newsletter_send_time">
            <option name="eazy_newsletter_send_time_value" value="00:00" <?php
            if ($sendTime == '00:00') {
                echo 'selected';
            };
            ?>>00:00</option>
            <option name="eazy_newsletter_send_time_value" value="01:00" <?php
            if ($sendTime == '01:00') {
                echo 'selected';
            };
            ?>>01:00</option>
            <option name="eazy_newsletter_send_time_value" value="02:00" <?php
            if ($sendTime == '02:00') {
                echo 'selected';
            };
            ?>>02:00</option>
            <option name="eazy_newsletter_send_time_value" value="03:00" <?php
            if ($sendTime == '03:00') {
                echo 'selected';
            };
            ?>>03:00</option>
            <option name="eazy_newsletter_send_time_value" value="04:00" <?php
            if ($sendTime == '04:00') {
                echo 'selected';
            };
            ?>></option>
            <option name="eazy_newsletter_send_time_value" value="05:00" <?php
            if ($sendTime == '05:00') {
                echo 'selected';
            };
            ?>>05:00</option>
            <option name="eazy_newsletter_send_time_value" value="06:00" <?php
            if ($sendTime == '06:00') {
                echo 'selected';
            };
            ?>>06:00</option>
            <option name="eazy_newsletter_send_time_value" value="07:00" <?php
            if ($sendTime == '07:00') {
                echo 'selected';
            };
            ?>>07:00</option>
            <option name="eazy_newsletter_send_time_value" value="08:00" <?php
            if ($sendTime == '08:00') {
                echo 'selected';
            };
            ?>>08:00</option>
            <option name="eazy_newsletter_send_time_value" value="09:00" <?php
            if ($sendTime == '09:00') {
                echo 'selected';
            };
            ?>>09:00</option>
            <option name="eazy_newsletter_send_time_value" value="10:00" <?php
            if ($sendTime == '10:00') {
                echo 'selected';
            };
            ?>>10:00</option>
            <option name="eazy_newsletter_send_time_value" value="11:00" <?php
            if ($sendTime == '11:00') {
                echo 'selected';
            };
            ?>>11:00</option>
            <option name="eazy_newsletter_send_time_value" value="12:00" <?php
            if ($sendTime == '12:00') {
                echo 'selected';
            };
            ?>>12:00</option>
            <option name="eazy_newsletter_send_time_value" value="13:00" <?php
            if ($sendTime == '13:00') {
                echo 'selected';
            };
            ?>>13:00</option>
            <option name="eazy_newsletter_send_time_value" value="14:00" <?php
            if ($sendTime == '14:00') {
                echo 'selected';
            };
            ?>>14:00</option>
            <option name="eazy_newsletter_send_time_value" value="15:00" <?php
            if ($sendTime == '15:00') {
                echo 'selected';
            };
            ?>>15:00</option>
            <option name="eazy_newsletter_send_time_value" value="16:00" <?php
            if ($sendTime == '16:00') {
                echo 'selected';
            };
            ?>>16:00</option>
            <option name="eazy_newsletter_send_time_value" value="17:00" <?php
            if ($sendTime == '17:00') {
                echo 'selected';
            };
            ?>>17:00</option>
            <option name="eazy_newsletter_send_time_value" value="18:00" <?php
            if ($sendTime == '18:00') {
                echo 'selected';
            };
            ?>>18:00</option>
            <option name="eazy_newsletter_send_time_value" value="19:00" <?php
            if ($sendTime == '19:00') {
                echo 'selected';
            };
            ?>>19:00</option>
            <option name="eazy_newsletter_send_time_value" value="20:00" <?php
            if ($sendTime == '20:00') {
                echo 'selected';
            };
            ?>>20:00</option>
            <option name="eazy_newsletter_send_time_value" value="21:00" <?php
            if ($sendTime == '21:00') {
                echo 'selected';
            };
            ?>>21:00</option>
            <option name="eazy_newsletter_send_time_value" value="22:00" <?php
            if ($sendTime == '22:00') {
                echo 'selected';
            };
            ?>>22:00</option>
            <option name="eazy_newsletter_send_time_value" value="23:00" <?php
            if ($sendTime == '23:00') {
                echo 'selected';
            };
            ?>>23:00</option>
        </select>
        <?php
    }

    public function eazy_newsletter_addresses_render() {

        $addresses = $this->getSettings()->getEazyNewsletterAddresses() !== false ? $this->getSettings()->getEazyNewsletterAddresses() : array();
        ?>

        <?php if (sizeof($addresses) > 0 && $addresses !== false) { ?>
            <input type="hidden" id="eazy-newsletter-action-delete" value="<?php echo System::getAjaxRequestValue('DeleteEmail'); ?>">
            <div id="eazy-newsletter-addresses-wrapper" class="wrapper">
                <table>
                    <tr>
                        <th class="mail-address"><?php __('Addresse', 'eazy_newsletter'); ?></th>
                        <th class = "mail-field"><?php __('Aktiv', 'eazy_newsletter'); ?></th>
                        <th class="mail-field"><?php __('Eingetragen am:', 'eazy_newsletter'); ?></th>
                        <th class="mail-field"><?php __('Status', 'eazy_newsletter'); ?></th>
                        <th class="mail-field"><?php __('Fehler', 'eazy_newsletter'); ?></th>
                        <th class="mail-field"></th>
                    </tr>
                    <?php
                    /* @var $singleAddress EmailAddress */
                    foreach ($addresses as $singleAddress) {

                        $active = '';
                        $status = '<p style="color:#096E26;">' . __('OK', 'eazy_newsletter') . '</p>';
                        $fehlermeldung = '<p style="color:#096E26;">' . __('Keine Fehler', 'eazy_newsletter') . '</p>';

                        if ($singleAddress->getActive() == 1) {
                            $active = '<p style="color:#096E26;">' . __('Ja', 'eazy_newsletter') . '</p>';
                        } else {
                            $active = '<p style="color:#960B00;">' . __('Nein', 'eazy_newsletter') . '</p>';
                        }

                        if ($singleAddress->getError() !== false) {
                            $status = '<p style="color:#960B00;">' . __('Fehler', 'eazy_newsletter') . '</p>';
                        }

                        if ($singleAddress->getError() === 2) {
                            $fehlermeldung = '<p style="color:#960B00;">' . __('Für diese E-Mail Addresse existiert kein MX-Record. Sie sollten diese E-Mail löschen wenn es zu Fehlermeldungen kommt!', 'eazy_newsletter') . '</p>';
                        } else if ($singleAddress->getError() === 3) {
                            $fehlermeldung = '<p style="color:#960B00;">' . __('Bei der Eintragung dieser E-Mail Addresse ist es zu Fehlern gekommen.', 'eazy_newsletter') . '</p>';
                        }
                        ?>
                        <tr>
                            <td class="mail-address"><?php echo $singleAddress->getAddress(); ?></td>
                            <td class="mail-field"><?php echo $active; ?></td>
                            <td class="mail-field"><?php echo date('d-m-Y', $singleAddress->getTimestamp()); ?></td>
                            <td class="mail-field"><?php echo $status; ?></td>
                            <td class="mail-field"><?php echo $fehlermeldung; ?></td>
                            <td class="mail-field"><a class="delete-mail-link"  style="color:red;"><?php echo __('Löschen', 'eazy_newsletter'); ?></a></td>
                        </tr>
                    <?php } ?>

                </table>
            </div>
        <?php } ?>
        <?php
    }

    public function eazy_newsletter_settings_section_callback() {

        echo __('Eazy Newsletter konfigurieren', 'eazy_newsletter');
    }

    public function eazy_newsletter_options_page() {
        ?>
        <div id="eazy_newsletter_settings_form" class="eazy_newsletter_settings_form">
            <h2>Eazy Newsletter</h2>
            <input type="hidden" id="eazy-newsletter-action"  value="<?php echo System::getAjaxRequestValue('SaveSettings'); ?>">
            <?php
            settings_fields('eazy_newsletter');
            do_settings_sections('eazy_newsletter');
            ?>
        </div>
        <input type="button" id="save-eazy-newsletter-settings-button" value="<?php echo __('Einstellungen speichern', 'eazy_newsletter'); ?>">
        <div class = "ajax-message" id = "ajax-message">
            <p class = "text"></p>
        </div>
        <div class = "loading-div" id = "loading-div">
            <img src = "<?php echo System::getImageURL('ajax-loader.gif'); ?>" />
        </div>
        <?php
    }

}
