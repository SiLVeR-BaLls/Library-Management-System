<h2>Login</h2>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
        <fieldset>
        <legend>Login Form</legend>
        <form method="POST">
            <table>
                <tr>
                    <td><label>UserName</label></td>
                    <td><input type="text" name="uname" placeholder="Enter User Name" required></td>
                </tr>
                <tr>
                    <td><label>Password</label></td>
                    <td><input type="password" name="password" placeholder="Enter Password" required></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Login"></td>
                </tr>
            </table>
        </form>
    </fieldset>