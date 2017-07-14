# PHP: Object Oriented Template for account: registration, login, update etc...

## Source

This template is based of this [tutorial](https://www.youtube.com/watch?v=c_hNNAdyfQk&index=21&list=PLfdtiltiRHWF5Rhuk7k4UAU1_yLAZzhWc#t=7.792864) witch is a good boilerplate for new projects.

## Setup

clone project and import the database-dump to the database. The dump can be find in /database/dump.sql

## Usage

All classes will be loaded automatically with ``spl_autoload_register()`` defined in ``init.php``. ``init.php`` should be included at the top of each php file to allow configurations and class auto loader.

```
spl_autoload_register( function( $class ){
  require_once 'classes/' . $class . '.php';
});
```

### classes

- Config.php
- DB.php
- Input.php
- Session.php
- User.php
- Cookie.php
- Hash.php
- Redirect.php
- Token.php
- Validate.php


### Class: Config

Get a configuration e.g ``Config::get('mysql/host')``

### Class: Session

- ``Session::exists($name)`` Session exists?
- ``Session::put($name, $value)`` set a session
- ``Session::get($name`` get a session
- ``Session::delete($name)`` delete a session
- ``Session::flash($name, $string = '')`` set a flash message

### Class: Cookie

- ``Cookie::exists($name)``
- ``Cookie::get($name)``
- ``Cookie::put($name,  $value, $expiry)``
- ``Cookie::delete($name)``

### Class: User

``$user = new User()``

- ``$user->update( $fields = array(), $id = null)``
- ``$user->create($fields = array())``
- ``$user->find( $user = null )``
- ``$user->login($username = null, $password = null,  $remember = false)``
- ``$user->logout(``
- ``$user->hasPermission($key)``
- ``$user->exists()``
- ``$user->data()``
- ``$user->isLoggedIn()``

### Class: Hash

- ``Hash::make( $string, $salt)``
- ``Hash::salt($length)``
- ``Hash::unique()``

### Class: Redirect

- ``Redirect::to($location)``

### Class: Token

- ``Token::generate()`` generates a unique md5 hash.
- ``Token::check($token)`` Checks if token exists in the session varialbe and then delets it.

***Class: DB***

The database instance is hold with a singleton pattern to make sure the instance is only called once.

***Get something from database*** end display the username...
```
$user =  DB::getInstance()->get( 'users', array('username', '=', 'billy'));

if (!$user->count()) {
  echo 'No user';
} else {
  echo $user->first()->username;
}
```

***Insert to database***
```
DB::getInstance()->insert( 'users', array(
  'username' => 'Dale',
  'password' => 'passowrd',
  'salt' => 'salt'));
```

***Update in database***
```
DB::getInstance()->update( 'users', 3, array(
  'password' => 'new_password',
  'name' => 'Dale Barrett'
  ));
```

***Delete in database***
```
DB::getInstance()->delete('users', array('id', '=', '12'));
```
Seems to be broken atm.


***Flash message***

Set message and redirect
```
Session::flash('home', 'Your password has been changed!');
Redirect::to('index.php');
```

Display flash message on the other page. (will only be displayed once)
```
// index.php
if(Session::exists('home')){
  echo Session::flash('home');
}
```

### Class: Input

Gets values form submitted forms. It will retrieve both GET and POST

- ``Input::exists()`` checks if anything is submitted
- ``Input::get('token')`` get the value from submitted data.


### Class: Validate


Good practice is to check if

- anything is submitted ``if(Input::exists())``
- if token exists in session to avoid hijacking ``if(Token::check(Input::get('token')))``

```
// PHP
$validate = new Validate();
// param 1: the posted valus form the form
// param 2: an array of rules for each field. The rule name and the name on the input field must match.
$validation = $validate->check($_POST, array(
  'username' => array(
    'required' => true,
    'min' => 2,
    'max' => 20,
    'unique' => 'users'
  ),
  'password' => array(
    'required' => true,
    'min' => 6
  ),
  'password_again' => array(
    'required' => true,
    'matches' => 'password' // this will compare the values of the input fields 'password_again' and 'password'
  )
));

if($validation->passed()){
  // Handle success
} else {
  // Handle errors
  foreach ($validation->errors() as $error) {
    echo "{$error}<br>";
  }
}
```


```
// HTML
<form action="" method="post">
  <div class="field">
    <label for="username">Username</label>
    <input  type="text" name="username" id="username",
            value="<?php echo escape(Input::get('username')) ?>" autocomplete="off">
  </div>
  <div class="field">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
  </div>
  <div class="field">
    <label for="password_again">Password_again</label>
    <input type="password_again" name="password_again" id="password_again">
  </div>
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" value="Register">
</form>
```



