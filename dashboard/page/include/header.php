<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lms</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col w-full h-screen max-w-full max-h-screen" style="background-color: <?= $background ?>">

    <div style="background-color: <?= $header ?>; color : <?= $text1 ?>;" class="flex z-1000 sticky top-0 shadow-md items-center justify-between w-full h-[4rem] shadow-md">
        <!-- Left side: Logo and Title -->
        <div class="flex items-center gap-4 p-4">
            <a href="#">
                <img src="<?= $logo ?>" alt="Logo" class="w-auto  h-auto max-w-xs max-h-16">
            </a>
            <strong class="text-lg font-semibold">
                Library Management System
            </strong>
        </div>


        <!-- Right side: Notification Bell, User's Photo, and Name -->
        <div class="flex items-center space-x-4 m-4">
            <?php if ($userData): ?>
                <!-- Notification Bell -->
                <ul class="nav justify-content-end">
                    <li class="dropdown">
                        <div class="dropdown-toggle text-light" id="noti_count" style="cursor: pointer;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="counter">0</span><i class="fas fa-bell" style="font-size: 20px;"></i>
                        </div>
                        
                        <div class="dropdown-menu overflow-h-menu dropdown-menu-right">
                            <div class="notification">
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="flex items-center gap-2">
                    <?php if (!empty($userData['photo'])): ?>
                        <img class="w-10 h-10 rounded-full object-cover" src="../../pic/User/<?php echo htmlspecialchars($userData['photo']); ?>" alt="User Photo">
                    <?php else: ?>
                        <img class="w-10 h-10 rounded-full object-cover" src="../../pic/default/user.jpg" alt="Default User Photo">
                    <?php endif; ?>
                    <span class="text-sm font-medium">
                        <strong><?php echo htmlspecialchars($userData['Fname']); ?></strong>
                    </span>
                </div>
                <!-- Logout Button (Icon Changes on Hover) -->
                <div class="py-4 px-4">
                    <a href="logout.php" id="logoutBtn" class="b p-2 rounded-md transition flex items-center justify-center">
                        <div class="bg-white rounded-full p-1 flex items-center justify-center">
                            <img id="logoutIcon" src="../../pic/scr/exit_door.png" alt="Logout" class="w-6 h-6">
                        </div>
                    </a>
                </div>


                <!-- JavaScript for Hover Effect -->
                <script>
                    document.getElementById("logoutBtn").addEventListener("mouseover", function() {
                        document.getElementById("logoutIcon").src = "../../pic/scr/exit_door_for hover.png"; // Change to hover image
                    });

                    document.getElementById("logoutBtn").addEventListener("mouseout", function() {
                        document.getElementById("logoutIcon").src = "../../pic/scr/exit_door.png"; // Revert to normal image
                    });
                </script>


            <?php else: ?>
                <span class="text-sm font-medium">Hello, <strong>Guest</strong></span>
                <a href="registration/log_in.php" class="btn  px-4 py-1 rounded-md hover:bg-blue-600 transition">
                    Log In
                </a>
            <?php endif; ?>


        </div>
    </div>


    <!-- Modal for confirmation -->
    <div id="myModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-lg text-center">
            <h2 class="text-xl font-semibold mb-2">Confirm Log Out?</h2>
            <p class="mb-4">Are you sure you want to leave the page?</p>
            <div class="flex justify-around">
                <button id="confirmBtn" class="bg-blue-500 px-4 py-2 rounded ">Confirm</button>
                <button id="cancelBtn" class="bg-blue-300 px-4 py-2 rounded hover:bg-blue-500">Cancel</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for modal and toggles in the log out -->
    <script>
        // Modal functionality
        const modal = document.getElementById("myModal");
        const logoutBtn = document.getElementById("logoutBtn");
        const confirmBtn = document.getElementById("confirmBtn");
        const cancelBtn = document.getElementById("cancelBtn");

        // Show the modal when clicking the logout button
        logoutBtn.addEventListener("click", function(event) {
            event.preventDefault();
            modal.classList.remove("hidden");
        });

        // Confirm logout
        confirmBtn.addEventListener("click", function() {
            modal.classList.add("hidden");
            window.location.href = "../logout.php"; // Redirect to logout page
        });

        // Cancel logout and close modal
        cancelBtn.addEventListener("click", function() {
            modal.classList.add("hidden");
        });

        // Close the modal if clicking outside the modal content
        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.classList.add("hidden");
            }
        });
    </script>


    <style>
        /* Apply custom button colors */
        .btn {
            background-color: <?= $button ?>;
            color: white;
        }

        /* Button hover state based on DB value */
        .btn:hover {
            background-color: <?= $button_hover ?>;
        }

        /* Button active state based on DB value */
        .btn:active {
            background-color: <?= $button_active ?>;
        }

        /* Sidebar item hover color from DB */
        .sidebar-item:hover {
            background-color: <?= $sidebar_hover ?>;
        }

        /* Sidebar item active color from DB */
        .sidebar-item.active {
            background-color: <?= $sidebar_active ?>;
        }
    </style>
</body>
</html>

    <script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script type="text/javascript">

    $(document).ready(function (){

        $('.notification').load('Ajax/Notification.php');
        $('.counter').text('0').hide();

        var counter = 0;

        $('#form-submit').on('submit', function(event){
            event.preventDefault();
            
            var subject = $('#subject').val().trim();
            var comment = $('#comment').val().trim();

            $('#sub-error').text('');
            $('#com-error').text('');

            if(subject != '' && comment != ''){
                
                $.ajax({
                    type: "POST",
                    url: "Ajax/Ins_notification.php",
                    data: { 'subject' : subject, 'comment' : comment },
                    success: function (response) {
                        var status = JSON.parse(response);
                        if(status.status == 101){
                            counter++;
                            $('.counter').text(counter).show();
                            $('.notification').load('Ajax/Notification.php');
                            $("#form-submit").trigger("reset");
                            console.log(status.msg);
                        }
                        else{
                           console.log(status.msg);
                        }
                    }
                });

            }
            else{
            
                if(subject == ''){
                    $('#sub-error').text("Please Enter Subject");
                }
                if(comment == ''){
                    $('#com-error').text("Please Enter Comment");
                }
            }

        });

        $('#noti_count').on('click',function (){
            counter = 0;
            $('.counter').text('0').hide();
        });

    });

</script>