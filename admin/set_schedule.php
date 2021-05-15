<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11.05.2021
 * Time: 19:11
 */
// Проверяем, пусты ли переменные id и role пользователя
session_start();
if ((empty($_SESSION['user_id']) or empty($_SESSION['role_id']))) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: ../index.php");
}

include "../include/header_admin.html";

if ($_SESSION['role_id'] == 1 or $_SESSION['role_id'] == 2 or $_SESSION['role_id'] == 3) {
    include "../include/connect.php";

    $resultTeacher = mysqli_query($connection, "SELECT * FROM users u, t_role r WHERE u.role_id = r.id_role AND r.role = 'Преподаватель'");
    $listTeacher = '<select class="form-control" name="teacher">';
    while ($teacher_array = mysqli_fetch_assoc($resultTeacher)) {
        $listTeacher .= '<option value="' . $teacher_array['user_id'] . '">' . $teacher_array['name'] . '</option>';
    }
    $listTeacher .= "</select>";

    $resultGroup = mysqli_query($connection, "SELECT id_group, group_name FROM t_group");
    $listGroup = '<select class="form-control" name="group">';
    while ($group_array = mysqli_fetch_assoc($resultGroup)) {
        $listGroup .= '<option value="' . $group_array['id_group'] . '">' . $group_array['group_name'] . '</option>';
    }
    $listGroup .= "</select>";

    $resultCabinet = mysqli_query($connection, "SELECT id_cabinet, cabinet FROM t_cabinet");
    $listCabinet = '<select class="form-control" name="cabinet">';
    while ($cabinet_array = mysqli_fetch_assoc($resultCabinet)) {
        $listCabinet .= '<option value="' . $cabinet_array['id_cabinet'] . '">' . $cabinet_array['cabinet'] . '</option>';
    }
    $listCabinet .= "</select>";

}
?>
<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
    <div class="section">
        <h3>Добавить занятие</h3>
    </div>
</div>

<!--Этот див должен быть в каждом файле после "include/header.html";, смотри header.html-->
</div>

<div class="container">
    <div class="row">
        <div class="text-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="container">

                    <?php

                    include "../include/connect.php";

                    if (isset($_REQUEST['submit'])) {
                        if (isset($_POST['teacher'])) {
                            $teacher = filter_var(trim($_POST['teacher']), FILTER_SANITIZE_STRING);
                        }

                        if (isset($_POST['group'])) {
                            $group = filter_var(trim($_POST['group']), FILTER_SANITIZE_STRING);
                        }

                        if (isset($_POST['cabinet'])) {
                            $cabinet = filter_var(trim($_POST['cabinet']), FILTER_SANITIZE_STRING);
                        }

                        if (isset($_POST['date'])) {
                            $date = $_POST['date'];
                            $date = strtotime($date);
                            $date = date("Y-m-d H:i:s", $date);
                        }

                        if (isset($_POST['theme'])) {
                            $theme = $_POST['theme'];
                            $recommendation = mysqli_real_escape_string($connection, $theme);
                        }

                        if (isset($_POST['recommendation'])) {
                            $recommendation = $_POST['recommendation'];
                            $recommendation = mysqli_real_escape_string($connection, $recommendation);
                        }

                        //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
                        if (empty($teacher) or empty($group) or empty($cabinet) or empty($date) or empty($theme)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
                        {
                            $nullError = "Заполните поля отмеченные звездочкой.";
                        } else {

                            $result = mysqli_query($connection, "INSERT INTO `t_lesson` (`id_personal`, `id_group`, `date`, `id_cabinet`, `theme`, `recommendation`) VALUES('$teacher', '$group', '$date', '$cabinet', '$theme', '$recommendation')");

                            if ($result) {
                                $smsg = "Занятие добавлено в расписние!";
                            } else {
                                $fmsg = "Произошла ошибка добавления.";
                            }
                        }
                    }
                    ?>
                    <form role="form" class="form-horizontal" method="POST">
                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">

                                <?php if (isset($nullError)) { ?>
                                    <div class="alert alert-danger" role="alert"> <?php echo $nullError; ?> </div> <?php } ?>
                                <?php if (isset($fmsg)) { ?>
                                    <div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div> <?php } ?>
                                <?php if (isset($smsg)) { ?>
                                    <div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div> <?php } ?>

                                <label>Преподаватель</label>
                                <?php echo $listTeacher; ?>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>

                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">
                                <label>Группа</label>
                                <?php echo $listGroup; ?>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>

                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">
                                <label>Кабинет</label>
                                <?php echo $listCabinet; ?>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>

                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">
                                <label>Дата и время занятия</label>
                                <div class='input-group date'>
                                    <input type='text' class="form-control" id='datetimepicker1' name="date"/>

                                    <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>

                                </div>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>

                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">
                                <label>Тема</label>
                                <input type='text' class="form-control" name="theme"/>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>

                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">
                                <label>Рекомендация к занятию</label>
                                <input type='text' class="form-control" name="recommendation"/>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>

                        <div class="form-group row text-left">
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            <div class="col-sm-8 col-md-6 col-lg-6">
                                <input type='submit' class="btn btn-lg btn-block btn-success" name="submit"
                                       value="Разместить"/>
                            </div>
                            <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Этот див должен быть в каждом файле после "include/footer_admin.html";, смотри footer_admin.html-->
</div>

<?php

include "../include/footer_admin.html";
?>




