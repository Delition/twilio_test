<?php echo $errorsHtml ?>
<form method="post" action="?verify=2">
    <p><b>Enter token for verify:</b></p>
    <input type="text" name="token" required placeholder="Token">
    <input type="hidden" name="user_id" value="<?php echo $userId ?>">
    <br><br>
    <input type="submit" value="Send">
</form>