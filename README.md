# Auto Featured Images SEO

Automatically adds SEO-friendly featured images to posts without them using Pixabay or Pexels. Supports WP Cron automation for seamless image assignment.

## Table of Contents
- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Requirements](#requirements)
- [Configuration](#configuration)
- [Changelog](#changelog)
- [License](#license)
- [Contributing](#contributing)

---

## Overview
Auto Featured Images SEO helps WordPress site owners automatically assign featured images to posts that are missing them. Images are selected based on post categories using free image sources like **Pixabay** and **Pexels**, improving both the visual appeal of your site and SEO performance.

This plugin is ideal for blogs, content-heavy sites, and multisite installations where manually adding featured images is impractical.

---

## Features
- Automatically adds featured images to posts missing them  
- Category-based image selection for relevance  
- Supports **Pixabay** and **Pexels** APIs  
- Fully automated using WP Cron  
- SEO-friendly image assignment  
- Lightweight and compatible with Gutenberg, Elementor, and Divi  

---

## Installation
1. Download the plugin ZIP from [GitHub](https://github.com/GWeb2015/auto-featured-images-seo)  
2. Upload it to `/wp-content/plugins/` on your WordPress site  
3. Activate the plugin through the “Plugins” menu in WordPress  
4. Optional: configure API keys if not hardcoded (future version will include admin page)

---

## Usage
- Once activated, the plugin will automatically scan posts without featured images and assign appropriate images based on categories  
- WP Cron handles automatic scans at a scheduled interval  
- For demonstration purposes, Pixabay/Pexels API keys are currently **hardcoded in the plugin**  

---

## Requirements
- WordPress 6.0+  
- PHP 7.4+  
- Pixabay or Pexels API key (hardcoded in this version)  
- Internet access for API calls  

---

## Configuration
- Currently, API keys for Pixabay/Pexels are hardcoded for demonstration purposes  
- Future versions will include a WordPress admin settings page to enter API keys and map categories dynamically  
- WP Cron handles automatic assignment of featured images for posts without them  

---

## Changelog
**v1.1**  
- Added category-based image selection  
- Improved WP Cron integration for automation  

**v1.0**  
- Initial release: automatically assigns featured images for posts without them  

---

## License
This plugin is licensed under the **MIT License**. See [LICENSE](LICENSE) for details.  

---

## Contributing
Contributions are welcome! Feel free to fork the repository, submit pull requests, or open issues for bug reports and feature suggestions.
