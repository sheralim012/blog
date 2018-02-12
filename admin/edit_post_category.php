<?php

require_once('../assets/src/functions.php');
require_once('../assets/src/session.php');

if (!isset($_SESSION['user_id'])) {
    redirect_to('login.php');
}

$post_category_id = empty($_GET['post_category_id']) ? 0 : escape_string($_GET['post_category_id']);

if (isset($_POST['submit'])) {
    $post_category_name = escape_string(trim($_POST['post_category_name']));
    $visibility = escape_string(trim($_POST['visibility']));
    if (check_length($post_category_name)) {
        if (update_post_category($post_category_id, $post_category_name, $visibility)) {
            $_SESSION['messages'][] = "Post Category \"{$post_category_name}\" has been updated.";
        } else {
            $_SESSION['messages'][] = "Post Category \"{$post_category_name}\" was not updated.";
        }
    } else {
        $_SESSION['messages'][] = "Post Category \"{$post_category_name}\" was not updated.";
    }
}

$result = get_post_category_by_id($post_category_id, $_SESSION['user_id']);
$result = fetch_assoc($result);

?>

<?php require_once '../assets/layouts/admin_header.php'; ?>

<?php if ($result != null) { ?>

    <<div class="container">
        <h2 class="text-center">Edit Post Category</h2>
        <form action='<?php echo $_SERVER["PHP_SELF"] . "?post_category_id={$post_category_id}"; ?>' method='POST'
              class="form-horizontal">

            <?php require_once('../assets/layouts/messages.php'); ?>

            <div class="form-group">
                <label class="col-sm-4 control-label">Post Category Name</label>
                <div class="col-sm-4">
                    <input type="text" name="post_category_name" value="<?php echo htmlentities($result['post_category_name']); ?>" class="form-control" placeholder="Post Category" maxlength="255" required
                           autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Visibility</label>
                <div class="col-sm-4">
                    <label class="radio-inline"><input type="radio" name="visibility" value="1" <?php echo ($result['visibility'] == 1) ? "checked" : ""; ?>> Yes</label>
                    <label class="radio-inline"><input type="radio" name="visibility" value="0" <?php echo ($result['visibility'] == 0) ? "checked" : ""; ?>> No</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                    <input type="submit" name="submit" value="Edit Post Category" class="btn btn-primary btn-block">
                </div>
            </div>
        </form>
    </div>

<?php } else { ?>

    <h2>No record was found.</h2>

<?php } ?>

<?php require_once '../assets/layouts/admin_footer.php'; ?>
