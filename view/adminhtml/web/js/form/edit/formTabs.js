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
            this.tabs = {};
            this.newTabLabel = config.newTabLabel;
            this.activeTab = config.activeTab;
            this.lastTabNumber = config.lastTabNumber;
            this.initTabs(config.formTabs);
            this.onTabChange();
            this.addNewTabClickEvent();
        },

        initTabs: function (tabsJson) {
            var self = this;
            $.each(tabsJson, function() {
                self.addTab(this.label, this.code);
            });
        },

        onTabChange: function () {
            var self = this;
            $(this.formContainer).find('.fieldset' ).each(function() {
                $(this).closest('.fieldset-wrapper').hide();
            });

            $(this.formContainer).find('.fieldset.' + 'form_tab_' + this.activeTab).each(function() {
                $(this).closest('.fieldset-wrapper').show();
            });

            var tab = self.getTabs()[self.activeTab];
            $(tab).parent().addClass('ui-state-active');

            $('.tab-settings').hide();
            $('#tab-settings-' + this.activeTab).show();
        },

        addNewTabClickEvent: function () {
            var self = this;
            var addNewButton = $('#add_tab_button');
            addNewButton.click(function () {
                self.lastTabNumber ++;
                var newTab = self.addTab(self.newTabLabel);
                self.activeTab = newTab.attr("data-id");
                self.onTabChange();
                return false;
            });
        },

        addOnClickTabEvent: function (tab) {
            var self = this;
            tab.click(function () {
                $.each(self.getTabs(), function() {
                    $(this).parent().removeClass('ui-state-active');
                });

                self.activeTab = $(this).parent().attr("data-id");
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

        addTab: function (label, tabId=null) {
            var tabTemplate = $('#tab-template')[0];
            var newTab = tabTemplate.clone(true);
            $(tabTemplate).parent()[0].insertBefore(newTab, $('#add_tab_button')[0]);
            newTab = $(newTab);
            if (tabId) {
                newTab.attr("data-id", tabId);
            } else {
                newTab.attr("data-id", this.lastTabNumber);
            }

            newTab.removeAttr('id');
            var tabLink = newTab.find(".tab-item-link");
            tabLink.title = label;
            tabLink.text(label);
            this.addOnClickTabEvent(tabLink);
            this.tabs[newTab.attr("data-id")] = tabLink;
            var newSettings = $('#tab-settings-template')[0].clone(true);
            newSettings.id = "tab-settings-new_tab_" + this.lastTabNumber;
            var labelInput = $(newSettings).find(".label-input");
            labelInput.val(label);
            labelInput.attr('name', 'new_form_tab[' + this.lastTabNumber + '][label]');
            $("#tabs-settings-container")[0].appendChild(newSettings);
            newTab.show();
            return newTab;
        }
    };

    return function (config) {
        $(document).ready(function () {
            formTabs.init(config);
        });
    };
});
