<?php echo $errorsHtml ?>
<form method="post" action="?verify=1">
    <p><b>Enter email address:</b></p>
    <input type="text" name="email" required placeholder="Email" value="<?php echo isset($_POST['email'])? $_POST['email'] : ''; ?>">
    <p><b>Country code:</b></p>
    <input type="text" name="code" required placeholder="Country code" value="<?php echo isset($_POST['code'])? $_POST['code'] : ''; ?>">
    <p><b>Cellphone:</b></p>
    <input type="text" name="phone" required placeholder="Phone" value="<?php echo isset($_POST['phone'])? $_POST['phone'] : ''; ?>">
    <br><br>
    <input type="submit" value="Send">
</form>