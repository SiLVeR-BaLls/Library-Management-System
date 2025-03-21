<div class="form-step">
    <p class="top"><b class="tile">Site Information</b></p>

    <div class="group-1">

        <div class="group-box">
            <p class="tile">Account Information</p>

            

            <div class="text-group">
                <label for="IDno">ID Number:</label> <!-- Added label text -->
                <input type="text" id="IDno" name="IDno" class="box" placeholder="Enter ID (if Manual)" required>
            </div>


            <div class="text-group">
                <label for="U_Type">User Type</label>
                <select class="box" name="U_Type" id="U_Type" required>
                    <option value="" selected disabled>Select User Type</option>
                    <option value="student">Student</option>
                    <option value="professor">Professor</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

        </div>

        <div class="group-box">
            <p class="tile">Student Information</p>

            <div class="text-group">
                <label for="college">College</label>
                <select class="box" id="college" name="college" required>
                    <option value="" selected disabled>Select College</option>
                    <option value="cas">College of Arts and Sciences</option>
                    <option value="cea">College of Engineering and Architecture</option>
                    <option value="coe">College of Education</option>
                    <option value="cit">College of Industrial Technology</option>
                </select>
            </div>

            <div class="text-group">
                <label for="course">Course</label>
                <select class="box" id="course" name="course" required>
                    <option value="" selected disabled>Select Course</option>
                    <option value="course1">Course 1</option> <!-- Placeholder options -->
                    <option value="course2">Course 2</option>
                    <option value="course3">Course 3</option>
                </select>
            </div>

            <div class="text-group">
                <label for="yrLVL">Year and Section</label>
                <select class="box" id="yrLVL" name="yrLVL" required>
                <option value="" selected disabled>Select Year and Section</option>
                            <?php for ($year = 1; $year <= 5; $year++): ?>
                                <?php foreach (['A', 'B', 'C', 'D'] as $section): ?>
                                    <option value="<?php echo $year . ' ' . $section; ?>" <?php echo (isset($user['yrLVL']) && $user['yrLVL'] == "$year $section") ? 'selected' : ''; ?>>
                                        <?php echo $year . ' ' . $section; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endfor; ?>
                </select>
            </div>

        </div>
    </div>
</div>