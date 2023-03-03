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
                "form-field-change-tab-click",
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
                self.setFieldTab(fieldset, this.value);
                self.onTabChange();
            });
            select.show();
            $(clickedButton).parent().find('.form-field-change-tab-button').hide();
        },

        setFieldTab: function(fieldset, tabId)
        {
            $(fieldset.find('.group-code')[0]).val(tabId);
            fieldset.removeClass('form_tab_' + this.activeTab);
            fieldset.addClass('form_tab_' + tabId);
        },

        hideChangeButtonSelects: function()
        {
            $('.form-field-change-tab-button').show();
            $('.change-tab-select').hide();
        },

        initTabs: function (tabsJson) {
            var self = this;
            $.each(tabsJson, function() {
                if (!self.firstTabId) {
                    self.firstTabId = this.code;
                }
                self.addTab(this.label, this.code);
            });
            this.addNewTabClickEvent();
            $(document).on(
                "form-new-field",
                function(e, newField) {
                    var fieldset = $(newField).find('.fieldset')[0];
                    self.setFieldTab($(fieldset), self.activeTab);
                }
            );
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
            var self = this;
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
            labelInput.attr('name', 'form_tabs[' + tabId + '][label]');
            $("#tabs-settings-container")[0].appendChild(newSettings);

            if (tabId != this.firstTabId) {
                var removeTabButton = $(newSettings).find('.form-remove-tab')[0];
                $(removeTabButton).parent().show();
                $(removeTabButton).click(function() {
                    self.removeTab(tabId);
                    return false;
                });
            }

            this.tabs[newTab.attr("data-id")] = {
                "tab": newTab,
                "link": tabLink,
                "settings": newSettings,
                "label": label,
                "id": tabId
            };
            newTab.show();

            return newTab;
        },

        removeTab: function(tabId) {
            var self = this;
            var selectTab = $('<select id="move_to_tab_id">');
            $.each(this.tabs, function () {
                if (this.id != tabId) {
                    selectTab.append(new Option(this.label, this.id));
                }
            });
            confirm({
                content: $t('Are You Sure?') + '<br>' + $t('Move fields to tab:') + ' ' + selectTab.get(0).outerHTML,
                actions: {
                    confirm: function () {
                        var selectedTabToMove = $('#move_to_tab_id').val();
                        var tab = self.tabs[tabId];
                        tab.tab.remove();
                        tab.settings.remove();
                        delete self.tabs[tabId];
                        self.activeTab = selectedTabToMove;
                        $(self.formContainer).find('.fieldset.' + 'form_tab_' + tabId).each(function() {
                            self.setFieldTab($(this), selectedTabToMove);
                        });
                        self.onTabChange();
                    }
                }
            });
        }
    };

    return function (config) {
        $(document).ready(function () {
            formTabs.init(config);
        });
    };
});
