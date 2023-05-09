<?php
require_once 'inc/pre.inc.php';

define('TITLE', 'login');
require_once 'tpl/head.inc.php';

define('INC_FOOTER', 'tpl/ano_foot.inc.php');

define('TAG_STRONG_OPEN', '<strong>');
define('TAG_STRONG_CLOSE', '</strong>');
define('TAG_BUTTON_CLOSE', '</button>');
define('TAG_SPANBUTTON_CLOSE', '</span></button>');

if (version_compare(phpversion(), '7.0', '<')) {
    echo '<div class="container"><div class="alert alert-danger" role="alert">Your PHP version ' .
         phpversion() . ' might be too old, expecting at least 7.0</div></div>';
} // if

require_once 'tpl/ano_nav.inc.php';
?>

<div class="container" id="infoheader">

</div>
<!-- /container -->

<!-- class container for fixed max width, or container-fluid for maximum width -->
<div class="container">
<div id="dialog" title="Details">I'm a dialog</div>

<form method="post" action="dologin.php">
  <div class="form-group">
    <label for="authEmail">Email address</label>
    <input type="email" class="form-control" name="authEmail" id="authEmail" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="authPassword">Password</label>
    <input type="password" class="form-control" name="authPassword" id="authPassword" placeholder="Password">
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" name="authRemember" id="authRemember">
    <label class="form-check-label" for="authRemember">Remember me</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>

<?php
require_once INC_FOOTER;
?>
