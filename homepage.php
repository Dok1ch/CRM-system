<?php
// Проверяем, пусты ли переменные email и id пользователя
session_start();
if (empty($_SESSION['user_id']) or empty($_SESSION['role_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
    exit();
}

if ($_SESSION['role_id'] == 3) {
    header("Location: view_students.php");
    exit();
}

include "include/connect.php";

$user_id = $_SESSION['user_id'];

if ($_SESSION['role_id'] == 1) {
    include "include/header.html";
    $result = mysqli_query($connection, "SELECT DISTINCT l.id_lesson, u.name name, u.surname surname, u.patronymic, g.group_name, c.cabinet, l.date, l.theme, l.recommendation FROM t_lesson l, users u, t_group g, t_cabinet c WHERE l.id_group = (SELECT u.group_id FROM users u WHERE u.user_id = '" . $user_id . "' LIMIT 1) AND g.id_group = (SELECT u.group_id FROM users u WHERE u.user_id = '" . $user_id . "' LIMIT 1) AND l.id_personal = u.user_id AND l.id_cabinet = c.id_cabinet ORDER BY l.date");

    $table = '<table class="table table-striped table-3">';
    $table .= "<thead><tr><th>Дата</th><th>Аудитория</th><th>Тема</th><th>Рекомендация</th><th>Преподаватель</th></tr></thead>";

    while ($lessons_array = mysqli_fetch_assoc($result)) {
        $table .= '<tr><td data-label="Дата занятия:">' . $lessons_array['date'] . '</td>';
        $table .= '<td data-label="Аудитория:">' . $lessons_array['cabinet'] . '</td>';
        $table .= '<td data-label="Тема урока:"><a class="link" href="#">' . $lessons_array['theme'] . '</a></td>';
        if (!empty($lessons_array['recommendation'])) {
            $recommendation = $lessons_array['recommendation'];
            $table .= '<td data-label="Рекомендация:">' . $recommendation . '</td>';
        } else {
            $recommendation = " Нет ";
            $table .= '<td data-label="Рекомендация:">' . $recommendation . '</td>';
        }
        $name = $lessons_array['name'];
        $name = mb_substr($name, 0, 1);
        $patronymic = $lessons_array['patronymic'];
        $patronymic = mb_substr($patronymic, 0, 1);
        $table .= '<td data-label="Преподаватель:">' . $lessons_array['surname'] . ' ' . $name . ' ' . $patronymic . '</td></tr>';

    }
    $table .= "</table>";

}elseif ($_SESSION['role_id'] == 2) {
    include "include/header_teacher.html";
    $result = mysqli_query($connection, "SELECT DISTINCT l.id_lesson, c.cabinet ,g.group_name, l.date, l.theme, l.recommendation FROM t_lesson l, users u, t_group g, t_cabinet c WHERE l.id_group = g.id_group AND l.id_cabinet = c.id_cabinet AND l.id_personal ='" . $user_id . "'");

    $table = '<table class="table table-striped table-3">';
    $table .= "<thead><tr><th>Аудитория</th><th>Дата</th><th>Тема</th><th>Рекомендация</th></tr></thead>";

    while ($lessons_array = mysqli_fetch_assoc($result)) {
        $table .= '<tr><td data-label="Аудитория:">' . $lessons_array['cabinet'] . '</td>';
        $table .= '<td data-label="Дата занятия:">' . $lessons_array['date'] . '</td>';

        //TODO преподаватель должен заполнять посещение и достижения студентов
        $table .= '<td data-label="Тема урока:"><a href="#">' . $lessons_array['theme'] . '</td>';
        if(!empty($lessons_array['recommendation'])) {
            $recommendation = $lessons_array['recommendation'];
            $table .= '<td data-label="Рекомендация:">' . $recommendation . '</td></tr>';
        } else {
            $recommendation = " Нет ";
            $table .= '<td data-label="Рекомендация:">' . $recommendation . '</td></tr>';
        }

    }
    $table .= "</table>";
}



?>

    <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
        <div class="section">
            <h3>Расписание занятий</h3>
        </div>
    </div>

    </div>

    <div class="container">
        <div class="row">
            <div class="text-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label></label>

                    <?php
                        echo $table;


                    ?>
                </div>
            </div>
        </div>
    </div>

    </div>

<?php

include "include/footer.html";
?>