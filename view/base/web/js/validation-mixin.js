/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
define(['jquery'], function($) {
    'use strict';

    return function(target) {
        $.validator.addMethod(
            'alekseon-validate-postal-code',
            function(postCode, element) {
                window.alekseonValidatedPostCodeExample = [];
                var countryFieldIdRegexp, postalCodes, countryFieldId, countryId, patterns, pattern, regex;
                countryFieldIdRegexp = new RegExp(/^alekseon-validate-postal-code-country-field-(.*)$/);
                postalCodes = window.alekseonCustomFormsPostalCodes;

                $.each(element.className.split(' '), function (index, name) {
                    if (name.match(countryFieldIdRegexp)) {
                        countryFieldId = name.split('-').pop();
                        countryId = $('#' + countryFieldId).val();
                    }
                });

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
