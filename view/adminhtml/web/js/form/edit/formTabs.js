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

    var formTabs = {

        init: function (config) {
            this.formContainer = $('#' + config.formContainerId);
            this.newTabLabel = config.newTabLabel;
            this.activeTab = config.activeTab;
            this.onTabChange();
            this.addOnClickEvents();
            this.vars = false;
            this.lastTabNumber = config.lastTabNumber;
        },

        onTabChange: function () {
            var self = this;
            $(this.formContainer).find('.fieldset' ).each(function() {
                $(this).closest('.fieldset-wrapper').hide();
            });
            $(this.formContainer).find('.fieldset.' + this.activeTab).each(function() {
                $(this).closest('.fieldset-wrapper').show();
            });

            var tab = self.getTabs()[self.activeTab];
            $(tab).parent().addClass('ui-state-active');

            $('.tab-settings').hide();
            $('#tab-settings-' + this.activeTab).show();
        },

        addOnClickEvents: function () {
            var self = this;
            $.each(this.getTabs(), function() {
                self.addOnClickTabEvent($(this));
            });
        },

        addOnClickTabEvent: function (tab) {
            var self = this;
            tab.click(function () {
                $.each(self.getTabs(), function() {
                    $(this).parent().removeClass('ui-state-active');
                });

                var tab_id = $(this).parent().attr("data-id");
                if (tab_id == 'add') {
                    self.addNewTab();
                } else {
                    self.activeTab = tab_id;
                }

                self.onTabChange();
                return false;
            });
        },

        getTabs: function () {
            var self = this;
            if (!this.tabs) {
                this.tabs = {};
                $(this.formContainer).find('.tab-item-link').each(function() {
                    var tabCode = $(this).parent().attr("data-id");
                    self.tabs[tabCode] = this;
                });
            }

            return this.tabs;
        },

        addNewTab: function () {
            this.lastTabNumber ++;
            var newTab = $('.form-tab')[0].clone(true);
            $($('.form-tab')[0]).parent()[0].insertBefore(newTab, $('#add_tab_button')[0]);
            newTab = $(newTab);
            newTab.attr("data-id", "new_tab_" + this.lastTabNumber);
            newTab.removeAttr('id');
            var tabLink = newTab.find(".tab-item-link");
            tabLink.title = this.newTabLabel;
            tabLink.text(this.newTabLabel);
            this.addOnClickTabEvent(tabLink);
            this.tabs[newTab.attr("data-id")] = tabLink;

            var newSettings = $('#tabs-settings-container .tab-settings')[0].clone(true);
            newSettings.id = "tab-settings-new_tab_" + this.lastTabNumber;
            var labelInput = $(newSettings).find(".label-input");
            labelInput.val(this.newTabLabel);
            labelInput.attr('name', 'new_form_tab[' + this.lastTabNumber + '][label]');
            $("#tabs-settings-container")[0].appendChild(newSettings);
            newTab.show();
            this.activeTab = newTab.attr("data-id");
            this.onTabChange();
        }
    };

    return function (config) {
        $(document).ready(function () {
            formTabs.init(config);
        });
    };
});
