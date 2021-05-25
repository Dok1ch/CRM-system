<?php
// Проверяем, пусты ли переменные email и id пользователя
session_start();
if (empty($_SESSION['user_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
}

include "include/header.html";


if(!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


}


?>

    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>

    <div class="container">
        <div class="row">
            <div class="text-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <table class="table table-striped">

                        <tr>
                            <th scope="col">Дата</th>
                            <th scope="col">Тема</th>
                            <th scope="col">Рекомендации</th>
                            <th scope="col">Посещение</th>
                        </tr>

                        <?php
                            include "include/connect.php";
                            // выбор данных о посещаемости
                            $result = mysqli_query($connection, "SELECT DISTINCT a.id_lesson, a.attendance, l.date, l.theme, l.recommendation FROM t_attendance a, t_lesson l WHERE a.id_student = '$user_id' AND a.id_lesson = l.id_lesson");
                            //извлекаем из базы все данные о посещаемости
                            while ($row_attendance = mysqli_fetch_assoc($result)) {
                        ?>

                        <tr>
                            <td scope="row"><?php
                                // вывод даты занятия
                                echo $row_attendance['date'];
                            ?></td>
                            <td><?php
                                // вывод темы занятия
                                echo $row_attendance['theme'];
                            ?></td>
                            <td><?php
                            // вывод рекомендации занятия
                                echo $row_attendance['recommendation'];
                            ?></td>
                            <td><?php if ($row_attendance['attendance'] == 0)
                                echo "не был(а)";
                                if ($row_attendance['attendance'] == 1)
                                echo "был(а)";
                            ?></td>

                        </tr>

                        <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>

<?php

include "include/footer.html";
?>