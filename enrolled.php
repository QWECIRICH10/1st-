<?php
session_start();
require_once 'dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$database = new Database();
$db = $database->getConnection();

$query = "SELECT courses.course_name, courses.description 
          FROM enrollments 
          JOIN courses ON enrollments.course_id = courses.id 
          WHERE enrollments.student_id = :student_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':student_id', $student_id);
$stmt->execute();
$enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRS | Enrolled Courses</title>
</head>

<body>

    <?php include_once 'header2.php'; ?>
    <div class="main-wrapper" style="background-color: #f4f4f4; position: relative;">
        <div class="course">
            <a href="courses.php"><i class="fa fa-arrow-left"></i></a>
            <h1>Your Enrolled Courses</h1>
            <table>
                <tr>
                    <th>Course Name</th>
                    <th>Description</th>
                </tr>
                <?php if (count($enrolled_courses) > 0): ?>
                    <?php foreach ($enrolled_courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">You are not enrolled in any courses.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <?php
    include_once 'footer.php';
    ?>
</body>

</html>