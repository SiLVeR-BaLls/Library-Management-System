<!-- Sticky Sidebar -->
 
<aside id="fullSidebar" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;" class="h-full z-10 flex flex-col w-1/6 -w-60 min-w-48 sticky top-0 shadow-lg">
    <div class="sticky top-0" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;">

        <div class="flex items-center justify-between p-4 border-b ">
            <p style="color : <?= $text2 ?>;" class="font-bold text-lg">Faculty Panel</p>
        </div>

        <nav class="flex-grow ">
            <ul class="space-y-1 py-4">
                <!-- browse button -->
                <li><a href="index.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'ViewBook.php', 'BookList.php', 'ViewCopy.php', 'AddBookCopy.php', 'edit_book.php'])) ? $button_active : $sidebar_hover ?>;">Browse</a></li>
                <!-- search button -->
                <li><a href="search.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (in_array(basename($_SERVER['PHP_SELF']), ['search.php'])) ? $button_active : $sidebar_hover ?>;">Search</a></li>
             
                <!-- Dashboard Dropdown -->
                        <li><a href="profile.php"  class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? $button_active : $sidebar_hover ?>;">Profile</a></li>
                        <li><a href="Myborrow.php"  class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Myborrow.php') ? $button_active : $sidebar_hover ?>;">My Borrow</a></li>
                        <li><a href="Myreturn.php" style="color : <?= $text1?>;"  class="btn block py-2 px-4 <?php echo (basename($_SERVER['PHP_SELF']) == 'Myreturn.php') ? $button_active : $sidebar_hover ?>;">My Returned</a></li>
                        <li><a href="Myreserve.php" class="btn block py-2 px-4" style="color: <?= $text1 ?>; background-color: <?= (basename($_SERVER['PHP_SELF']) == 'Myreserve.php') ? $button_active : $sidebar_hover ?>;">My Reserve</a></li>
                     </ul>
        </nav>



</aside>


<style>
    @media (max-width: 600px) {
        #fullSidebar {
            display: flex;
        }

        #iconSidebar {
            display: flex;
        }
    }
</style>

