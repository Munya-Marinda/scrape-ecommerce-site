<?php

/*

AUTHOR: MUNYARADZI MARINDA & ChatGPT

*/



// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the Simple HTML DOM library
include_once('./lib/simple-html-dom/simple_html_dom.php');

// Define the target website URL
$domain = 'xxxxxxxx';

// Define the path for the JSON file to save product data
$jsonFilePath = 'products.json';

// Check if the 'scrapedata' query parameter is set and not empty
if (isset($_GET['scrapedata']) && !empty($_GET['scrapedata'])) {
    // Trigger the function to scrape and save product data
    ScrapeData();
}
// Check if the 'scrapeimages' query parameter is set and not empty
elseif (isset($_GET['scrapeimages']) && !empty($_GET['scrapeimages'])) {
    // Trigger the function to scrape and save product images
    ScrapeImages();
}

/**
 * Function to scrape and save product data.
 */
function ScrapeData()
{
    global $domain, $jsonFilePath;

    // Define an array of product categories and their URLs to scrape
    $categories = [
        'Bone & Joint Care Category' => ['/product-category/bone-joint-care-category/'],
        // Add more categories as needed
    ];

    // Create an empty array to store product data
    $productData = [];

    // Iterate through each category and its associated URLs
    foreach ($categories as $category => $links) {
        foreach ($links as $link) {
            // Load the HTML content of the category page
            $html = file_get_html($domain . $link);

            // Find the product list container
            $productList = $html->find('ul.products.columns-3', 0);

            // Check if the product list is found
            if ($productList) {
                // Iterate through each product item
                foreach ($productList->find('li.product') as $productItem) {
                    // Extract product details
                    $productName = $productItem->find('h2.woocommerce-loop-product__title', 0)->plaintext;
                    $productPrice = $productItem->find('span.price bdi', 0)->plaintext;
                    $productImageURL = $productItem->find('img', 0)->src;

                    // Create an associative array for the product
                    $product = [
                        'name' => $productName,
                        'price' => decodeHtmlEntities($productPrice),
                        'image_name' => cleanUpProductName($productName),
                        'image_url' => $productImageURL,
                    ];

                    // Create a category array if it doesn't exist
                    if (!key_exists($category, $productData)) {
                        $productData[$category] = [];
                    }

                    // Add the product to the category array
                    array_push($productData[$category], $product);
                }
            }
        }
    }

    // Convert the array to JSON format
    $jsonData = json_encode($productData, JSON_PRETTY_PRINT);

    // Save the JSON data to the specified file
    file_put_contents($jsonFilePath, $jsonData);

    // Output a success message
    echo 'Product data saved to ' . $jsonFilePath;
}

/**
 * Function to scrape and download product images.
 */
function ScrapeImages()
{
    global $jsonFilePath;

    // Read product data from the JSON file
    $jsonData = json_decode(file_get_contents($jsonFilePath), true);

    // Check if JSON data was parsed successfully
    if ($jsonData === null) {
        echo "Failed to parse JSON data from file: $jsonFilePath\n";
        return;
    }

    // Iterate through each category and its products
    foreach ($jsonData as $categoryName => $products) {
        foreach ($products as $product) {
            // Get the image URL and image file name
            $imageUrl = $product['image_url'];
            $imageFileName = $product['image_name'] . '.jpeg'; // You can specify the desired file extension

            // Set the destination folder for image storage
            $destinationFolder = 'images/thumbnail/';

            // Create the destination folder if it doesn't exist
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder, 0777, true);
            }

            // Define the full image path
            $imagePath = $destinationFolder . $imageFileName;

            // Download and save the image
            file_put_contents($imagePath, file_get_contents($imageUrl));

            // Check if the image was saved successfully
            if (file_exists($imagePath)) {
                echo "Image saved: $imageFileName\n";
            } else {
                echo "Failed to save image: $imageFileName\n";
            }
        }
    }
}

/**
 * Function to decode HTML entities in text.
 * @param string $input The HTML-encoded input text.
 * @return string The decoded text.
 */
function decodeHtmlEntities($input)
{
    // Decode HTML entities
    $decodedText = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return $decodedText;
}

/**
 * Function to clean up product names for use as image file names.
 * @param string $productName The product name.
 * @return string The cleaned-up image file name.
 */
function cleanUpProductName($productName)
{
    // Remove special characters and spaces
    $cleanedName = preg_replace('/[^\w\s-]/', '', $productName);

    // Replace spaces with underscores
    $cleanedName = str_replace(' ', '_', $cleanedName);

    // Convert to lowercase
    $cleanedName = strtolower($cleanedName);

    // Remove duplicate underscores
    $cleanedName = preg_replace('/_+/', '_', $cleanedName);

    return $cleanedName;
}
