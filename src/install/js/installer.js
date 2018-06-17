cb = {};

cb.installer = {

    installUrl : null,

    loadingOverlay : jQuery('#overlay'),

    init : function(configObject) {
        this.installUrl = configObject.installUrl;

        if(configObject.loadingOverlay !== null) {
            this.loadingOverlay = configObject.loadingOverlay;
        }

        jQuery('.btn-prev-step').hide();

        jQuery(document).ajaxStart(function() {
            cb.installer.showLoading();
        });

        jQuery(document).ajaxStop(function() {
            cb.installer.hideLoading();
        });

        jQuery('.btn-next-step').on('click', function() {
            var currentStep = parseInt(jQuery('#installer').attr('data-step'));
            console.log(currentStep,jQuery(this).data('step'), jQuery(this));
            if(cb.installer.validateStep(currentStep) === true) {
                cb.installer.showStep(currentStep +1);

                jQuery('#installer').attr('data-step', currentStep+1);
            } else {

            }

            if(currentStep +1 > 1) {
                jQuery('.btn-prev-step').show();
            }
        });

        jQuery('.btn-prev-step').on('click', function() {
            var currentStep = parseInt(jQuery('#installer').attr('data-step').attr('data-step'));

            cb.installer.showStep(currentStep -1);

            jQuery('#installer').attr('data-step', currentStep -1);

            if(currentStep -1 == 1) {
                jQuery('.btn-prev-step').hide();
            }
        });

        this.showStep(jQuery('#installer').attr('data-step'));
    },

    validateStep : function(stepNumber) {
        var url = this.installUrl + 'index.php?a=';
        var data = $('#installer').serialize();
        var ok = false;

        if(stepNumber == 1){
            if($('#agree').is(':checked') === false) {
                alert('You must agree with terms of service');
                return false;
            }
        }

        if(stepNumber == 2){
            if(this.isEmpty('db_host')) {
                alert("Please check the provided database information.");
                return false;
            }

            if(this.isEmpty('db_name')) {
                alert("Please check the provided database information.");
                return false;
            }

            if(this.isEmpty('db_user')) {
                alert("Please check the provided database information.");
                return false;
            }

            $.ajax({
                async: false,
                type: "POST",
                url: url+'check-db',
                data: data
            }).done(function( msg ) {
                if(msg != 'ok') {
                    alert(msg);
                } else {
                    ok = true;
                }
            });

            return ok;
        }

        if(stepNumber == 3){
            if(this.isEmpty('admin_name')) {
                alert("Please check the provided administrator information.");
                return false;
            }

            if(this.isEmpty('admin_email')) {
                alert("Please check the provided administrator information.");
                return false;
            }

            if(this.isEmpty('admin_pass')) {
                alert("Please check the provided administrator information.");
                return false;
            }

            if(confirm('CircleBilling installer will create database. It may take some time. Do not close this window. Continue?')) {
                $.ajax({
                    async: false,
                    type: "POST",
                    url: url +'install',
                    data: data
                }).done(function( msg ) {
                    if(msg != 'ok') {
                        alert(msg);
                    } else {
                        $('.buttonNext').text('Installed');
                        $('.buttonPrevious').hide();
                        ok = true;
                    }
                });

                return ok;
            } else {
                return false;
            }
        }
        return true;
    },

    showLoading : function() {
        var o = $('.wizard').offset();
        jQuery(this.loadingOverlay).css('height', $('.wizard').height()-2).offset({top: o.top+1, left:o.left+1}).show();
    },

    hideLoading : function() {
        jQuery(this.loadingOverlay).hide();
    },

    showStep : function (stepNumber){
        jQuery('.wizard-progress .step').removeClass('complete active disabled');
        jQuery('.wizard-progress .step').each(function (index) {
            var elementStepNumber = jQuery(this).data('step');

            if(elementStepNumber == stepNumber) {
                jQuery(this).addClass('active');
            }

            if(elementStepNumber < stepNumber) {
                jQuery(this).addClass('complete');
            }

            if(elementStepNumber > stepNumber) {
                jQuery(this).addClass('disabled');
            }

        });

        jQuery('main[role="main"] .step').hide();

        jQuery('.step.step-' + stepNumber).show();
        jQuery('#step-' + stepNumber).show();
        jQuery('.wizard-progress .step[data-step="' + stepNumber  + '"]').addClass('active');

        if(stepNumber == 4){
            cb.installer.doFinish();
        }
    },

    doFinish : function (){
        jQuery('.wizard-footer').hide();
        var installerContent = jQuery('.main-content');
        jQuery(installerContent).attr('class', '');
        jQuery(installerContent).addClass('col-sm-12 col-xs-12')
        return false;
    },

    isEmpty : function isEmpty(id)
    {
        if(!$('#' + id).val()) {
            $('#' + id).addClass('is-invalid');
            return true;
        } else {
            $('#'+id).removeClass('is-invalid');
            return false;
        }
    }
};