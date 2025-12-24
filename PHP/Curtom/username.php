<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['username'])) {
    $userData['username'] = $_SESSION['username'];
    $userData['chucvu'] = $_SESSION['chucvu'];
    file_put_contents('../Curtom/user_data.php', '<?php $userData = ' . var_export($userData, true) . ';');
}
?>