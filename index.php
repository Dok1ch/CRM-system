<?php
session_start();

if (!empty($_SESSION['user_id']) or !empty($_SESSION['role_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: homepage.php");
    exit();
}

include "include/connect.php";
//  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
if (isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
}
if (isset($_POST['password'])) {
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    $password = md5($password);
}

if (empty($email) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
    $loginError = "Неправильный логин или пароль.";
} else {

    $result = mysqli_query($connection, "SELECT `user_id`, `password`,`role_id` FROM users u WHERE u.email = '$email'"); //извлекаем из базы все данные о пользователе с введенным логином
    $row = mysqli_fetch_array($result);

    if (empty($row['user_id'])) {
        //если пользователя с введенным логином не существует
        $loginError = "Неправильный логин или пароль.";
    } else {
        if(!empty($row['user_id'])) {
            //если существует, то сверяем пароли
            if ($row['password'] == $password) {

                //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role_id'] = $row['role_id'];
                //эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь

                header("Location: homepage.php");
                exit();
            } else {
                $loginError = "Неправильный логин или пароль.";
            }
        }

    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style/style.css">

    <!-- Подключение bootstrap v4.5-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

    <!-- Подключение jquery v3.5.1-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <!-- Подключение poper-->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
</head>

<body>

<div class="container">
    <div class="row justify-content-center text-center">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="yellow">
                <h2>Кружок программирования</h2>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="white">
                <h2>искусственного интеллекта</h2>
            </div>
        </div>
    </div>

    <div class="row row justify-content-center text-center">

    </div>
</div>


<div class="container">
    <form role="form" class="form-horizontal" method="POST">
        <div class="form-group row justify-content-center">
            <label for="inputEmail" class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-xl-1 col-form-label">Email</label>
            <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4 col-xl-4">
                <input type="email" class="form-control" name="email" placeholder="Введите e-mail">
            </div>
        </div>

        <div class="form-group row justify-content-center">
            <label for="inputPassword"
                   class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-xl-1 col-form-label">Пароль</label>
            <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4 col-xl-4">
                <input type="password" class="form-control" name="password" placeholder="Введите пароль"
                       id="inputPassword">
                <a href="#" class="password-control" onclick="return show_hide_password(this);"></a>
            </div>
        </div>

        <div class="form-group row justify-content-center">
            <div class="col-xs-12 col-sm-11 col-md-8 col-lg-5 col-xl-5">
                <button name="submit" type="submit"
                        class="btn btn-primary btn-lg btn-block">Войти
                </button>
            </div>
        </div>

        <div class="form-group row justify-content-center">
            <div class="col-xs-12 col-sm-11 col-md-8 col-lg-5 col-xl-5">
                <button type="button"
                        class="btn btn-success btn-lg btn-block" onclick="document.location='registration.php'">
                    Регистрация
                </button>
            </div>
        </div>

        <div class="form-group row justify-content-center">
            <div class="col-xs-12 col-sm-11 col-md-8 col-lg-5 col-xl-5">
                <button type="button" class="btn btn-info btn-lg btn-block">Забыли пароль?</button>
            </div>
        </div>

    </form>


</div>

<script>
    function show_hide_password(target) {
        var input = document.getElementById('inputPassword');
        if (input.getAttribute('type') == 'password') {
            target.classList.add('view');
            input.setAttribute('type', 'text');
        } else {
            target.classList.remove('view');
            input.setAttribute('type', 'password');
        }
        return false;
    }
</script>

</body>

</html>