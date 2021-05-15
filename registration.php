<?php

session_start();


if (!empty($_SESSION['role_id']) == 3 AND !empty($_SESSION['user_id'])) {
    include "include/header_admin.html";
    ?>

<?php } elseif (empty($_SESSION['user_id'])) { ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


    <!-- Подключение jquery v3.5.1-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>

    <title>Регистрация</title>
</head>

<div>

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


<?php } ?>

<?php

include 'include/connect.php';

if (isset($_POST['surname'])) {
    $surname = filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING);
}

if (isset($_POST['name'])) {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
}

if (isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
}

if (isset($_POST['surname'])) {
    $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
}

if (isset($_POST['role'])) {
    $role = $_POST['role'];
}


if (isset($_POST['patronymic'])) {
    $patronymic = $_POST['patronymic'];

}

if (isset($_POST['password'])) {
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    $password = md5($password);
}

if (isset($_POST['passwordRepeat'])) {
    $passwordRepeat = filter_var(trim($_POST['passwordRepeat']), FILTER_SANITIZE_STRING);
    $passwordRepeat = md5($passwordRepeat);
}

if (isset($_REQUEST['submit'])) {
    if (empty($surname) or empty($name) or empty($email) or empty($password)) {
        $nullError = "Заполните все поля.";
    } else {

        //Экранирование переменной $email
        $email = mysqli_real_escape_string($connection, $email);

        $result = mysqli_query($connection, "SELECT u.user_id FROM users u WHERE u.email='$email' LIMIT 1");
        $row = mysqli_fetch_array($result);

        if (!empty($row['user_id']) or $email == NULL) {
            $regError = "Пользователь с таким email уже зарегистрирован.";
        } else {
            if ($password != $passwordRepeat) {
                $passError = "Пароли не совпадают.";
            } else {
                switch ($role) {
                    case "Студент":
                        $resultEnd = mysqli_query($connection, "INSERT INTO `users` (`surname`, `name`, `email`, `phone`, `password`, `role_id`) VALUES('$surname', '$name', '$email', '$phone', '$password', (SELECT r.id_role FROM t_role r WHERE r.role = 'Студент'))");
                        if (isset($_REQUEST['submit']) AND $resultEnd) {
                            $smsg = "Регистрация прошла успешно. <a href='index.php'>На главную.</a>";
                        } else {
                            $fsmsg = "Ошибка регистрации!";
                        }
                        break;
                    case "Преподаватель":

                        $result2 = mysqli_query($connection, "INSERT INTO `users` (`surname`, `name`, `patronymic`, `email`, `phone`, `password`, `role_id`, `group_id`) VALUES('$surname', '$name', '$patronymic', '$email', '$phone', '$password', (SELECT r.id_role FROM t_role r WHERE r.role = 'Преподаватель'), NULL)");
                        if (isset($_REQUEST['submit']) AND $result2) {
                            $smsg = "Регистрация прошла успешно. <a href='index.php'>На главную.</a>";
                        } else {
                            $fsmsg = "Ошибка регистрации!";
                        }

                        break;

                }
            }


        }


    }
}


if (!empty($_SESSION['role_id']) == 3) { ?>
<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
    <div class="section">
        <h3>Регистрация преподавателя</h3>
    </div>
</div>
    </div>
<?php } ?>
<div class="container">
    <form role="form" class="form-horizontal" method="POST">
        <div class="form-group row justify-content-center">
            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-6 col-xl-5">

                <?php if (isset($nullError)) { ?>
                    <div class="alert alert-danger" role="alert"> <?php echo $nullError; ?> </div> <?php } ?>
                <?php if (isset($regError)) { ?>
                    <div class="alert alert-danger" role="alert"> <?php echo $regError; ?> </div> <?php } ?>
                <?php if (isset($passError)) { ?>
                    <div class="alert alert-danger" role="alert"> <?php echo $passError; ?> </div> <?php } ?>
                <?php if (isset($fmsg)) { ?>
                    <div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div> <?php } ?>
                <?php if (isset($smsg)) { ?>
                    <div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div> <?php } ?>

                <label for="labelSurname" class="col-form-label">Фамилия</label>
                <input type="text" class="form-control" name="surname" placeholder="Введите фамилию">

                <label class="col-form-label">Имя</label>
                <input type="text" class="form-control" name="name" placeholder="Введите имя">

                <?php
                if (!empty($_SESSION['role_id']) == 3) {
                    echo ' <label class="col-form-label">Отчество</label>
                <input type="text" class="form-control" name="patronymic" placeholder="Введите отчество">';
                } elseif (empty($_SESSION['user_id'])) {

                }
                ?>

                <label class="col-form-label">E-mail</label>
                <input type="email" class="form-control" name="email" placeholder="Введите E-mail">

                <label class="col-form-label">Телефон</label>
                <input type="text" class="form-control" name="phone" placeholder="Введите номер телефона" id="phone">

                <label class="col-form-label">Роль</label>
                    <?php
                    if (!empty($_SESSION['role_id']) == 3 AND !empty($_SESSION['user_id'])) {
                        echo '<input type="text" name="role" class="form-control" readonly value="Преподаватель">';
                    } elseif (empty($_SESSION['user_id']) AND empty($_SESSION['role_id'])) {
                        echo '<input type="text" name="role" class="form-control" readonly value="Студент">';
                    }
                    ?>

                <label class="col-form-label">Пароль</label>
                <input type="password" class="form-control" name="password" placeholder="Введите пароль">

                <label class="col-form-label">Повторите пароль</label>
                <input type="password" class="form-control" name="passwordRepeat" placeholder="Повторите пароль">


            </div>
        </div>

        <div class="form-group row justify-content-center">
            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-6 col-xl-5">
                <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Регистрация</button>
                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="document.location='index.php'">
                    Назад
                </button>
            </div>
        </div>
    </form>

</div>

<?php if (!empty($_SESSION['role_id']) == 3) { ?>
</div>
<script>
    $(document).ready(function () {
        $("#phone").mask("+7 (999) 99-99-999");
    });
</script>
<script src="js/jquery.maskedinput.min.js"></script>
<?php

include "include/footer.html"; } else { ?>




</body>

</html>
<?php } ?>

