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

    $('#eazy-newsletter-submit-button').bind('click touch', function () {
        $.ajax({
            'type': 'POST',
            'data': {
                action: 'eazyNewsletterRequests', 
                eazy_newsletter_action:document.getElementById('eazy-newsletter-action').value,
                email: document.getElementById('eazy-newsletter-mail').value,
                email2: document.getElementById('eazy-newsletter-mail-two').value,
                email3: document.getElementById('eazy-newsletter-mail-three').value,
                time: document.getElementById('eazy-newsletter-time').value
            },
            'url': getAjaxUrl.ajaxurl,
            'success': function (response) {
                $('.ajax-message .text').text(response);
            },
            error: function (jqXHR, exception) {
                $('.ajax-message .text').text(jqXHR + exception);
            }
        });
    });
});