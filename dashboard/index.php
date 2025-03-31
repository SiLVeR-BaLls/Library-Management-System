<?php

// Redirect to root if IDno is blank
if ( $idno) {
    header("Location: /lms");
    exit();
}
?>
    <link rel="stylesheet" href="index.css">


    <div class="container">
        <div class="logo">
        <a href="#">
                <img src="<?= $logo ?>" alt="Logo" class="w-auto  h-auto max-w-xs max-h-16">
            </a>
            </div>
        <h1>Welcome to Library Management System</h1>
        <p>We are delighted to have you here. Our library management system is designed to enhance your library experience by providing easy access to our vast collection of books, journals, and digital resources.</p>
        <center>
            <a href="../Registration/log_in.php" style="text-decoration: none;" class="button">
                <button class="animated-button">
                    <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
                    </svg>
                    <span class="text">Get Started</span>
                    <span class="circle"></span>
                    <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
                    </svg>
                </button>
            </a>
        </center>
    </div>

