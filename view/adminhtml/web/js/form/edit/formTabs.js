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
            this.initChangeTabButtons();
        },

        initChangeTabButtons: function()
        {
            var self = this;
            $(document).on(
                "change-tab",
                function(e, clickedButton) {
                    self.onChangeTabButtonClick(clickedButton);
                }
            );

            $(window).click(function(e) {
                if (!$(e.target).hasClass('change-tab-select')) {
                    self.hideChangeButtonSelects();
                }
            });
        },

        onChangeTabButtonClick: function(clickedButton)
        {
            var self = this;
            this.hideChangeButtonSelects();
            var select = $(clickedButton).parent().find('.change-tab-select');
            select.empty();
            $.each(this.tabs, function () {
                select.append(new Option(this.label, this.id));
            });
            select.val(this.activeTab);
            select.on('change', function() {
                var fieldset = select.closest('.fieldset');
                $(fieldset.find('.group-code')[0]).val(this.value);
                fieldset.removeClass('form_tab_' + self.activeTab);
                fieldset.addClass('form_tab_' + this.value);
                self.onTabChange();
            });
            select.show();
            $(clickedButton).parent().find('.form-field-change-tab-button').hide();
        },

        hideChangeButtonSelects: function()
        {
            $('.form-field-change-tab-button').show();
            $('.change-tab-select').hide();
        },

        initTabs: function (tabsJson) {
            var self = this;
            $.each(tabsJson, function() {
                self.addTab(this.label, this.code);
            });
            this.addNewTabClickEvent();
        },

        onTabChange: function () {
            var self = this;
            $(this.formContainer).find('.fieldset' ).each(function() {
                $(this).closest('.fieldset-wrapper').hide();
            });

            $(this.formContainer).find('.fieldset.' + 'form_tab_' + this.activeTab).each(function() {
                $(this).closest('.fieldset-wrapper').show();
            });

            $.each(self.tabs, function() {
                this.tab.removeClass('ui-state-active');
            });

            var tab = self.tabs[self.activeTab];
            tab.tab.addClass('ui-state-active');

            this.hideChangeButtonSelects();
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
            addNewButton.show();
        },

        addOnClickTabEvent: function (tab) {
            var self = this;
            tab.click(function () {
                self.activeTab = $(this).parent().attr("data-id");
                self.onTabChange();
                return false;
            });
        },

        addTab: function (label, tabId=null) {
            var tabTemplate = $('#tab-template')[0];
            var newTab = tabTemplate.clone(true);
            $(tabTemplate).parent()[0].insertBefore(newTab, $('#add_tab_button')[0]);
            newTab = $(newTab);
            if (!tabId) {
                tabId = this.lastTabNumber;
            }
            newTab.attr("data-id", tabId);
            newTab.removeAttr('id');
            var tabLink = newTab.find(".tab-item-link");
            tabLink.title = label;
            tabLink.text(label);
            this.addOnClickTabEvent(tabLink);
            var newSettings = $('#tab-settings-template')[0].clone(true);
            newSettings.id = "tab-settings-" + tabId;
            var labelInput = $(newSettings).find(".label-input");
            labelInput.val(label);
            labelInput.attr('name', 'new_form_tab[' + tabId + '][label]');
            $("#tabs-settings-container")[0].appendChild(newSettings);
            newTab.show();

            this.tabs[newTab.attr("data-id")] = {
                "tab": newTab,
                "link": tabLink,
                "label": label,
                "id": tabId
            };

            return newTab;
        }
    };

    return function (config) {
        $(document).ready(function () {
            formTabs.init(config);
        });
    };
});
