# WordPress Blog Filter Repository
 WordPress Blog Filter Shortcode

# Custom News Filter Shortcode

This project provides a custom WordPress shortcode `[custom-news]` that allows users to filter news posts by category and tag, with pagination support.

## Features

- Filter posts by category and tag.
- AJAX-based pagination.
- Responsive design with Bootstrap.
- Reset filters button.
- Loading indicators.

## Installation

### Step 1: Directory Structure

Ensure your theme directory has the following structure:

theme-directory  
  * css  
    * news-styles.css  
  * js  
    * news-scripts.js  

functions.php  
news.php  


### Step 2: Create `news.php`

Create a file named `news.php` in your theme directory and add the necessary code to define the shortcode function, handle AJAX requests, register the shortcode, and create custom helper functions.

### Step 3: Create `news-scripts.js`

Create a file named `news-scripts.js` in the `js` directory. This file will contain the JavaScript needed to handle AJAX requests for filtering and pagination.

### Step 4: Create `news-styles.css`

Create a file named `news-styles.css` in the `css` directory. This file will contain any custom styles needed for the news filter.

### Step 5: Update `functions.php`

Modify your theme’s `functions.php` to enqueue the styles and scripts, include the `news.php` file, and localize the AJAX URL for JavaScript.

### Step 6: Embed the Shortcode

To use the shortcode, simply add `[custom-news]` in the editor of any post or page where you want the filtered news to appear.

## Usage

- **Filtering by Category and Tag:** Users can select a category or tag from the dropdown menus to filter the news posts.
- **Pagination:** The posts are paginated, and users can navigate through the pages using AJAX-based pagination.
- **Reset Filters:** Users can reset the filters by clicking the "Reset" button.

## Notes

- Ensure that your theme is using Bootstrap for styling to match the provided classes.
- The AJAX URL is localized using `wp_localize_script` to ensure compatibility with different site configurations.
- Only published posts are included in the filter results.

By following these instructions, you can set up and use the custom news filter shortcode on your WordPress site.

*End*