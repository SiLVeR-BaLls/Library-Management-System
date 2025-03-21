<div class="form-step">
    <p class="top"><b>User Information</b></p>

    <div class="group-1">
        <div class="group-box">
            <p class="title">Account Information</p>

            <div class="text-group">
                <label for="username">Username</label>
                <input id="username" name="username" class="box" type="text" placeholder="Enter Username" required>
            </div>

            <div class="text-group">
                <label for="password">Password</label>
                <div class="pass">
                    <input id="password" name="password" class="box" type="password" placeholder="Enter Password" required>
                    <span id="password-toggle" class="show-password" onclick="togglePasswordVisibility('password', 'password-toggle')">📚</span>
                </div>
            </div>

            <div class="text-group">
                <label for="password-repeat">Repeat Password</label>
                <div class="pass">
                    <input id="password-repeat" name="password-repeat" class="box" type="password" placeholder="Repeat Password" required>
                    <span id="password-repeat-toggle" class="show-password" onclick="togglePasswordVisibility('password-repeat', 'password-repeat-toggle')">📚</span>
                </div>
            </div>
        </div>
    </div>
</div>
