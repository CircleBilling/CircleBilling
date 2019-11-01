jQuery(document).on('click', '.api-button', function() {
    var jElement = jQuery(this);

    CB.APP.HandleAPIElement(jElement.data('api-url'), jElement.data('api-data'), jElement.data('api-reload'));

    return false;
});

jQuery(document).on('submit', '.api-form', function() {
    var jForm = jQuery(this);

    CB.APP.HandleAPIElement(jForm.data('api-url'),jForm.serialize(), jForm.data('api-reload'));

    return false;
});

var CB = {};

CB.APP = {

    HandleAPIElement: function(apiUrl, apiData, reload) {
        CB.APP.ShowLoader();

        CB.APP.Post(
            apiUrl,
            apiData,
            function() {
                if(reload == true) {
                    CB.APP.Reload();
                }
            }
        );
    },

    RegisterEvent: function(name, params){
        var event = new CustomEvent(name, { detail: params });

        // Dispatch/Trigger/Fire the event
        document.dispatchEvent(event);
    },

    Post: function(url, params, jsonp) {
        jQuery.ajax({
            type: "POST",
            url: CB.APP.RestApiUrl(url),
            data: params,
            dataType: 'json',

            error: function(jqXHR, textStatus, errorMessage) {
                CB.APP.HideLoader();

                CB.APP.ShowMessage(error, textStatus, errorMessage);
            },

            success: function(data) {
                if(data.error) {
                   CB.APP.HideLoader();

                    CB.APP.RegisterEvent('cb_ajax_post_message_error', data);
                    CB.APP.ShowMessage('error', 'Error', data.error.message);
                } else {
                    if(typeof jsonp === 'function') {
                        return jsonp(data.result);
                    } else if(window.hasOwnProperty('console')) {
                        console.log(data.result);
                    }
                }
            }
        });
    },

    Get: function(url, params, jsonp) {
        jQuery.ajax({
            type: "GET",
            url: APP.restApiUrl(url),
            data: params,
            dataType: 'json',
            error: function(jqXHR, textStatus, errorMessage) {
                CB.APP.HideLoader();

                CB.APP.ShowMessage(error, textStatus, errorMessage);
            },
            success: function(data) {
                if(data.error) {
                    CB.APP.HideLoader();

                    CB.APP.RegisterEvent('cb_ajax_get_message_error', data);
                    CB.APP.ShowMessage('error', 'Error', data.error.message);
                } else {
                    if(typeof jsonp === 'function') {
                        return jsonp(data.result);
                    } else if(window.hasOwnProperty('console')) {
                        console.log(data.result);
                    }
                }
            }
        });
    },

    RestApiUrl: function(url) {
        if(url.indexOf('http://') > -1 || url.indexOf('https://') > -1) {
            return url;
        }

        var url = jQuery('meta[property="app:url"]').attr("content") + 'index.php?_url=/api/' + url;
        return url;
    },

    Reload: function() {
        location.reload(false);
    },

    Redirect: function(url) {
        if(url === undefined) {
            window.location = jQuery('meta[property="app:url"]').attr("content");
        } else {
            window.location = url;
        }
    },

    ShowMessage: function(type, title, text) {
        //TODO: Need popup implementation
        console.log({type: type, title: title, text: text});
    },

    ShowLoader: function() {
        jQuery('.wait').show();
    },

    HideLoader: function() {
        jQuery('.wait').hide();
    }
};