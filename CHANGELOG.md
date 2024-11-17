# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
### Changed
### Fixed
### Added

## [102.3.14] - 2024-11-17
### Added
- postal code validator

## [102.3.13] - 2024-09-25
### Fixed
- fix getMappedAttributeCode method

## [102.3.12] - 2024-05-24
### Fixed
- Translate labels (https://github.com/Alekseon/AlekseonEav/issues/45)

## [102.3.11] - 2024-05-09
### Fixed
- make "Admin Note" translatable

## [102.3.10] - 2024-02-25
### Changed
- Raplaced "Is Enabled" by "Input Visibility", it allows to set input visibility to: visible, only for admin or none

## [102.3.9] - 2023-12-03
### Added
- Max Size param (for file input validator) 
- note for params

## [102.3.8] - 2023-05-22
### Added
- manage form button on records view + acl permission
- Yes/No option source
- check if source model class exists 
### Fixed 
- fix for saving fields in tabs for new forms

## [102.3.7] - 2023-05-09
### Changed
- code quality improvements
- replaced install and upgrade scripts by patches
### Fixed
- hide delete massaction if no permisstion set

## [102.3.6] - 2023-05-08
### Changed
- code quality improvements

## [102.3.5] - 2023-05-01
### Added
- github actions
- introduced strict_types
### Fixed
- display default label instead of store label as fieldset label
- stay on same scope view after "save and continue" form
- code quality improvements

## [102.3.4] - 2023-04-22
### Fixed
- fixed mass delete action
- remove image file during mass delete action

## [102.3.3] - 2023-04-21
### Added
- Created From Store Id for Record entity 
- system.xml file
### Fixed
- added missing group_fields_in column to alekseon_custom_form table

## [102.3.2] - 2023-03-12
### Added
- small alekseon logo
- cache tags
- getRecordCollection() and getRecordById() methods in Form class
- filter,sort,select on form records collection by field identifier
- getData from form record by field identifier

## [102.3.1] - 2023-03-04
### Fixed
- removed "Options Source" input from rating and boolean fields

## [102.3.0] - 2023-03-03
### Added
- possibility to disable form field
- check if "is required" field is editable
- scopable records grid and record edit page
- group fields in tabs
- added "alekseon/widget-forms-statistics" in composer
### Changed
- use ajax on grids

## [102.2.3] - 2023-02-27
### Added
- notification with link to wiki

## [102.2.2] - 2022-11-19
### Fixed
- fix setUp() declaration in tests
### Added
- admin note field
- mass delete action for form records

## [102.2.1] - 2022-10-22
### Fixed
- fix error during setup:upgrade for fresh installation

## [102.2.0] - 2022-10-22
### Added
- form identifier
- field identifier
- form property in form record attribute
- added alekseon/custom-forms-frontend requirerment to composer
### Changed
- max length validator modifications
- moved table definition to db_schema.xml

## [102.1.2] - 2022-10-13
### Changed
- chenged composer requirements
- small fix in setup upgrade

## [102.1.1] - 2022-10-12
### Changed
- attribute_code field length in DB to 255 chars
### Added
- attribute source: TextFormAttributes

## [102.1.0] - 2022-10-10
### Changed
- Move new entity mail notification functionality to alekseon/custom-forms-email-notification

## [102.0.8] - 2022-10-05
### Added
- compability to attribute default values

## [102.0.7] - 2022-10-04
### Changed
- chenged composer requirements

## [102.0.6] - 2022-09-27
### Added
- created at field for form
- hashed images names and directory name for images (needs eav in version 101.0.11)
- input params (needs eav in version 101.0.11)

## [102.0.5] - 2022-08-02
### Fixed
- fix visibility of configuration

## [102.0.4] - 2020-05-02
### Added
- csv and excel exports
- show custome forms in adminhtml menu
- permissions to view and save custom forms

## [102.0.3] - 2020-04-14
### Fixed
- fixed issue with missing created_at column on fresh installation

## [102.0.1] - 2020-04-01
### Added
- new entity notifiaction email
- option sources for select attributes
- form record created at column
- record attribute variables for events

## [102.0.0] - 2020-10-15
### Added
- Added compatibility with Magento 2.4.0

## [101.0.0] - 2020-04-27
### Added
- init

