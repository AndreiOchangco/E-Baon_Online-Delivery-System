<?php

session_start(); // Start the session to handle form states and messages


// this array stores error messages for login and registration forms, as well as which form should be active (visible) when the page loads.
$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];

//this variable used to determine which form (login or register) should be displayed as active when the page loads.
$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset(); // Clear session variables to avoid showing old messages/forms on page reload


// it returns an HTML paragraph element containing the error message if there is an error; otherwise, it returns an empty string.
function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}


// it accepts two parameters: the name of a form and the currently active form. It returns the string 'active' if the form name matches the active form, otherwise it returns an empty string.
function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | E-Baon</title>
  <link rel="stylesheet" href="../Css/style.css">
</head>
<body>

  <div class="logo">logo</div>
  <div class="card">

  <form action="Login.php" method="POST">
    <div class="field">
      <span>🙎🏻‍♂️</span>
      <input type="text" name="username" placeholder="user" required>
    </div>

    <div class="field">
      <span>🔒</span>
      <input type="password" name="password" placeholder="password" required>
    </div>

    <div class="field">
      <span>⚙️</span>
      <select name="role" required>
        <option disabled selected>role</option>
        <option value="admin">admin</option>
        <option value="customer">customer</option>
        <option value="delivery">delivery ID</option>
      </select>
    </div>

    <button class="btn-login" type="submit">LOGIN</button>
  </form>

  <div class="forgot">Forgot Password</div>

  <?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>
</div>

</body>
</html>
