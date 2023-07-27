<?php
session_start();
include('./common/header.php');
include_once "Restaurant.php";
include_once "select.php";

$isResult = false; // a boolean for displaying changes saved to the client
$failure = ""; // for debugging btn1 if is set or not

// Defining variables as they are showing errors in Review.php input fields
$street;               
$city;
$province;
$postalCode;
$summary;
$rating;
$restaurant;
$restaurantID;
$restaurantRating;
$previousId;
$result; // Success message in Review.php
$postal; $anyErr= false; //for validating postalcode

// ON GET

        function test_input($data) 
        {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        } 
$restaurantID = $_SESSION["restaurantId"];
$restaurantRating = $_SESSION["rating"];

// Retrieving selected Restaurant data from List built in Restaurant class
foreach ($restaurantList as $restaurant)
{
    if ($restaurant->id == $restaurantID) 
    {
        $street = $restaurant->streetName;
        $city = $restaurant->city;
        $province = $restaurant->province;
        $postalCode = $restaurant->postalCode;
        $summary = $restaurant->summary;
        $rating = $restaurant->rating;
        $restaurantRating = $restaurant->rating;
    }
}

// Fending session
if(isset($_POST['endSession']))
{
    // Ending the session
    session_destroy();

    // Redirect to another page or display a logout message
    header("Location:RestaurantIndex.php");
    exit;  
}

// FORM 2 handling HTTP request || User Request // POST REQUEST
if (isset($_POST['submit2'])) 
{
    // Code for processing the form submitted from Review.php 
    $selectedRestaurant = test_input( $_SESSION["restaurantId"]);
    $street = test_input($_POST['street']);
    $city = test_input($_POST['city']);
    $province = test_input($_POST['province']);
    $postalCode = test_input($_POST['zipCode']);
    $summary = test_input($_POST['summary']);
    $rating = test_input($_POST['rating']);
    $postal =  test_input($_POST['zipCode']);
    
    //PostalCode Validation
        if(!empty($postal))
        {
            $expression = '/^([a-zA-Z]\d[a-zA-Z])\ {0,1}(\d[a-zA-Z]\d)$/';
            $postalLower = strtolower(test_input(test_input($postal)));

            if(preg_match($expression, $postalLower))
            { 
                $anyErr= false;
            }
            else
            {
                $anyErr= true;              
            }   
        }

    if (!$anyErr && !empty($street) && !empty($city) && !empty($province) && !empty($postalCode) && !empty($summary) && !empty($rating)) 
    {
            //$previousId = $selectedRestaurant;
            unset($_SESSION["previousId"]);
            // Updating Restaurant list class
            foreach ($restaurantList as $restaurant)
            {
                if ($restaurant->id == $selectedRestaurant) 
                {
                    $restaurant->streetName = $street;
                    $restaurant->city = $city;
                    $restaurant->province = $province;
                    $restaurant->postalCode = $postalCode;
                    $restaurant->summary = $summary;
                    $restaurant->rating = $rating;
                }
            } 
            
            $_SESSION["restaurantId"] = $selectedRestaurant; // Update session with new restaurant ID
            $_SESSION["rating"] = $rating; // Update session with new rating
            
            $isResult = true; // a boolean for displaying changes saved to the client  
            $result = "Revised Restaurant has been saved to: Data/restaurant_review.xml";
            
            // Saving updated data to the XML file
            saveRestaurantData($restaurantList);
   
    }
    elseif ($anyErr) 
    {
        $failure = "Postal Code can not be in that format"; // debugging purpose
    }
    else 
    {
        $failure = "All inputs fields are mandatory!"; // debugging purpose
    }
}  
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Restaurant Review</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>        
        <form action="" id="form2" class="form2" method="post">         
            <div id="dropdown">
                <label for="Street">Street Address:</label>
                <input id="street" name="street" class="enter" type="text" value="<?php echo $street; ?>">  
            </div>
            <div id="dropdown">
                <label for="City">City:</label>
                <input id="city" class="enter" name="city" value="<?php echo $city; ?>" type="text">  
            </div>
            <div id="dropdown">
                <label for="Province">ProvinceState:</label>
                <input id="province" class="enter" name="province" value="<?php echo $province; ?>" type="text">  
            </div>
            <div id="dropdown">
                <label for="ZipCode">PostalZip Code:</label>
                <input id="zipCode" class="enter" name="zipCode" value="<?php echo $postalCode; ?>" type="text">  
            </div>
            <div>
                <label for="Summary">Summary:</label>
                <textarea id="summary" name="summary"><?php echo $summary; ?></textarea>
            </div>

            <div id="dropdown" class="rating">
                <label for="Rating">Rating:</label>
                <select id="rating" class="enter" name="rating">
                    <?php
                    $num = 6;
                    for($i = 1; $i < $num; $i++) {
                        echo '<option value="' . $i . '"';
                        if ($restaurantRating == $i || $rating == $i) {
                            echo ' selected';
                        }
                        echo '>' . $i . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input id="btn2" type="submit" name="submit2" value="Save Changes">
            <button id="endSession"
            onmouseover="this.style.color=#00308F;"
            onmouseout="this.style.color=#0066b2;"
            style=" background: none;
            border: none;
            padding: 0;
            margin-left:20px;
            color: #0066b2;
            text-decoration: underline;
            cursor: pointer;" type="submit" name="endSession">End Session</button>
             <br>  <br>
            <p style=" margin-left:130px; color: red;"><?php echo $failure; ?></p>
            <?php
            if ($isResult) {
                echo '<div id="result">' .
                     '<p id="resultP">' . $result . '</p>' .
                     '</div>';
            }
            ?>
        </form>
    </body>
</html>
<?php include('./common/footer.php'); ?>
