<?php
include '../config.php';
?>

<div class="flex">
    <!-- Sidebar -->
    <div class="sidebar">
         <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?></div>

    <!-- Main Content -->
    <div class="flex flex-col w-screen">
        <!-- Header -->
        <?php include 'include/header.php'; ?>

        <!-- About Section -->
        <div class="p-4 flex-grow bg-gray-100">
            <div class="z-10 p-6 bg-white rounded-lg shadow-lg">
                <div class="flex items-center mb-4">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixid=MnwzNjUyOXwwfDF8c2VhcmNofDF8fGxpYnJhcnl8ZW58MHx8fHwxNjkzNzYyNzYz&ixlib=rb-1.2.1&q=80&w=200" 
                         alt="Library Icon" class="w-20 h-20 rounded-full shadow-md mr-3">
                    <h2 class="text-2xl font-bold text-gray-800">Welcome to the Library Management System</h2>
                </div>
                <p class="text-gray-700 text-base flex items-center">
                    <i class="fa fa-book text-blue-500 mr-2"></i> Discover a seamless way to manage your library. Our system simplifies book cataloging, borrowing, and returning, ensuring an efficient and user-friendly experience.
                </p>
                <p class="text-gray-700 text-base mt-3 flex items-center">
                    <i class="fa fa-users text-purple-500 mr-2"></i> Empowering knowledge, one book at a time. Join us in creating a smarter, more connected library ecosystem.
                </p>
                <p class="text-gray-700 text-base mt-3 font-semibold flex items-center">
                    <i class="fa fa-lightbulb text-yellow-500 mr-2"></i> "Your gateway to endless learning."
                </p>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixid=MnwzNjUyOXwwfDF8c2VhcmNofDV8fGxpYnJhcnl8ZW58MHx8fHwxNjkzNzYyNzYz&ixlib=rb-1.2.1&q=80&w=600" 
                             alt="Bookshelf" class="w-full h-40 object-cover rounded-lg shadow-md">
                        <p class="text-gray-700 text-center mt-2"><i class="fa fa-bookmark text-green-500"></i> Explore our extensive collection of books.</p>
                    </div>
                    <div>
                        <img src="https://images.unsplash.com/photo-1593642532973-d31b6557fa68?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fGxpYnJhcnl8ZW58MHx8fHwxNjkzNzYyNzYz&ixlib=rb-1.2.1&q=80&w=600" 
                             alt="Library Interior" class="w-full h-40 object-cover rounded-lg shadow-md">
                        <p class="text-gray-700 text-center mt-2"><i class="fa fa-graduation-cap text-blue-500"></i> A space for learning and growth.</p>
                    </div>
                </div>
                <p class="text-gray-700 text-base mt-4">
                    Our Library Management System is designed to cater to the needs of students, staff, and administrators. With features like advanced search, real-time availability, and personalized recommendations, we aim to make your library experience enjoyable and productive.
                </p>
                <p class="text-gray-700 text-base mt-3">
                    Whether you're looking for academic resources, leisure reading, or research materials, our system has you covered. Explore our extensive catalog and discover the world of knowledge at your fingertips.
                </p>
                <p class="text-gray-700 text-base mt-3 font-semibold">
                    "A library is not a luxury but one of the necessities of life." – Henry Ward Beecher
                </p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
