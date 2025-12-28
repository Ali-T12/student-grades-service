<?php
require_once __DIR__ . '/../app/db.php';
$editRow = null;

$message = '';


if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM grades WHERE id = ?");
    $stmt->execute([$id]);
    $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
}


// حذف سجل
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM grades WHERE id = ?");
        $stmt->execute([$id]);
    }

    // منع تكرار الحذف عند refresh
    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deleted");
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = $_POST['id'] ?? null;
    $student = trim($_POST['student_name'] ?? '');
    $course  = trim($_POST['course_name'] ?? '');
    $grade   = (int)($_POST['grade'] ?? -1);

    if ($student !== '' && $course !== '' && $grade >= 0 && $grade <= 100) {


        if ($id) {
            // نجيب البيانات القديمة
            $stmt = $pdo->prepare("SELECT student_name, course_name, grade FROM grades WHERE id = ?");
            $stmt->execute([(int)$id]);
            $old = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($old) {
                $noChange =
                    $student === $old['student_name'] &&
                    $course  === $old['course_name'] &&
                    (int)$grade === (int)$old['grade'];

                if ($noChange) {
                    // ما في تغيير
                    header("Location: " . $_SERVER['PHP_SELF'] . "?edit=" . (int)$id . "&msg=nochg");
                    exit;
                }
            }

            // UPDATE 
            $stmt = $pdo->prepare("UPDATE grades SET student_name=?, course_name=?, grade=? WHERE id=?");
            $stmt->execute([$student, $course, $grade, (int)$id]);

            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=updated");
            exit;
        } else {
            // INSERT
            $stmt = $pdo->prepare(
                "INSERT INTO grades (student_name, course_name, grade) VALUES (?, ?, ?)"
            );
            $stmt->execute([$student, $course, $grade]);

            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=added");
            exit;
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}




$grades = $pdo->query("SELECT * FROM grades ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);


$avgRow = $pdo->query("SELECT AVG(grade) AS avg_grade FROM grades")->fetch(PDO::FETCH_ASSOC);
$avg = $avgRow && $avgRow['avg_grade'] !== null ? round((float)$avgRow['avg_grade'], 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Grades Service</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../ph/school_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.png" />

    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>

<body>
    <div id="page-wrapper">

        <h1>Student Grades Service</h1>

        <?php
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'nochg') {
                echo "<p id='flash-msg' class='flash warning' style='text-align:center;'>No changes detected. Nothing was updated.</p>";
            }
            if ($_GET['msg'] === 'updated') {
                echo "<p id='flash-msg' class='flash success' style='text-align:center;'>Updated successfully.</p>";
            }
            if ($_GET['msg'] === 'added') {
                echo "<p id='flash-msg' class='flash success' style='text-align:center;'>Added successfully.</p>";
            }
            if ($_GET['msg'] === 'deleted') {
                echo "<p id='flash-msg' class='flash success' style='text-align:center;'>Deleted successfully.</p>";
            }
        }
        ?>



        <form method="POST">
            <div class="form_2">
                <div class="form_1">

                    <input type="hidden" name="id" value="<?= $editRow['id'] ?? '' ?>">

                    <input type="text" name="student_name"
                        value="<?= htmlspecialchars($editRow['student_name'] ?? '') ?>"
                        placeholder="Student Name" required>

                    <input type="text" name="course_name"
                        value="<?= htmlspecialchars($editRow['course_name'] ?? '') ?>"
                        placeholder="Course Name" required>

                    <input type="number" name="grade" class="grades"
                        value="<?= htmlspecialchars($editRow['grade'] ?? '') ?>"
                        placeholder="Grade (0-100)" min="0" max="100" required style="width: 120px;">

                    <button type="submit" class="btn-primary <?= $editRow ? 'is-update' : 'is-add' ?>">
                        <?= $editRow ? 'Update' : 'Add student' ?>
                    </button>

                    <?php if ($editRow): ?>
                        <a class="btn-cancel" href="<?= $_SERVER['PHP_SELF'] ?>">Cancel</a>
                    <?php endif; ?>

                </div>


            </div>

        </form>
        <div class="h-div">
            <h2>All Grades</h2>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Grade</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($grades) === 0): ?>
                        <tr>
                            <td colspan="5">No records yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($grades as $g): ?>
                            <tr>
                                <td><?= (int)$g['id'] ?></td>
                                <td><?= htmlspecialchars($g['student_name']) ?></td>
                                <td><?= htmlspecialchars($g['course_name']) ?></td>
                                <td><?= (int)$g['grade'] ?></td>
                                <td><?= htmlspecialchars($g['created_at']) ?></td>
                                <td>
                                    <a id="del" href="?delete=<?= (int)$g['id'] ?>"
                                        onclick="return confirm('Are you sure?')">
                                        Delete
                                    </a>

                                    <a id="edit" href="?edit=<?= (int)$g['id'] ?>">
                                        Edit
                                    </a>


                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="h-div">
            <h3>Average Grade (All Records): <?= $avg ?></h3>
        </div>
    </div>
    <script>
        const msg = document.getElementById('flash-msg');

        if (msg) {
            
            requestAnimationFrame(() => {
                msg.classList.add('show');
            });

            setTimeout(() => {
                msg.classList.remove('show');
            }, 3000);

            setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.delete('msg');
                url.searchParams.delete('edit'); 
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }, 500); 
        }
    </script>





</body>

</html>