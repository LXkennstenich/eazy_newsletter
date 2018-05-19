jQuery(document).ready(function ($) {
    var $loading = $('#loading-div').hide();
    var $ajaxMessage = $('#ajax-message').hide();
    $(document)
            .ajaxStart(function () {
                $loading.show();
            })
            .ajaxStop(function () {
                $loading.hide();
                $ajaxMessage.show();
            });

    $('.delete-mail-link').bind('click touch', function () {
        $.ajax({
            'type': 'POST',
            'data': {
                action: 'eazyNewsletterRequests',
                eazy_newsletter_action: document.getElementById('eazy-newsletter-action-delete').value,
                email: $(this).parent('td').parent('tr').children()[0].innerHTML
            },
            'url': getAjaxUrl.ajaxurl,
            'success': function (response) {
                $('.ajax-message .text').text(response);
                setTimeout(3000);
                document.location.reload();
            },
            error: function (jqXHR, exception) {
                $('.ajax-message .text').text(jqXHR + exception);
            }
        });
    });

    $('#save-eazy-newsletter-settings-button').bind('click touch', function () {

        var htmlvalue = 0;
        var automaticvalue = 0;

        if (document.getElementById('eazy_newsletter_html').checked != false) {
            htmlvalue = 1;
        }

        if (document.getElementById('eazy_newsletter_automatic').checked != false) {
            automaticvalue = 1;
        }

        $.ajax({
            'type': 'POST',
            'data': {
                action: 'eazyNewsletterRequests',
                eazy_newsletter_action: document.getElementById('eazy-newsletter-action').value,
                name: document.getElementById('eazy_newsletter_name').value,
                email: document.getElementById('eazy_newsletter_mail').value,
                html: htmlvalue,
                header: document.getElementById('eazy_newsletter_custom_html_header').value,
                body: document.getElementById('eazy_newsletter_custom_html_body').value,
                footer: document.getElementById('eazy_newsletter_custom_html_footer').value,
                automatic: automaticvalue,
                activationPageID: document.getElementById('eazy_newsletter_activation_page_id').value,
                deleteMailPageID: document.getElementById('eazy_newsletter_delete_mail_page_id').value,
                sendTime: document.getElementById('eazy_newsletter_send_time').value
            },
            'url': getAjaxUrl.ajaxurl,
            'success': function (response) {
                $('.ajax-message .text').text(response);
                setTimeout(1000);
            },
            error: function (jqXHR, exception) {
                $('.ajax-message .text').text(jqXHR + exception);
            }
        });
    });
});

