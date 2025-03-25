<?php
include '../config.php';
?>

<!-- Main Content Area with Sidebar and Contact Section -->
<div class="flex">
    <!-- Sidebar PHP Logic -->
    <div class="sidebar">
        <?php include $sidebars[$userType] ?? ''; ?>
    </div>

    <!-- Contact Content Section -->
    <div class="flex flex-col w-screen">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <!-- Contact Section -->
        <div class="p-3 space-y-4">
            <h2 class="text-2xl font-bold mb-4">Contact Us</h2>
            <p class="mb-4">If you have any questions, feel free to reach out to us.</p>

            <form method="post" action="include/process_contact.php" class="space-y-4">
                <div>
                    <label for="name" class="block text-lg font-medium">Your Name</label>
                    <input type="text" name="name" required
                        class="w-full p-2 border rounded-md">
                </div>

                <div>
                    <label for="email" class="block text-lg font-medium">Your Email</label>
                    <input type="email" name="email" required
                        class="w-full p-2 border rounded-md">
                </div>

                <div>
                    <label for="message" class="block text-lg font-medium">Your Message</label>
                    <textarea name="message" rows="4" required
                        class="w-full p-2 border rounded-md"></textarea>
                </div>

                <button type="submit"
                    class="w-full p-2 text-lg font-semibold border rounded-md">
                    Send Message
                </button>
            </form>


        </div>

        <!-- Footer at the Bottom -->
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>