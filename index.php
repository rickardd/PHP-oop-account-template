<?php
require_once 'core/init.php';

// Salt does not get saved correctly to database.
// *C@��u:z�:���u$,�Q^���91F
// only *C@ will be saved.
//
// DB->delete does not work properly.

// $user =  DB::getInstance()->get( 'users', array('username', '=', 'billy'));

// if (!$user->count()) {
//   echo 'No user';
// } else {
//   echo $user->first()->username;
// }

// $user =  DB::getInstance()->insert( 'users', array(
//   'username' => 'Dale',
//   'password' => 'passowrd',
//   'salt' => 'salt'));

// $user =  DB::getInstance()->update( 'users', 3, array(
//   'password' => 'new_password',
//   'name' => 'Dale Barrett'
//   ));

if(Session::exists('home')){
  echo Session::flash('home');
}

// DELETE FUNCTION SEEMS TO BE BROKEN!
DB::getInstance()->delete('users', array('id', '=', '12'));

// echo Session::get(Config::get('session/session_name'));

$user = new User();
if($user->isLoggedIn()) {
?>
  <p>Hello <a href="<?php echo 'profile.php?user=' . $user->data()->username; ?>" ><?php echo escape($user->data()->username); ?></a></p>

  <ul>
    <li><a href="logout.php">log out</a></li>
    <li><a href="update.php">Update details</a></li>
    <li><a href="changepassword.php">change password</a></li>
  </ul>


<?php

  if($user->hasPermission('admin')) {
    echo '<p>You are an admin!</p>';
  }

  if($user->hasPermission('moderator')) {
    echo '<p>You are a moderator!</p>';
  }

} else {
  echo "<p>You need to <a href='login.php'>log in</a> or <a href='register.php'>register</a></p>";
}

