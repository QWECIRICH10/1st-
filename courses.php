<?php
session_start();
require_once 'dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Fetch all available courses
$query = "SELECT * FROM courses";
$stmt = $db->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$courses) {
    $courses = [];
}

// Handle course enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user is already enrolled in the course
    $checkQuery = "SELECT * FROM enrollments WHERE student_id = :user_id AND course_id = :course_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':user_id', $user_id);
    $checkStmt->bindParam(':course_id', $course_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        echo "<script>alert('You are already enrolled in this course.');</script>";
    } else {
        // Enroll the user in the course
        $enrollQuery = "INSERT INTO enrollments (student_id, course_id) VALUES (:user_id, :course_id)";
        $enrollStmt = $db->prepare($enrollQuery);
        $enrollStmt->bindParam(':user_id', $user_id);
        $enrollStmt->bindParam(':course_id', $course_id);

        if ($enrollStmt->execute()) {
            echo "<script>alert('Successfully enrolled in the course!');</script>";
        } else {
            echo "<script>alert('Failed to enroll in the course. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>CRS | Available Courses</title>
</head>

<body>
    <?php include_once 'header2.php'; ?>
    <div class="main-wrapper" style="background-color: #f4f4f4;">
        <div class="course">
            <h1>Available Courses</h1>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['description']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                    <button type="submit">Enroll</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    include_once 'footer.php';
    ?>
</body>

</html>