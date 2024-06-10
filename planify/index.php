<?php
// Start a session if one is not already started
session_start();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> Home | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>

    <header id="hero">
        <div class="container inner-hero">
            <div class="intro-text slide-in">
                <h1>Welcome to Planify: Your Ultimate Event Planner and Tracker!</h1>
                <p>At Planify, we understand that life is a series of events, big and small, that deserve to be <br />
                    celebrated and organized with precision. That's why we've designed the ultimate event <br />
                    planning and tracking tool to help you manage your events effortlessly and ensure that <br />
                    every moment is memorable.</p>

                <div>
                    <video controls id="home-video" title="watch me only when you are alone!" preload="auto">
                        <source src="videos/writing_in_diary.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <p>Don't know how to organise your calendar? check <a
                            href="https://www.youtube.com/watch?v=ODXV-fb_c-I&pp=ygUdaG93IHRvIHBsYW4gdXNpbmcgYSBjYWxlbmRhciA%3D"
                            class="link-text" target="_blank">this</a> out</p>
                </div>
            </div>
            <!-- Slideshow container -->
            <div class="slideshow-container slide-in2">

                <!-- Full-width images with number and caption text -->
                <div class="mySlides fade">
                    <img src="images/calendar.jpg" style="width:100%">
                </div>

                <div class="mySlides fade">
                    <img src="images/slideshow/Calendar/slide-show-image-1.jpg" style="width:100%">
                </div>

                <div class="mySlides fade">
                    <img src="images/slideshow/Calendar/slide-show-image-2.jpg" style="width:100%">
                </div>
                <div class="mySlides fade">
                    <img src="images/slideshow/Calendar/slide-show-image-3.jpg" style="width:100%">
                </div>

                <!-- Next and previous buttons -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <br>
        </div>

    </header>
    <?php include("components/footer.php") ?>

</body>

</html>