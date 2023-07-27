<?php
include_once "Restaurant.php";

// Defining variables used to store values in session

$rating;
$restaurantID =$_SESSION["restaurantId"];
$failure = ""; // for debugging purposes // last lines

global $restaurantSelected;

if (isset($_POST['restaurant'])) {
    $selectedRestaurantId = $_POST['restaurant'];
    $restaurantID = $_POST['restaurant'];
    // for keeping restaurant name checked in select option USING ID
    $_SESSION["restaurantId"] = $_POST['restaurant'];
    // Code for processing the form submitted from RestaurantOverview.php

    // Retrieving selected Restaurant data from List built in Restaurant class 
    // debugging purpose  first i disable exit or going to another page so we stay on this page line 69
    foreach ($restaurantList as $restaurant) {
        if ($restaurant->id == $restaurantID) {

            //saving selected restaurant  to a new list see below debugging line 110
            $restaurantSelected = $restaurant;
        }
    }


    //$selectedRestaurant = getRestaurantById($selectedRestaurantId);

    if (!empty($restaurantSelected)) {
        $rating = $restaurantSelected->rating;
        // for keeping restaurant rate is checked in select option
        $_SESSION["rating"] = $rating;

        // Redirecting to the Review.php page
        header("Location:ResraurantOverView.php");
        exit;
    } else {
        $failure = "You must select a restaurant you want to view or edit !"; //debugging purpose
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
    <form action="" id="form" method="post">
        <h2>Online Restaurant Review</h2>

        <p>Select a restaurant from a dropdown list to review or edit its review </p>
            <div id="dropdown">
                <label for="Restaurants">Restaurant:</label>  
                <select id="enter" name="restaurant" class="enter">
                <?php foreach ($restaurantList as $restaurant) : ?>
                    <option value="<?php echo $restaurant->id; ?>" <?php echo ($restaurant->id == $restaurantID) ? 'selected' : ''; ?>><?php echo test_input($restaurant->name); ?></option>
                <?php endforeach; ?>
                </select>       
            </div>
    </form>
</body>

<script>
    document.getElementById("enter").addEventListener("change", function() {
        document.getElementById("form").submit();
    });
</script>

</html>


