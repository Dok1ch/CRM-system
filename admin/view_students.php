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


if ($_SESSION['role_id'] == 3) {
    include "../include/header_admin.html";
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

                <div class="container">
                    <div class="row">
                        <div class="text-left">

                            <div class="form-group row text-center">
                                <div class="col-sm-2 col-md-3 col-lg-3"></div>
                                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                                    <label></label>
                                    <input class="form-control" type="text"id="search-text" onkeyup="tableSearch()"
                                           placeholder="Поиск студентов">

                                    <script>
                                        function tableSearch() {
                                            var phrase = document.getElementById('search-text');
                                            var table = document.getElementById('info-table');
                                            var regPhrase = new RegExp(phrase.value, 'i');
                                            var flag = false;
                                            for (var i = 1; i < table.rows.length; i++) {
                                                flag = false;
                                                for (var j = table.rows[i].cells.length - 1; j >= 0; j--) {
                                                    flag = regPhrase.test(table.rows[i].cells[j].innerHTML);
                                                    if (flag) break;
                                                }
                                                if (flag) {
                                                    table.rows[i].style.display = "";
                                                } else {
                                                    table.rows[i].style.display = "none";
                                                }

                                            }
                                        }
                                    </script>
                                </div>
                                <div class="col-sm-2 col-md-3 col-lg-3"></div>
                            </div>

                        </div>
                    </div>
                </div>


                <?php
                $table = '<table class="table table-striped table-3"  id="info-table">';
                $table .= "<thead><tr><th>Фамилия</th><th>Имя</th><th>Группа</th><th>Email</th></tr></thead>";

                while ($student_array = mysqli_fetch_assoc($result)) {
                    $table .= '<tr><td data-label="Фамилия:"><a href="../profile.php?user_id=' . $student_array['user_id'] . '">' . $student_array['surname'] . '</a></td>';
                    $table .= '<td data-label="Имя:"><a href="../profile.php?user_id=' . $student_array['user_id'] . '">' . $student_array['name'] . '</a></td>';
                    $table .= '<td data-label="Группа:">' . $student_array['group_name'] . '</td>';
                    $table .= '<td data-label="Email:">' . $student_array['email'] . '</td></tr>';
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
