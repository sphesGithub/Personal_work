<?php
// Start a session if one is not already started
session_start();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title> About | Planify</title>
    <link href="style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>

<body>

    <?php include("components/navbar.php") ?>
    <?php $page_title="About Us //The_Boys"; include("components/header.php") ?>

    <section class="container flex flex-col-mobile" id="about-us">
        <div class="slide-in">
            <img id="pic_1" src="images/personal/Tebza-1.jpeg" alt="butcher" width="300px" height="300px"
                class="spinImage" />
            <h2>(Tebogo-G21N7397)</h2>
            <p>
                Tebogo is a visionary web developer who excels in the art of creating captivating and functional
                websites. They meticulously think through every detail,
                crafting pixel-perfect layouts and seamlessly responsive designs. Tebogo thrives on the challenge of
                problem-solving, exploring innovative solutions to complex issues. Their commitment to
                continuous learning ensures they stay at the forefront of web development trends, making them a
                sought-after asset in the ever-evolving digital landscape.
            </p>
        </div>
        <div class="slide-in2">
            <img id="pic_2" src="images/personal/Sihle-1.jpeg" alt="homelander" width="300px" height="300px"
                class="spinImage" />

            <h2>(Siphesihle-G21S8633)</h2>

            <p> Sihle is a bold and driven web developer, known for his critical-thinking skills and smart approach to
                design.
                His confident attitude shines through in his work, where he fearlessly tackles challenges and embraces
                innovative solutions. Sihle's attention to detail and commitment to user-friendly design make him a
                critical-thinker who creates visually stunning and highly functional websites.
                He's a true asset to any team in the ever-evolving world of web development.</p>
        </div>
    </section>
    <div id="spin_button">
        <button class="spin_button_start btn-primary send-btn">Start Spinning</button>
        <button class="spin_button_stop btn-secondary send-btn">Stop Spinning</button>
    </div>
    <?php include("components/footer.php") ?>
</body>

</html>