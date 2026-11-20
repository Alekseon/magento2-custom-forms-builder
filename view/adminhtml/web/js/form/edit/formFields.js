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
            var self = this;
            this.newFieldsCounter = 0;

            this.formContainer = $('#' + config.formContainerId + ' .form-inline')[0];
            this.formRemovedFieldsInputId = $('#' + config.formRemovedFieldsInputId);

            var templates = config.newFieldTemplates || [{
                buttonId: config.newFieldButtonId,
                templateId: config.newFieldTemplateId
            }];
            $.each(templates, function () {
                var template = $('#' + this.templateId)[0];
                if (template) {
                    $(template).hide();
                    self.addNewFieldButtonEvent($('#' + this.buttonId), template, this);
                }
            });

            this.addFieldsEvents();
        },

        addNewField: function (newFieldTemplate, templateConfig) {
            this.newFieldsCounter ++;
            var self = this;
            var newField = newFieldTemplate.clone(true);

            $(newField).find("input, select, textarea").each(function() {
                var fieldCode = $(this).data('fieldcode');
                $(this).attr("name", "new_fields[" + self.newFieldsCounter + "][" + fieldCode + "]");
            });

            if (templateConfig && templateConfig.type) {
                $(newField).find('[data-fieldcode="frontend_input"]').val(templateConfig.type).trigger('change');
            }

            if (templateConfig && templateConfig.label) {
                $(newField).find('.fieldset-wrapper-title .admin__collapsible-title > span').first().text('[' + templateConfig.label + ']');
            }

            var removeButton = $(newField).find('.delete-field-button')[0];
            this.addRemoveFieldEvent(removeButton);

            var changeTabButton = $(newField).find('.form-field-change-tab-button')[0];
            this.addChangeTabButtonEvent(changeTabButton);

            this.formContainer.appendChild(newField);

            $(document).trigger('form-new-field', [newField]);
            $(newField).slideDown();
        },

        addNewFieldButtonEvent: function (button, newFieldTemplate, templateConfig) {
            button.click(function () {
                formFields.addNewField(newFieldTemplate, templateConfig);
                button.closest('.actions-split').find('[data-toggle=dropdown].active').trigger('close.dropdown');
                return false;
            });
        },

        addFieldsEvents: function () {
            var self = this;
            $(this.formContainer).find('.delete-field-button').each(function() {
                self.addRemoveFieldEvent(this);
            });
            $(this.formContainer).find('.form-field-change-tab-button').each(function() {
                self.addChangeTabButtonEvent(this);
            });
        },

        addChangeTabButtonEvent: function (changeTabButton) {
            $(changeTabButton).click(function () {
                $(document).trigger('form-field-change-tab-click', [this]);
                return false;
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
                            var isSavedField = !!fieldsetWrapper.find('[data-fieldcode="id"]').val();
                            if (isSavedField) {
                                var removedIds = [];
                                var removedIdsVal = $(self.formRemovedFieldsInputId).val();
                                if (removedIdsVal) {
                                    removedIds = removedIdsVal.split(',');
                                }
                                removedIds.push(fieldsetId);
                                $(self.formRemovedFieldsInputId).val(removedIds.join(','));
                            }

                            fieldsetWrapper.slideUp("slow", function () {
                                this.remove();
                            });
                        }
                    }
                });
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
