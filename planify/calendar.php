<?php include "secure.php" ?>
<?php include "config/config.php" ?>
<?php include "utils/utils.php" ?>
<?php
 // define variables and set to empty
$titleErr = $eventDateErr = $startTimeErr =$endTimeErr = $timeErr = $formErr = $formSuccess = "";
$title = $eventDate = $startTime = $endTime ="";
$user_id = $_SESSION['user_id'];

// checks if form has been submitted 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        //load form values
        $title = test_input($_POST["title"]);
        $eventDate = test_input($_POST["eventDate"]);
        $startTime = test_input($_POST["startTime"]);
        $endTime = test_input($_POST["endTime"]);
    
        //check for required fields
        if(empty($title)){
            $titleErr = "Title is empty";
        }
        if(empty($eventDate)){
            $eventDateErr = "Date is required";
        }
        if(empty($startTime)){
            $startTimeErr= "Start time is required";
        }
        if(empty($endTime)){
            $endTimeErr = "End time is required";
        }
 
        // more form validation
        if(strlen($title) > 25){
            $titleErr = "title too long! max is 25 characters.";
        }
        // Convert the input times to Unix timestamps for comparison
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);

        if(!empty($startTime) && !empty($endTime)){
        if ($startTimestamp >= $endTimestamp) {
            $timeErr = "Start time cannot be ahead of or equal to end time.";
          }
        }
          
        if($titleErr== "" && $eventDateErr == "" && $startTimeErr == "" && $endTimeErr == "" && $timeErr == "" ){
            // form had no errors, continue with submission.
        
            // Create a DateTime object from the input
            $date = new DateTime($eventDate);
            $formattedDate = $date->format('Y-m-d');
            // insert data to entries
            $new_event_query = "INSERT INTO event (user_id, title, date, start_time, end_time)
            VALUES ('$user_id', '$title', '$formattedDate', '$startTime', '$endTime')";

            if ($conn->query($new_event_query) === TRUE) {
                // success message
                $formSuccess = "Event added successfully";
                // clear the form 
                $title = $eventDate = $startTime = $endTime ="";
            } else {
                $formErr = "Error adding your data to the database: " . $conn->error;
            }


        }
        else{
            $formErr = "Could not add event, please fix your errors and try again.";
            
        }
 }
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> Calendar | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <span id="user_id" class="hidden"><?php echo $user_id ?> </span>
    <div id="calendar-container" style="margin-top: 20px;">
        <div class="invalid-feedback"> <?php echo $formErr ?>
        </div>
        <div class="valid-feedback"> <?php echo $formSuccess ?>
        </div>
        <div id="top-div">
            <button id="add_event_button" class="btn-primary">Add event</button>
            <div class="icon-button">
                <button name="previous_month">
                    <img src="images/Icons/chevron-left.svg" alt="previous month icon">
                </button>
                <h2 id="current_month"></h2>
                <button name="next_month">
                    <img src="images/Icons/chevron-right.svg" alt="next month icon">
                </button>
            </div>
        </div>
        <div id="days_of_week"> </div>
        <div id="calendar"></div>
    </div>

    <div id="event_form" class="form_center-div hidden" style="background-image: none;">
        <div class="events-div-form">
            <form id="add_event_form_section" action="calendar.php" method="POST" novalidate>
                <label>Title
                    <input type="text" name="title" placeholder="Tebza's birthday!" value="<?php echo $title ?>" />
                    <div class="invalid-feedback"> <?php echo $titleErr ?>
                    </div>
                </label>
                <br /><br />
                <label>Date
                    <input type="date" name="eventDate" required value="<?php echo $eventDate ?>" />
                    <div class="invalid-feedback"> <?php echo $eventDateErr ?>
                    </div>
                </label>
                <br /><br />
                <label>Start Time
                    <input type="time" name="startTime" required value="<?php echo $startTime ?>" />
                    <div class="invalid-feedback"> <?php echo $startTimeErr ?>
                    </div>
                </label>
                <br /><br />
                <label>End Time
                    <input type="time" name="endTime" required value="<?php echo $endTime ?>" />
                    <div class="invalid-feedback"> <?php echo $endTimeErr ?>
                    </div>
                    <div class="invalid-feedback"> <?php echo $timeErr ?>
                    </div>
                </label>
                <div class="button-container">
                    <input type="submit" class="btn-primary send-btn" value="Add">
                    <button id="discard_button" class="btn-secondary send-btn">Discard</button>
                </div>
            </form>
        </div>
    </div>
    <?php include("components/footer.php") ?>
</body>

</html>