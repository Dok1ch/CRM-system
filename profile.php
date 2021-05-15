<?php
// Проверяем, пусты ли переменные email и id пользователя
session_start();
if (empty($_SESSION['user_id']) or empty($_SESSION['role_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
}
include "include/connect.php";

$user_id = $_SESSION['user_id'];

if ($_SESSION['role_id'] == 3) {
    $user_id = $_GET['user_id'];

}
//извлекаем из базы все данные о пользователе с введенным логином
$result = mysqli_query($connection, "SELECT u.user_id, u.surname, u.name, u.email, u.phone, u.role_id, g.group_name, g.id_group FROM users u, t_group g WHERE  u.user_id = '$user_id' LIMIT 1");

$row = mysqli_fetch_array($result);

$name = $row['name'];
$surname = $row['surname'];
$email = $row['email'];
$phone = $row['phone'];
$role = $row['role_id'];
$group_name = $row['group_name'];
$group_id = $row['id_group'];

switch ($role) {
    case 1:
        $role = "Студент";
        break;
    case 2:
        $role = "Преподаватель";
        break;


}

if ($_SESSION['role_id'] == 3) {
    include "include/header_admin.html";
    ?>
    <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
        <div class="section">
            <h3>Профиль студента</h3>
        </div>
    </div>
<?php } else {
    include "include/header.html";
    ?>
    <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
        <div class="section">
            <h3>Мой профиль</h3>
        </div>
    </div>
<?php } ?>


    <!--Этот див должен быть в каждом файле, смотри header.html-->
    </div>

    <div class="container">
        <div class="text-center white">
            <?php

            include "include/connect.php";


            if (isset($_REQUEST['submit'])) {
                if (isset($_POST['surname'])) {
                    $surname = filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['name'])) {
                    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['email'])) {
                    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['phone'])) {
                    $phone = trim($_POST['phone']);
                }

                if (isset($_POST['role'])) {
                    $role = $_POST['role'];
                }

                if (isset($_POST['group'])) {
                    $group_id = $_POST['group'];
                }

                //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
                if (empty($surname) or empty($name) or empty($email)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
                {
                    $nullError = "Заполните все поля.";
                } else {

                    $user_id = mysqli_real_escape_string($connection, $user_id);

                    $result = mysqli_query($connection, "UPDATE users SET `name` = '$name', `surname` = '$surname', `phone` = '$phone', `email` = '$email', `group_id` = '$group_id' WHERE users.user_id = '$user_id'");

                    if ($result) {
                        $smsg = "Профиль успешно сохранен!";
                    }
                }
            }


            ?>
            <form method="post">
                <div class="form-group row text-left">

                    <div class="form-group row text-center">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <?php if (isset($nullError)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-danger"
                                 role="alert"> <?php echo $nullError; ?> </div> <?php } ?>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>

                    <div class="form-group row text-center">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <?php if (isset($smsg)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-success"
                                 role="alert"> <?php echo $smsg; ?> </div> <?php } ?>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>

                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <label>Фамилия</label>
                        <input type="text" class="form-control" placeholder="Фамилия" readonly id="surname"
                               name="surname"
                               value="<?php global $surname;
                               print $surname; ?>">
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <label>Имя</label>
                        <input type="text" class="form-control" placeholder="Имя" readonly id="name" name="name"
                               value="<?php global $name;
                               print $name; ?>">
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-sm-8 col-md-6 col-lg-6">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email" readonly id="email" name="email"
                               value="<?php global $email;
                               print $email; ?>">
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-sm-8 col-md-6 col-lg-6">
                        <label>Номер телефона</label>
                        <input type="text" class="form-control" readonly id="phone" name="phone"
                               value="<?php global $phone;
                               print $phone; ?>">
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-sm-8 col-md-6 col-lg-6">
                        <label>Роль</label>
                        <input type="text" class="form-control" readonly name="role" value="<?php global $role;
                        print $role; ?>">
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                </div>

                <?php
                if ($_SESSION['role_id'] != 2) { ?>
                    <div class="form-group row text-left">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <div class="col-sm-8 col-md-6 col-lg-6">
                            <label>Группа</label>
                            <?php
                            if ($_SESSION['role_id'] == 3) {

                                $get_group = mysqli_query($connection, "SELECT * FROM t_group");

                                echo '<select class="form-control" name="group">';

                                while ($row_group = mysqli_fetch_assoc($get_group)) {
                                    if ($group_id == $row_group['id_group']) {
                                        echo '<option value="' . $row_group['id_group'] . '" selected>' . $row_group['group_name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row_group['id_group'] . '">' . $row_group['group_name'] . '</option>';
                                    }

                                }

                                echo '</select>';

                            } else {
                                global $group_name;
                                echo '<input type="text" class="form-control" readonly name="group" value="' . $group_name . '">';
                            } ?>

                        </div>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>
                <?php }

                ?>


                <div class="form-group row">
                    <div class="form-check white-font">
                        <input class="form-check-input" type="checkbox" name="agree" onclick="agreeForm(this.form);">
                        <label class="form-check-label" for="gridCheck"> Редактировать профиль
                        </label>
                    </div>
                </div>

                <div class="form-group row white-font">
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <button type="button" class="btn btn-lg btn-block btn-info"
                                onclick="document.location='changePassword.php'">Изменить пароль
                        </button>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-success">Сохранить профиль
                        </button>
                    </div>
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>

                </div>

            </form>
        </div>
    </div>
    <script>
        function agreeForm(f) {
            // Если поставлен флажок, снимаем блокирование редактирование
            var name = document.getElementById('name');
            var surname = document.getElementById('surname');
            var phone = document.getElementById('phone');
            var email = document.getElementById('email');

            if (f.agree.checked) {
                email.removeAttribute('readonly');
                surname.removeAttribute('readonly');
                phone.removeAttribute('readonly');
                name.removeAttribute('readonly');
            }
            // В противном случае вновь блокируем редактирование
            else {
                email.setAttribute('readonly', 'readonly');
                surname.setAttribute('readonly', 'readonly');
                phone.setAttribute('readonly', 'readonly');
                name.setAttribute('readonly', 'readonly');
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#phone").mask("+7 (999) 99-99-999");
        });
    </script>

    <script src="js/jquery.maskedinput.min.js"></script>
<?php

include "include/footer.html";


?>