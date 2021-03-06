<?php

session_start();


if (!empty($_SESSION['role_id']) == 3 and !empty($_SESSION['user_id'])) {
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
                if(strlen($password) < 5 or strlen($passwordRepeat) < 5) {
                    $passError = "Пароль должен содержать не менее 6-ти символов";
                } else {
                    if ($password != $passwordRepeat) {
                        $passError = "Пароли не совпадают.";
                    } else {
                        switch ($role) {
                            case "Студент":
                                $resultEnd = mysqli_query($connection, "INSERT INTO `users` (`surname`, `name`, `email`, `phone`, `password`, `role_id`) VALUES('$surname', '$name', '$email', '$phone', '$password', (SELECT r.id_role FROM t_role r WHERE r.role = 'Студент'))");
                                if (isset($_REQUEST['submit']) and $resultEnd) {
                                    $smsg = "Регистрация прошла успешно. <a href='index.php'>На главную.</a>";
                                } else {
                                    $fsmsg = "Ошибка регистрации!";
                                }
                                break;
                            case "Преподаватель":

                                $result2 = mysqli_query($connection, "INSERT INTO `users` (`surname`, `name`, `patronymic`, `email`, `phone`, `password`, `role_id`, `group_id`) VALUES('$surname', '$name', '$patronymic', '$email', '$phone', '$password', (SELECT r.id_role FROM t_role r WHERE r.role = 'Преподаватель'), NULL)");
                                if (isset($_REQUEST['submit']) and $result2) {
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

                <label for="labelSurname" class="col-form-label">Фамилия*</label>
                <input type="text" class="form-control input-ru" name="surname" placeholder="Введите фамилию"
                       maxlength="98">

                <label class="col-form-label">Имя*</label>
                <input type="text" class="form-control input-ru" name="name" placeholder="Введите имя" maxlength="98">

                <script>
                    $('body').on('input', '.input-ru', function () {
                        this.value = this.value.replace(/[^а-яё\s]/gi, '');
                    });
                </script>

                <?php
                if (!empty($_SESSION['role_id']) == 3) {
                    echo ' <label class="col-form-label">Отчество</label>
                <input type="text" class="form-control" name="patronymic" placeholder="Введите отчество" maxlength="98">';
                } elseif (empty($_SESSION['user_id'])) {

                }
                ?>

                <label class="col-form-label">E-mail*</label>
                <input type="email" class="form-control" name="email" placeholder="Введите E-mail" maxlength="98">

                <label class="col-form-label">Телефон*</label>
                <input type="text" class="form-control" name="phone" placeholder="Введите номер телефона" id="phone">

                <label class="col-form-label">Роль</label>
                <?php
                if (!empty($_SESSION['role_id']) == 3 and !empty($_SESSION['user_id'])) {
                    echo '<input type="text" name="role" class="form-control" readonly value="Преподаватель">';
                } elseif (empty($_SESSION['user_id']) and empty($_SESSION['role_id'])) {
                    echo '<input type="text" name="role" class="form-control" readonly value="Студент">';
                }
                ?>

                <label class="col-form-label">Пароль*</label>
                <input type="password" class="form-control" name="password" id="pass1" onkeyup="checkPass(); return false;" placeholder="Введите пароль" maxlength="98">

                <label class="col-form-label">Повторите пароль*</label>
                <input type="password" class="form-control" name="passwordRepeat" id="pass2" onkeyup="checkPass(); return false;" placeholder="Повторите пароль"
                       maxlength="98">
                <div id="error-nwl"></div>

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

</div>

<script>
    function checkPass()
    {
        var pass1 = document.getElementById('pass1');
        var pass2 = document.getElementById('pass2');
        var message = document.getElementById('error-nwl');
        var goodColor = "#66cc66";
        var badColor = "#ff6666";

        if(pass1.value.length > 5)
        {
            pass1.style.backgroundColor = goodColor;
            message.style.color = goodColor;
            message.innerHTML = ""
        }
        else
        {
            pass1.style.backgroundColor = badColor;
            message.style.color = badColor;
            message.innerHTML = "Вы должны ввести не менее 6-ти символов!"
            return;
        }

        if(pass1.value == pass2.value)
        {
            pass2.style.backgroundColor = goodColor;
            message.style.color = goodColor;
            message.innerHTML = "Пароли совпадают!"
        }
        else
        {
            pass2.style.backgroundColor = badColor;
            message.style.color = badColor;
            message.innerHTML = "Пароли должны совпадать!"
        }
    }
</script>
<script>
    $(document).ready(function () {
        $("#phone").mask("+7 (999) 99-99-999");
    });
</script>

<script src="js/jquery.maskedinput.min.js"></script>

<?php if (!empty($_SESSION['role_id']) == 3) {
    include "include/footer_admin.html";
} else { ?>

</body>

</html>
<?php } ?>

