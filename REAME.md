# PHP Web Scraping and Image Download Script Documentation

This document provides an overview and documentation for the PHP script used for web scraping product data and downloading product images from the website "xxxxxxxx".

**Table of Contents**

1. **Introduction**

   - Purpose
   - Prerequisites

2. **Script Overview**

   - Key Features
   - How to Use

3. **Functions**
   - `ScrapeData()`
   - `ScrapeImages()`
   - `decodeHtmlEntities()`
   - `cleanUpProductName()`

---

**1. Introduction**

**Purpose:**
The PHP script is designed to scrape product data and images from the specified website and save them as JSON data and image files, respectively. This script can be useful for automating the extraction of product information for further processing or analysis.

**Prerequisites:**

- PHP environment with the Simple HTML DOM library.
- Web server or local development environment.
- Access to the target website, "xxxxxxxx"

**2. Script Overview**

**Key Features:**

- Scrapes product data from specific categories on the website.
- Saves product data as JSON format in the specified JSON file.
- Downloads and saves product images to the "images" folder.
- Provides two main functions: `ScrapeData()` and `ScrapeImages()` for data scraping and image downloading, respectively.

**How to Use:**

- Include the Simple HTML DOM library by ensuring it's available in the `lib/simple-html-dom/` directory.
- Configure the script by specifying the `$domain` (website URL) and `$jsonFilePath` (JSON file path).
- Access the script via a web server or localhost with query parameters to trigger scraping and image downloading.

**3. Functions**

**`ScrapeData()` Function**

- Purpose: Scrapes product data from the specified website categories, processes it, and saves it as JSON.
- Usage: Access the script with the `?scrapedata` query parameter.
- Steps:
  - Defines an array of product categories and their respective URLs to scrape.
  - Initializes an empty array, `$productData`, to store product information.
  - Iterates through categories and their URLs.
  - Loads the HTML content of the category pages.
  - Extracts product details such as name, price, image URL.
  - Organizes product data by category.
  - Converts product data to JSON and saves it to the specified JSON file.
  - Outputs a success message.

**`ScrapeImages()` Function**

- Purpose: Downloads and saves product images using the product data from the JSON file.
- Usage: Access the script with the `?scrapeimages` query parameter.
- Steps:
  - Reads product data from the specified JSON file.
  - Iterates through product categories and their products.
  - Retrieves image URLs and image names.
  - Specifies a destination folder for image storage.
  - Checks if the destination folder exists; if not, it creates one.
  - Constructs the full image path and downloads the image.
  - Checks if the image was successfully saved and provides feedback.

**`decodeHtmlEntities()` Function**

- Purpose: Decodes HTML entities in text.
- Usage: Called within the script to decode HTML-encoded text.

**`cleanUpProductName()` Function**

- Purpose: Cleans up product names to create suitable image file names.
- Usage: Called within the script to format product names for image file names.

These functions are instrumental in scraping data and managing images for products.

This PHP script simplifies the process of web scraping and image downloading, making it easier to collect and store data from the specified website for further use.

_Note: Ensure that your PHP environment is properly configured, and you have the necessary permissions to read and write files to the specified directories._
