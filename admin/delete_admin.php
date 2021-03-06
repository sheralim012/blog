<?php

require_once('../assets/src/functions.php');
require_once('../assets/src/session.php');

if (!isset($_SESSION['user_id'])) {
    redirect_to('login.php');
}

$user_id = empty($_GET['user_id']) ? 0 : escape_string($_GET['user_id']);

if (isset($_POST['submit'])) {
    $username = escape_string(trim($_POST['username']));
    $old_pwd = trim($_POST['old_pwd']);
    if (check_username($username, $user_id) && password_confirm($user_id, $old_pwd)) {
        if (delete_user($user_id)) {
            $_SESSION['messages'][] = 'Account has been deleted.';
            redirect_to('manage_admins.php');
        } else {
            $_SESSION['messages'][] = "Account was not deleted.";
        }
    } else {
        $_SESSION['messages'][] = "Account was not deleted.";
    }
}

$result = get_user_by_id($user_id);
$result = fetch_assoc($result);

?>

<?php require_once '../assets/layouts/admin_header.php'; ?>

<?php if ($result != null) { ?>

    <div class="container">
        <h2 class="text-center">Delete Admin</h2>
        <form action='<?php echo $_SERVER["PHP_SELF"] . "?user_id=" . $user_id; ?>' method='POST'
              class="form-horizontal">

            <?php require_once('../assets/layouts/messages.php'); ?>

            <div class="form-group">
                <label class="col-sm-4 control-label">Email</label>
                <div class="col-sm-4">
                    <input type="email" name="username" value="<?php echo $result['username']; ?>" class="form-control" placeholder="user@domain.com" maxlength="255" pattern="^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Old Password</label>
                <div class="col-sm-4">
                    <input type="password" name="old_pwd" class="form-control" placeholder="Old Password" minlength="8" maxlength="15" pattern="^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                    <input type="submit" name="submit" value="Delete Admin" class="btn btn-primary btn-block">
                </div>
            </div>
        </form>
    </div>

<?php } else { ?>

    <h2>No record was found.</h2>

<?php } ?>

<?php require_once '../assets/layouts/admin_footer.php'; ?>
