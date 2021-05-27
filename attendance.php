<?php
// Проверяем, пусты ли переменные email и id пользователя
session_start();
if (empty($_SESSION['user_id'])) {
    // Если пусты, то перемещаемся на форму авторизации
    header("Location: index.php");
}
include "include/connect.php";

if(!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    //извлекаем из базы все данные о пользователе с введенным логином
    $result = mysqli_query($connection, "SELECT * FROM users u WHERE u.user_id = ' $user_id 'LIMIT 1");

}


$row = mysqli_fetch_array($result);
if (empty($row['password'])) {
    //если пользователя с введенным логином не существует
    $loginError = "Неправильный логин или пароль.";
} else {
    $role = $_SESSION['role_id'];
    


    switch ($role) {
        case 1:
            //$role = "Студент";
        // вызов просмотра страницы студентом
            include "include/header.html";
            ?>

    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>

    <div class="container">
        <div class="row">
            <div class="text-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <?php
                            include "include/connect.php";
                            // выбор данных о посещаемости
                            $result = mysqli_query($connection, "SELECT DISTINCT a.id_lesson, a.attendance, a.achievements, l.date, l.theme, l.recommendation FROM t_attendance a, t_lesson l WHERE a.id_student = '$user_id' AND a.id_lesson = l.id_lesson");
                        ?>
                    <table class="table table-striped">                        
                        <tr>
                            <th scope="col">Дата</th>
                            <th scope="col">Тема</th>
                            <th scope="col">Достижение</th>
                        </tr>
                        <?php
                            //извлекаем из базы все данные о посещаемости
                            $row_attendance = array(array());
                            $i = 0;
                            while ($row_attendance[$i] = mysqli_fetch_assoc($result)) {
                                if ($row_attendance[$i]['achievements']){
                        ?>

                        <tr>
                            <td scope="row"><?php
                                // вывод даты занятия
                                echo $row_attendance[$i]['date'];
                            ?></td>
                            <td><?php
                                // вывод темы занятия
                                echo $row_attendance[$i]['theme'];
                            ?></td>
                            <td><?php
                            // вывод рекомендации занятия
                                echo $row_attendance[$i]['achievements'];
                            ?></td>

                        </tr>

                        <?php } 
                        $i++;
                    }?>
                    
                </div>
            </div>
        </div>
    </div>
    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>

<?php

include "include/footer.html";

/// вызов страницы редактирования посещаемости преподавателем

            break;
        case "2":
            //$role = "Преподаватель";
            //echo $role;

include "include/header_teacher.html";
?>
    <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
        <div class="section">
            <h3>Посещаемость</h3>
        </div>
    </div>

    <!--Этот див должен быть в каждом файле, смотри header.html-->
    </div>

    <div class="container">
        <div class="text-center white">
            <?php

            include "include/connect.php";


            


            ?>
            <form method="post">
                <div class="form-group row text-left">

                    <div class="form-group row text-center">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <?php if (isset($nullError)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-danger" role="alert"> <?php echo $nullError; ?> </div> <?php } ?>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>

                    <div class="form-group row text-center">
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                        <?php if (isset($smsg)) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 alert alert-success" role="alert"> <?php echo $smsg; ?> </div> <?php } ?>
                        <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    </div>

                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                    
                 
                    <div class="col-sm-2 col-md-3 col-lg-3"></div>
                </div>

                
                <div class="container">
                        <div class="row">
                            <div class="text-center">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <table class="table table-striped">
                                        <tr>
                                            <th scope="col">Присутствие на занятии</th>
                                            <th scope="col">Достижения</th>
                            


                        </tr>

                        <?php
                            include "include/connect.php";
                            // список учеников                    
                            $lesson_id = $_GET['id_lesson'];
                                                   



                            if (!$result_attendance = mysqli_query($connection, "SELECT DISTINCT a.id_lesson  FROM t_attendance a WHERE a.id_lesson = $lesson_id")){
                            
                            $result = mysqli_query($connection, "SELECT DISTINCT u.user_id,  u.surname, u.name  FROM users u, t_group g, t_lesson l WHERE u.group_id = l.id_group AND l.id_lesson = $lesson_id AND u.role_id = (SELECT id_role FROM t_role WHERE role = 'Студент')" );
                            //извлекаем из базы все данные о посещаемости                            
                            $array_students_id = array();  
                            while ($row_users = mysqli_fetch_assoc($result)) {                                
                                array_push($array_students_id, $row_users['user_id']);

                        ?>

                        <tr>
                            <td scope="row">
                                <input type="checkbox" name="<?php echo "student_" . $row_users['user_id'] ?>" checked><?php
                                // вывод учеников с чекбоксом
                                echo $row_users['name'] . " " . $row_users['surname'];
                            ?></td>
                            <td><input class = form-control type="text" name="<?php echo "achievement_" . $row_users['user_id'] ?>" size="40"><?php
                                // запись достижений                            
                            ?></td>         
                        </tr>


                        <?php } ?>

                    </table>
                    <input class = "btn btn-lg btn-success" type="submit" name="formSubmit" value="Сохранить" />
                    <?php
                    // запрос на отправку данных в базу   
                    $result_send_query;
                    for ($i = 0; $i < count($array_students_id); $i++){                        
                        $attendance = $_POST["student_" . $array_students_id[$i]];
                        if ($attendance == "")
                            $attendance_int = 0;
                            else $attendance_int = 1;                       
                        $achievement =$_POST["achievement_" . $array_students_id[$i]];                                    
                        $result_send_query = mysqli_query($connection, "INSERT INTO `t_attendance` (`id_student`, `id_lesson`, `attendance`, `achievements`) VALUES ('$array_students_id[$i]', '$lesson_id', '$attendance_int', '$achievement')");
                    } 
                    }
                        else {
                            $result = mysqli_query($connection, "SELECT DISTINCT u.user_id,  u.surname, u.name  FROM users u, t_group g, t_lesson l WHERE u.group_id = l.id_group AND l.id_lesson = $lesson_id AND u.role_id = (SELECT id_role FROM t_role WHERE role = 'Студент')" );
                            //извлекаем из базы все данные о посещаемости                            
                            $array_students_id = array();  
                            while ($row_users = mysqli_fetch_assoc($result)) {                                
                                array_push($array_students_id, $row_users['user_id']);

                        ?>

                        <tr>
                            <td scope="row">
                                <input type="checkbox" name="<?php echo "student_" . $row_users['user_id'] ?>" checked><?php
                                // вывод учеников с чекбоксом
                                echo $row_users['name'] . " " . $row_users['surname'];
                            ?></td>
                            <td><input class = form-control type="text" name="<?php echo "achievement_" . $row_users['user_id'] ?>" size="40"><?php
                                // запись достижений                            
                            ?></td>         
                        </tr>


                        <?php } ?>

                    </table>
                    <input class = "btn btn-lg btn-success" type="submit" name="formSubmit" value="Изменить" />
                    <?php
                    // запрос на отправку данных в базу   
                    $result_send_query;
                    for ($i = 0; $i < count($array_students_id); $i++){                        
                        $attendance = $_POST["student_" . $array_students_id[$i]];
                        if ($attendance == "")
                            $attendance_int = 0;
                            else $attendance_int = 1;                       
                        $achievement =$_POST["achievement_" . $array_students_id[$i]];                                    
                        $result_send_query = mysqli_query($connection, "UPDATE t_attendance SET attendance = '$attendance_int', achievements = '$achievement' WHERE (id_student = '$array_students_id[$i]' and id_lesson ='$lesson_id')");


                    } 
                    }
                               
                    ?>

                </div>
            </div>
        </div>
    </div>
    <!--Этот див должен быть в каждом файле после include "include/header.html";, смотри header.html-->
    </div>
            </form>
        </div>
    </div>
    <script>
      
    </script>



    <script src="js/jquery.maskedinput.min.js"></script>
<?php

include "include/footer.html";



            break;
    }

}

?>