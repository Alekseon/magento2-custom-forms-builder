# Changelog
All notable changes to this project will be documented in this file.


## [Unreleased]
### Changed
### Fixed
### Added

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

