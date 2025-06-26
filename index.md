<?php
session_start();
require_once 'dbcon.php';
include_once 'header.php';
?>
    <main class="main-wrapper">
        <h2>Welcome to the Basic Course Registration System</h2>
        <p>Here you can register for courses, view available courses, and manage your enrollments.</p>
        <p>To get started,</p>

        <div class="logs">
            <button class="btns"><a href="signup.php">Sign up</a></button> 
            or 
            <button class="btns"><a href="login.php">Log in</a></button>              
        </div>
    </main>

    <?php
    include_once 'footer.php';
    ?>
