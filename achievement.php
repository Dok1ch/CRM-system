<?php
// Проверка id юзера и его роли
session_start();

if(empty($_SESSION['user_id']) or ($_SESSION['role_id'] != 1)){
    header("Location: homepage.php");
    exit;
}
else {
    include "include/header.html";
    include "include/connect.php";

    $user_id = $_SESSION['user_id'];

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
                            <th scope="col">Достижение</th>
                        </tr>

                        <?php
                            // выбор данных о посещаемости
                            $result = mysqli_query($connection, "SELECT DISTINCT a.id_lesson, a.attendance, a.achievements, l.date, l.theme, l.recommendation FROM t_attendance a, t_lesson l WHERE a.id_student = '$user_id' AND a.id_lesson = l.id_lesson");
                           
                            // выбор данных о достижениях  
                            $row_attendance = array(array());
                            $i = 0;
                            while ($row_attendance[$i] = mysqli_fetch_assoc($result)) {
                                if ($row_attendance[$i]['achievements']){
                        ?>

                        <tr>
                            <td scope="row"><?php
                                // вывод даты достижения
                                echo $row_attendance[$i]['date'];
                            ?></td>
                            <td><?php
                                // вывод темы занятия
                                echo $row_attendance[$i]['theme'];
                            ?></td>
                            <td><?php
                            // вывод достижения
                                echo $row_attendance[$i]['achievements'];
                            ?></td>
                        </tr>

                        <?php } 
                        $i++; }
                        ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>

<?php
include "include/footer.html";
}
?>