<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10.05.2021
 * Time: 12:07
 */
session_start();
if (empty($_SESSION['user_id']) or empty($_SESSION['role_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
}

if ($_SESSION['role_id'] == 3) {
    include "include/header_admin.html";
} elseif ($_SESSION['role_id'] == 2) {
    include "include/header_teacher.html";
} elseif ($_SESSION['role_id'] == 1) {
    include "include/header.html";
} ?>

    <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
        <div class="section">
            <h3>Изменение пароля</h3>
        </div>
    </div>

    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>

    <div class="container">
        <div class="text-center white">
            <?php
            include "include/connect.php";


            if (isset($_POST['oldPassword'])) {
                $oldPassword = filter_var(trim($_POST['oldPassword']), FILTER_SANITIZE_STRING);
                $oldPassword = md5($oldPassword);
            }

            if (isset($_POST['newPassword'])) {
                $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);
                $newPassword = md5($newPassword);
            }

            if (isset($_POST['repeatPassword'])) {
                $repeatPassword = filter_var(trim($_POST['repeatPassword']), FILTER_SANITIZE_STRING);
                $repeatPassword = md5($repeatPassword);
            }

            $user_id = $_SESSION['user_id'];

            if (isset($_REQUEST['submit'])) {
                if ($_SESSION['role_id'] == 3) {
                    if ($newPassword == $repeatPassword) {
                        $resultAdmin = mysqli_query($connection, "UPDATE users SET `password` = '$newPassword'  WHERE users.user_id = '$user_id'");

                        if ($resultAdmin) {
                            $smsg = "Пароль успешно изменен!";
                        }
                    } else {
                        $passError = "Пароли должны совпадать.";
                    }
                } else {
                    if (empty($oldPassword) or empty($newPassword) or empty($repeatPassword)) {
                        $nullError = "Заполните все поля.";
                    } else {
                        if (strlen($newPassword) < 5 or strlen($repeatPassword) < 5) {
                            $passError = "Пароль должен содержать не менее 6-ти символов";
                        } else {
                            $resultPass = mysqli_query($connection, "SELECT `password` FROM `users` WHERE user_id = '$user_id'");
                            $rowPass = mysqli_fetch_array($resultPass);

                            //Пароль юзера в бд
                            $passwordBD = $rowPass['password'];

                            //Если старый пароль совпал, сверяем новый пароль
                            if ($passwordBD == $oldPassword) {
                                if ($newPassword == $repeatPassword) {
                                    $result = mysqli_query($connection, "UPDATE users SET `password` = '$newPassword'  WHERE users.user_id = '$user_id'");

                                    if (isset($_REQUEST['submit'])) {
                                        $smsg = "Пароль успешно изменен!";
                                    }
                                } else {
                                    $passError = "Пароли должны совпадать.";
                                }
                            } else {
                                $checkPass = "Прежний пароль введен неправильно.";
                            }
                        }


                    }
                }

            } else {

            }


            ?>
            <form method="post">
                <div class="form-group row text-left">

                    <div class="form-group row text-center">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <?php if (isset($smsg)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-success"
                                 role="alert"> <?php echo $smsg; ?> </div> <?php } ?>
                        <?php if (isset($passError)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-danger"
                                 role="alert"> <?php echo $passError; ?> </div> <?php } ?>
                        <?php if (isset($nullError)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-danger"
                                 role="alert"> <?php echo $nullError; ?> </div> <?php } ?>
                        <?php if (isset($checkPass)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-danger"
                                 role="alert"> <?php echo $checkPass; ?> </div> <?php } ?>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>

                </div>

                <?php
                if ($_SESSION['role_id'] != 3) { ?>
                    <div class="form-group row text-left">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <div class="col-sm-8 col-md-6 col-lg-6">
                            <label>Старый пароль</label>
                            <input maxlength="98" type="password" class="form-control" name="oldPassword">
                        </div>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>
                <?php } ?>


                <div class="form-group row text-left">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-sm-8 col-md-6 col-lg-6">
                        <label>Новый пароль</label>
                        <input maxlength="98" type="password" class="form-control" name="newPassword" id="pass1"
                               onkeyup="checkPass(); return false;">
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>

                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-sm-8 col-md-6 col-lg-6">
                        <label>Повторите пароль</label>
                        <input maxlength="98" type="password" class="form-control" name="repeatPassword" id="pass2"
                               onkeyup="checkPass(); return false;">
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>

                </div>
                <div id="error-nwl"></div>
                <div class="form-group row white-font">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <?php if ($_SESSION['role_id'] == 3) { ?>
                        
                       <?php } else { ?>
                            <button type="button" class="btn btn-lg btn-block btn-info"
                                    onclick="document.location = 'profile.php'">Назад
                            </button>
                        <?php } ?>

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-success">Изменить пароль
                        </button>
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>

                </div>

                <script>
                    function checkPass() {
                        var pass1 = document.getElementById('pass1');
                        var pass2 = document.getElementById('pass2');
                        var message = document.getElementById('error-nwl');
                        var goodColor = "#66cc66";
                        var badColor = "#ff6666";

                        if (pass1.value.length > 5) {
                            pass1.style.backgroundColor = goodColor;
                            message.style.color = goodColor;
                            message.innerHTML = ""
                        } else {
                            pass1.style.backgroundColor = badColor;
                            message.style.color = badColor;
                            message.innerHTML = "Вы должны ввести не менее 6-ти символов!"
                            return;
                        }

                        if (pass1.value == pass2.value) {
                            pass2.style.backgroundColor = goodColor;
                            message.style.color = goodColor;
                            message.innerHTML = "Пароли совпадают!"
                        } else {
                            pass2.style.backgroundColor = badColor;
                            message.style.color = badColor;
                            message.innerHTML = "Пароли должны совпадать!"
                        }
                    }
                </script>
            </form>
        </div>
    </div>

<?php

include "include/footer.html";

?>