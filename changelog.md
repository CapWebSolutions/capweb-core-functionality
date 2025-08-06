# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [5.0.1] - 2025-08-06

### Remove

- Duplicated and no longer needed code after Cursor AI refactor.
- Entire plugin lib/ folder tree 

## [5.0.0] - 2025-08-06

### Added

- Complete refactoring of plugin from v4 to modern WordPress plugin structure
- Default featured image functionality
- Admin settings page under Tools > Default Featured Image
- Media library integration for selecting default image
- Automatic display of default image when posts lack featured images
- Support for all WordPress image sizes
- Comprehensive filtering system for featured image display
- Portfolio custom post type with categories and tags
- Editor style refresh functionality
- General utility functions migrated from v4
- jQuery functionality for smooth scrolling and toggle elements
- CSS styles for content boxes, toggles, and callouts
- Gravity Forms integration and enhancements
- Various admin customizations and improvements

### Migrated from v4 (excluding WooCommerce and Genesis-specific functionality)

- Post types and taxonomies registration
- Editor style refresh functionality
- General utility functions and admin customizations
- jQuery and CSS assets
- Gravity Forms enhancements and deregistration
- Social sharing functionality (Simple Social Icons, Scriptless Social Sharing)
- Custom login styling and functionality
- Various WordPress hooks and filters
- Asset management and enqueuing

### Technical Details

- Added `Capweb_Core_Functionality_Admin` class methods for settings management
- Added `Capweb_Core_Functionality_Public` class methods for frontend filtering
- Added `Capweb_Core_Functionality_Post_Types` class for custom post types
- Added `Capweb_Core_Functionality_Taxonomies` class for custom taxonomies
- Added `Capweb_Core_Functionality_Editor_Styles` class for editor functionality
- Implemented WordPress filters: `post_thumbnail_html`, `post_thumbnail_id`, `post_thumbnail_url`, `post_thumbnail_alt`
- Added CSS styling for admin settings page and public styles
- Created comprehensive documentation
- Modernized plugin structure with proper class organization

## [4.0.0] - 2024-08-08

### Changed

- Complete reworking of plugin code structure. No going back. 
- Updated for v2024 of Cap Web Solutions' website.

## [3.64.2] - 2024-01-31

### Added
 - Added snippet to auto complete woo paid orders. wootweaks-snippets. 

## [3.64.1] - 2024-01-31

### Added

- Social sharing tweaks file to house all social related customizations. 
- Social sharing to Threads (WIP).
- This Changelog. 

### Changed

- Moved social sharing from general.php to own file. 

## [3.64.0] - 2024-01-30

### Added

- Add assets/images folder with icons.

### Fixed

- Adjust image location for icons. 

## [3.16.1] - 2022-03-16

### Changed

- Coordinated all versions to Git. Think I have this git thing working. Now off to the races

## [3.16.1] - 2021-12-02

### Changed

- Merging of live plugin back into Github repo to preserve history. Remove extraneous code tht is not being used. Originally this was pulled in with the starter plugin and never removed. Getting set up for the next big iteration of the website and subsequent changes to the core functionality plugin. 
