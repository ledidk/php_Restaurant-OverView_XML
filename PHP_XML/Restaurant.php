<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Restaurant
 *
 * @author ntak0001
 */
class Restaurant
{
    public $id;
    public $name;
    public $rating;
    public $streetName;
    public $city;
    public $postalCode;
    public $province;
    public $summary;
    public $Provinces;

    public function __construct($id, $name, $rating, $streetName, $city, $postalCode, $province, $summary)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rating = $rating;
        $this->streetName = $streetName;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->province = $province;
        $this->summary = $summary;
    }

    public function provinces()
    {
        $this->Provinces = array("AB", "BC", "MB", "NB", "NL", "NS", "ON", "PE", "QC", "SK", "NT", "NU", "YT");
    }
    
}

// Function to save the updated restaurant data to the XML file
function saveRestaurantData($restaurantList)
{
    $xmlFilePath = "Data/lab3.xml";

    $xml = new SimpleXMLElement('<restaurants></restaurants>');

    foreach ($restaurantList as $restaurant) {
        $xmlRestaurant = $xml->addChild('restaurant');
        $xmlRestaurant->addChild('id', $restaurant->id);
        $xmlRestaurant->addChild('name', $restaurant->name);
        $xmlRestaurant->addChild('rating', $restaurant->rating);
        $xmlRestaurant->addChild('summary', $restaurant->summary);

        $xmlAddress = $xmlRestaurant->addChild('address');
        $xmlAddress->addChild('street', $restaurant->streetName);
        $xmlAddress->addChild('city', $restaurant->city);
        $xmlAddress->addChild('postalCode', $restaurant->postalCode);
        $xmlAddress->addChild('province', $restaurant->province);
    }

    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $xmlString = $dom->saveXML();

    file_put_contents($xmlFilePath, $xmlString);
}

function loadRestaurantData()
{
    $restaurantList = [];
    $xmlFilePath = "Data/lab3.xml"; // XML file path

    libxml_use_internal_errors(true); // Enabling internal error handling as I was getting errors reading my xml file

    $xmlString = file_get_contents($xmlFilePath);

    // Convert the XML string to UTF-8 encoding
    $xmlString = utf8_encode($xmlString);

    $xml = simplexml_load_string($xmlString);

    if ($xml !== false) {
        $restaurantList = []; // Initializing the array

        foreach ($xml->restaurant as $restaurant) {
            $id = (string)$restaurant->id;
            $name = (string)$restaurant->name;
            $rating = (int)$restaurant->rating;
            $streetName = (string)$restaurant->address->street;
            $city = (string)$restaurant->address->city;
            $postalCode = (string)$restaurant->address->postalCode;
            $province = (string)$restaurant->address->province;
            $summary = (string)$restaurant->summary;

            $restaurantObj = new Restaurant($id, $name, $rating, $streetName, $city, $postalCode, $province, $summary);
            $restaurantList[] = $restaurantObj;
        }

    } else {
        echo "Failed to load XML file. Errors:<br>";
        foreach (libxml_get_errors() as $error) {
            echo $error->message . "<br>";
        }
    }

    return $restaurantList;
}

// Calling the function to load the restaurant data
$restaurantList = loadRestaurantData();




