/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
define(['jquery'], function($) {
    'use strict';

    return function(target) {
        $.validator.addMethod(
            'alekseon-validate-form-filesize',
            function(v, element, params) {
                const maxSize = params * 1024;
                const file = element.files[0];

                if(!file) {
                    return true
                }

                const fsize = Math.round((file.size / 1024));
                if(fsize > maxSize) {
                    return false;
                }
                return true;
            },
            $.mage.__('File size too large.')
        );

        $.validator.addMethod(
            'alekseon-validate-form-filetype',
            function(v, element, params = 'image') {
                const file = element.files[0];

                if(!file) {
                    return true;
                }

                const pattern = new RegExp(`^${params}\/.+`);

                if(!pattern.test(file.type)) {
                    return false;
                }
                return true;
            },
            $.mage.__('File is not an image.')
        );
        $.validator.addMethod(
            'alekseon-validate-postal-code',
            function(postCode, element, params) {
                var dataValidate, postalCodes, countryFieldId, countryId, patterns, pattern, regex;
                // for admin
                countryFieldId = $(element).attr('data-validation-params');
                if (countryFieldId === undefined) {
                    // for frontend
                    dataValidate = JSON.parse($(element).attr('data-validate'));
                    countryFieldId = dataValidate['alekseon-validate-postal-code'];
                }

                window.alekseonValidatedPostCodeExample = [];
                postalCodes = window.alekseonCustomFormsPostalCodes;
                countryId = $('#' + countryFieldId).val();

                if (postCode && countryId && postalCodes.hasOwnProperty(countryId)) {
                    patterns = postalCodes[countryId];
                    for (pattern in patterns) {
                        if (patterns.hasOwnProperty(pattern)) {
                            window.alekseonValidatedPostCodeExample.push(patterns[pattern].example);
                            regex = new RegExp(patterns[pattern].pattern);
                            if (regex.test(postCode)) {
                                return true;
                            }
                        }
                    }
                    return false;
                }

                return true;
            },
            function () {
                return $.mage.__('Provided Zip/Postal Code seems to be invalid.')
                    + ' '
                    + $.mage.__('Example: ')
                    +  window.alekseonValidatedPostCodeExample.join('; ')
                    + '. ';
            },
            true
        );

        return target;
    }
});
