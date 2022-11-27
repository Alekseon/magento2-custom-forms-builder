/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'mage/translate'
], function ($, confirm, $t) {
    'use strict';

    var formFields = {

        init: function (config) {
            this.newFieldsCounter = 0;
            this.newFieldTemplate = $('#' + config.newFieldTemplateId)[0];

            this.formContainer = $('#' + config.formContianerId + ' .form-inline')[0];
            this.newFieldButton = $('#' + config.newFieldButtonId);
            this.formRemovedFieldsInputId = $('#' + config.formRemovedFieldsInputId);
            this.hideNewFieldTemplate();
            this.addNewFieldButtonEvent();
            this.addDeleteFieldsEvents();
            this.addEnableDisableFieldsEvents();
        },

        addNewField: function () {
            this.newFieldsCounter ++;
            var self = this;
            var newField = this.newFieldTemplate.clone(true);

            $(newField).find("input, select, textarea").each(function() {
                var fieldCode = $(this).data('fieldcode');
                $(this).attr("name", "new_fields[" + self.newFieldsCounter + "][" + fieldCode + "]");
            });

            var removeButton = $(newField).find('.delete-field-button')[0];
            this.addRemoveFieldEvent(removeButton);
            this.formContainer.appendChild(newField);

            $(newField).slideDown();
        },

        addNewFieldButtonEvent: function () {
            this.newFieldButton.click(function () {
                formFields.addNewField();
                return false;
            });
        },

        hideNewFieldTemplate: function () {
            this.newFieldTemplate.hide();
        },

        addDeleteFieldsEvents: function () {
            var formFields = this;
            $(this.formContainer).find('.delete-field-button').each(function() {
                formFields.addRemoveFieldEvent(this);
            });
        },

        addRemoveFieldEvent: function (removeButton) {
            var self = this;
            $(removeButton).click(function () {
                confirm({
                    content: $t('Are You Sure?'),
                    actions: {
                        confirm: function () {
                            var fieldsetWrapper = $(removeButton).closest('.fieldset-wrapper');
                            var fieldsetId = self.getFieldsetId($(removeButton));
                            if (fieldsetId !== 'new_field') {
                                var removedIds = [];
                                var removedIdsVal = $(self.formRemovedFieldsInputId).val();
                                if (removedIdsVal) {
                                    removedIds = removedIdsVal.split(',');
                                }
                                removedIds.push(fieldsetId);
                                $(self.formRemovedFieldsInputId).val(removedIds.join(','));
                                fieldsetWrapper.slideUp("slow");
                            } else {
                                fieldsetWrapper.slideUp("slow", function () {
                                    this.remove();
                                });
                            }
                        }
                    }
                });
                return false;
            });
        },

        addEnableDisableFieldsEvents: function () {
            var self = this;
            $(this.formContainer).find('.enable-disable-field-buttons').each(function() {
                var enableButton = this.querySelector('.enable-button');
                var disableButton = this.querySelector('.disable-button');
                self.updateDisableNotice(enableButton);
                self.addEnableDisableFieldEvent(enableButton, disableButton);
            });
        },

        updateDisableNotice: function (enablebutton) {
            var fieldsetWrapper = $(enablebutton).closest('.fieldset-wrapper');
            var fieldsetId = this.getFieldsetId($(enablebutton));
            var isDisabled = $('#form_field_' + fieldsetId + '_is_enabled').val();
            var formDisableNotice = $('#form_disable_notice_' + fieldsetId);
            if (formDisableNotice.length != 0) {
                formDisableNotice.remove();
            }

            if (isDisabled == 0) {
                $(fieldsetWrapper.find('strong')).prepend(
                    "<span id=\"form_disable_notice_" + fieldsetId + "\" class=\"message message-warning\">"+ $t('Disabled') + "</span>"
                );
            }
        },

        addEnableDisableFieldEvent: function (enableButton, disableButton) {
            var self = this;

            $(disableButton).click(function () {
                var fieldsetId = self.getFieldsetId($(disableButton));
                $('#form_field_' + fieldsetId + '_is_enabled').val(0);
                $(enableButton).show();
                $(disableButton).hide();
                self.updateDisableNotice(enableButton);
                return false;
            });

            $(enableButton).click(function () {
                var fieldsetId = self.getFieldsetId($(disableButton));
                $('#form_field_' + fieldsetId + '_is_enabled').val(1);
                $(enableButton).hide();
                $(disableButton).show();
                self.updateDisableNotice(enableButton);
                return false;
            });
        },

        getFieldsetId: function (fieldsetChildElement) {
            var fieldsetWrapper = fieldsetChildElement.closest('.fieldset-wrapper');
            var fieldsetId = $(fieldsetWrapper.find('fieldset')[0]).attr('id').substr('form_field_'.length);
            return fieldsetId;
        }
    };

    return function (config) {
        $(document).ready(function () {
            formFields.init(config);
        });
    };
});
