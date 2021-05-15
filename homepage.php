<?php
// Проверяем, пусты ли переменные email и id пользователя
session_start();
if (empty($_SESSION['user_id']) or empty($_SESSION['role_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
    exit();
}

if($_SESSION['role_id'] == 3) {
    header("Location: admin/view_students.php");
    exit();
}

include "include/header.html";
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
                    <table class="table table-striped">
                        <tr>
                            <th>Понедельник</th>
                            <th>Вторник</th>
                            <th>Среда</th>
                            <th>Четверг</th>
                            <th>Пятница</th>
                            <th>Суббота</th>
                        </tr>
                        <tr>
                            <td>Microsoft</td>
                            <td>20.3</td>
                            <td>30.5</td>
                            <td>23.5</td>
                            <td>40.3</td>
                            <td>40.3</td>
                        </tr>
                        <tr>
                            <td>Google</td>
                            <td>50.2</td>
                            <td>40.63</td>
                            <td>45.23</td>
                            <td>39.3</td>
                            <td>40.3</td>

                        </tr>
                        <tr>
                            <td>Apple</td>
                            <td>25.4</td>
                            <td>30.2</td>
                            <td>33.3</td>
                            <td>36.7</td>
                            <td>40.3</td>
                        </tr>
                        <tr>
                            <td>IBM</td>
                            <td>20.4</td>
                            <td>15.6</td>
                            <td>22.3</td>
                            <td>29.3</td>
                            <td>40.3</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>

<?php

include "include/footer.html";
?>