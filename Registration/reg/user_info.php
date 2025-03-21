<div class="form-step form-step-active">
    <p class="top"><b>User Information</b></p>

    <div class="group1">
        <div class="text-group">
            <label for="Fname">Firstname</label>
            <input id="Fname" name="Fname" class="box" type="text" placeholder="Firstname" required>
        </div>
        <div class="text-group">
            <label for="Sname">Surname</label>
            <input id="Sname" name="Sname" class="box" type="text" placeholder="Surname" required>
        </div>
        <div class="text-group">
            <label for="Mname">Middle Name</label>
            <input id="Mname" name="Mname" class="box" type="text" placeholder="Middle Name" required>
        </div>
    </div>

    <div class="group-1">
        <div class="group-box">
            <p class="tile">Basic Information</p>
            <div class="text-group">
                <label for="gender">Gender</label>
                <select class="box" name="gender" id="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="">Male</option>
                    <option value="f">Female</option>
                    <option value="o">Other</option>
                </select>
            </div>
            <div class="text-group">
                <label for="DOB">Birthdate</label>
                <input id="DOB" name="DOB" class="box" type="date" required>
            </div>
            <div class="text-group">
                <label for="Ename">Extension</label>
                <input class="box" name="Ename" id="Ename" placeholder="Enter Extension">
            </div>
        </div>

        <!-- Address Section -->
        <div class="group-box">
            <p class="tile">Address</p>
            <div class="text-group">
                <label for="municipality">Municipality/City</label>
                <input id="municipality" name="municipality" class="box" type="text" placeholder="Enter Municipality/City" required>
            </div>
           
            <div class="text-group">
                <label for="barangay">Barangay</label>
                <input id="barangay" name="barangay" class="box" type="text" placeholder="Enter Barangay" required>
            </div>
            <div class="text-group">
                <label for="province">Province</label>
                <input id="province" name="province" class="box" type="text" placeholder="Enter Province" required>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="group-box">
            <p class="tile">Contact</p>
            <div class="text-group">
                <label for="contact">Contact No. 1</label>
                <input id="contact" name="contact" class="box" type="text" placeholder="09*********" required pattern="^\d{11}$" title="Please enter a valid 11-digit number">
            </div>
            <div class="text-group">
                <label for="con2">Contact No. 2</label>
                <input id="con2" name="con2" class="box" type="text" placeholder="09*********" pattern="^\d{11}$" title="Please enter a valid 11-digit number">
            </div>
            <div class="text-group">
                <label for="mail1">Email 1</label>
                <input id="mail1" name="mail1" class="box" type="email" placeholder="sample@gmail.com" required>
            </div>
            <div class="text-group">
                <label for="mail2">Email 2</label>
                <input id="mail2" name="mail2" class="box" type="email" placeholder="sample@gmail.com">
            </div>
        </div>
    </div>
</div>
