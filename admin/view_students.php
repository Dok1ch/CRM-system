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
    header("Location: index.php");
}

include "../include/header_admin.html";

if ($_SESSION['role_id'] == 3) {
    include "../include/connect.php";

    $result = mysqli_query($connection, "SELECT u.user_id, u.surname, u.name, g.group_name, u.email FROM users u, t_group g WHERE u.group_id = g.id_group");

}
?>
<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
    <div class="section">
        <h3>Просмотр студентов</h3>

    </div>
</div>
<!--Этот див должен быть в каждом файле после "include/header.html";, смотри header.html-->
</div>

<div class="container">
    <div class="row">
        <div class="text-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <?php
                $table = '<table class="table table-striped">';
                $table .= "<th>Фамилия</th><th>Имя</th><th>Номер группы</th><th>Номер телефона</th>";

                while ($student_array = mysqli_fetch_assoc($result)) {
                    $table .= '<tr><td><a href="../profile.php?user_id=' . $student_array['user_id'] . '">' . $student_array['surname'] . '</a></td>';
                    $table .= '<td>' . $student_array['name'] . '</td>';
                    $table .= '<td>' . $student_array['group_name'] . '</td>';
                    $table .= '<td>' . $student_array['email'] . '</td></tr>';
                }
                $table .= "</table>";

                echo $table;


                ?>
            </div>
        </div>
    </div>
</div>

<!--Этот див должен быть в каждом файле после "include/header.html";, смотри header.html-->
</div>

<?php

include "../include/footer.html";
?>
