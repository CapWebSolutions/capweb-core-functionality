# Default Featured Image Functionality

This plugin now includes functionality to set a default featured image that will be displayed when blog posts don't have a featured image assigned.

## Features

- **Admin Settings Page**: Located under Tools > Default Featured Image
- **Media Library Integration**: Uses WordPress Media Library for image selection
- **Automatic Display**: Default image is automatically displayed on the frontend when no featured image is set
- **Multiple Image Sizes**: Supports all WordPress image sizes (thumbnail, medium, large, etc.)

## How to Use

### Setting the Default Featured Image

1. Go to **Tools > Default Featured Image** in the WordPress admin
2. Click **Select Image** to open the Media Library
3. Choose an image from your media library
4. Click **Use this image** to select it
5. Click **Save Settings** to save your selection

### Removing the Default Featured Image

1. Go to **Tools > Default Featured Image** in the WordPress admin
2. Click **Remove Image** to clear the default image
3. Click **Save Settings** to confirm

## Technical Details

### Hooks Used

The plugin uses the following WordPress filters to implement the default featured image functionality:

- `post_thumbnail_html` - Filters the HTML output of featured images
- `post_thumbnail_id` - Filters the featured image ID
- `post_thumbnail_url` - Filters the featured image URL
- `post_thumbnail_alt` - Filters the featured image alt text

### Options

The plugin stores the default featured image ID in the WordPress options table as `capweb_default_featured_image_id`.

### Compatibility

This functionality works with:
- All WordPress themes
- All image sizes
- Posts and custom post types
- Any function that uses `get_the_post_thumbnail()`, `the_post_thumbnail()`, or similar functions

## Code Examples

### Getting the Default Featured Image ID
```php
$default_image_id = Capweb_Core_Functionality_Admin::get_default_featured_image_id();
```

### Getting the Default Featured Image URL
```php
$default_image_url = Capweb_Core_Functionality_Admin::get_default_featured_image_url('medium');
```

### Getting the Default Featured Image Alt Text
```php
$default_image_alt = Capweb_Core_Functionality_Admin::get_default_featured_image_alt();
```

## Version History

- **1.0.0**: Initial implementation of default featured image functionality 