<?php
// Get the current page filename (e.g., "contact.php", "calendar.php", etc.)
$current_page = basename($_SERVER['PHP_SELF']);
echo "
    <nav id='desktop-nav'class='container flex flex-col-mobile'>
    <a href='index.php'>
    <img src='images/logo.png' alt='Logo' />
    </a> 
    <ul class='flex flex-col-mobile'>
    <li> <a href='index.php' class='" . ($current_page === 'index.php' ? 'active' : '') . "'> Home </a></li>
    <li> <a href='calendar.php' class='" . ($current_page === 'calendar.php' ? 'active' : '') . "' > Calendar </a></li>
    <li> <a href='about.php' class='" . ($current_page === 'about.php' ? 'active' : '') . "' > About </a></li>
    <li> <a href='contact.php' class='" . ($current_page === 'contact.php' ? 'active' : '') . "' > Contact </a></li>
    </ul>";
    if(isset($_SESSION["role"])){

        if($_SESSION["role"] == 'admin'){
            echo"
            <div class='dropdown'>
            <img src='images/profile_pictures/" . $_SESSION['profile_pic'] ."' alt='Avatar' class='avatar'>
            " . $_SESSION['fullname'] ."
                <div class='dropdown-content'>
                <a href='profile.php'>Profile</a>
                <a href='admin-users.php'>users</a>
                <a href='admin-messages.php'>messages</a>
                <a href='systemtest.php'>System info check</a>
                <a href='signout.php'>Sign Out</a>
                </div>
            </div>
            ";
        }
        else{
            echo"
            <div class='dropdown'>
            <img src='images/profile_pictures/" . $_SESSION['profile_pic'] ."' alt='Avatar' class='avatar'>
                " . $_SESSION['fullname'] ."
                <div class='dropdown-content'>
                <a href='profile.php'>Profile</a>
                <a href='systemtest.php'>System info check</a>
                <a href='signout.php'>Sign Out</a>
                </div>
            </div>
            ";
        }
    
    }else{
        echo"
        <div>
            <a href='signup.php' class='btn-secondary'> Sign Up </a>
            <a href='signin.php' class='btn-primary'> Sign In </a>
        </div>
        ";
    }

  echo " </nav >
    <!--Mobile nav view-->
    <nav id='mobile-nav' class='container flex flex-col-mobile'>
    <div id='mobile-header'>
    <a href='index.php'>
    <img src='images/logo.png' alt='Logo' />
    </a>
    <img id='nav-menu-open' src='images/Icons/menu.svg' class='nav-menu-icon' />
    <img id='nav-menu-close'  src='images/Icons/x.svg' class='nav-menu-icon hidden' />
    </div>
    <div id='mobile-menu' class='flex-col-mobile hidden'>
    <ul class='flex flex-col-mobile'>
        <li> <a href='index.php' class='active'> Home </a></li>
        <li> <a href='calendar.php'> Calendar </a></li>
        <li> <a href='about.php'> About </a></li>
        <li> <a href='contact.php'> Contact </a></li>
    </ul>";
    if(isset($_SESSION["role"])){
        echo"
        <div>
            <a href='profile.php'>" . $_SESSION['fullname'] ." </a>
            <a href='signout.php' class='btn-primary'>sign out </a>
        </div>
        ";
    
    }else{
        echo"
        <div>
            <a href='signup.php' class='btn-secondary'> Sign Up </a>
            <a href='signin.php' class='btn-primary'> Sign In </a>
        </div>
        ";
    }


    echo "</div>
    </nav>";

?>