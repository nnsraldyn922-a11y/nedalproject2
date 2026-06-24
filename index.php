<?php
$name = $email = $number =  $year = $batch = "";
$message = "";
$message_type = "";
require 'db.php';

if(isset($_GET['delete_id'])){
    $id =$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM student WHERE id = ?");
    $stmt->execute([$id]);
header("Location: register.php");
exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['student_name'];
    $email = $_POST['email'];
    $number =$_POST['student_number'];
    $year = $_POST['year_of_study'];
    $batch = $_POST['batch_name'];

        try {
            $sql = "INSERT INTO student (id ,student_name, email, student_number, year_of_study, batch_name)
            VALUES ( NULL, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
            $name, $email, $number, $year, $batch
            ]);
            $message = "تم تسجيل الطالب بنجاح";
            $message_type = "success";
            $name = $email = $number = $year = $batch = "";
        }
        catch(PDOException $e){
            if($e->getCode() == 23000){
                $message = "الايميل او رقم الطالب مستخدم من قبل";
                $message_type = "error";
            }
            else{
                $message = "خطأ:" . $e->getMessage();
                $message_type = "error";
            }
        }
    }


$stmt = $pdo->query("SELECT * FROM student ORDER BY id DESC");
$student =$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>نظام تسجيل الطلاب</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h1>student Registration</h1>
            <?php
            if(!empty($message)):
                ?>
                <div class="alert <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
                <?php endif;
                 ?>
                 <form method="POST" action="">
                    <label>اسم الطالب:</label>
                    <input type="text" name="student_name" value="<?php echo isset($name) ? $name : ''; ?>" required>

                    <label>الايميل</label>
                    <input type="email" name="email" value="<?php echo isset($email) ? $email : '';  ?>" required>

                    <label>رقم الطالب</label>
                    <input type="text" name="student_number" value="<?php echo isset($number)  ? $number : ''; ?>" required>

                    <label>سنة الدراسة</label>
                    <input  type="number" name="year_of_study" value="<?php echo isset($year) ? $year : ''; ?>" required>

                    <label>اسم الدفعة</label>
                    <input type="text" name="batch_name" value="<?php  echo isset($batch)  ? $batch : ''; ?>" required>

                    <button type="submit">تسجيل طالب جديد</button>
                 </form>
                 <h2>الطلاب المسجلين</h2>
                 <table>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>الايميل</th>
                        <th>الرقم</th>
                        <th>السنة</th>
                        <th>الدفعة</th>
                        <th>حذف</th>
                        
                    </tr>
                    <?php foreach($student as $student): ?>
                        <tr>
                            <td><?php echo $student['id']; ?></td>
                            <td><?php echo $student['student_name']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo $student['student_number'];?></td>
                            <td><?php echo $student['year_of_study']; ?></td>
                            <td><?php echo $student['batch_name']; ?></td>
                            <td><a class="delete-btn" href="register.php? delete_id=<?php echo $student['id']; ?>"
                        onclick="return confirm('تريد الحذف حقا ') ">مسح</a></td>
                        </tr>
                        <?php endforeach; ?>
                 </table>
        </div>
    </body>
</html>