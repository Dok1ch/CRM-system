<?php
// Проверяем, пусты ли переменные email и id пользователя
session_start();
if (empty($_SESSION['user_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
}

include "include/header.html";
?>

    <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
        <div class="section">
            <h3>Расписание занятий</h3>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="text-center">
            <div class="col-12 white">
                <h2>Тут по-любому должно быть расписание</h2>
                <h2><?php echo "Конгратулэйшнс, братан! Ты залогинен!"; ?></h2>
            </div>
        </div>
    </div>
    </div>

<?php

include "include/footer.html";
?>