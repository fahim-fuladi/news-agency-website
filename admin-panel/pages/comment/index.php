<?php
include "../../include/layout/header.php";

$comments = $db->query("SELECT * FROM comment ORDER BY id DESC");

if (isset($_GET['action']) && isset($_GET['id'])) {

    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == "delete") {
        $query = $db->prepare('DELETE FROM comment WHERE id = :id');
    } else {
        $query = $db->prepare("UPDATE comment SET status='confirmed' WHERE id = :id");
    }
    
    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
}

?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "../../include/layout/sidebar.php"
        ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">نظرات</h1>
            </div>

            <!-- Comments -->
            <div class="mt-4">
                <?php if ($comments->rowCount() > 0) : ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>نام</th>
                                    <th>متن نظر</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comments as $comment) : ?>
                                    <tr>
                                        <th><?= $comment['id'] ?></th>
                                        <td><?= $comment['writer'] ?></td>
                                        <td><?= $comment['text'] ?></td>
                                        <td>
                                            <?php if ($comment['status'] == 'confirmed') : ?>
                                                <button class="btn btn-sm btn-outline-dark disabled">تایید شده</button>
                                            <?php else : ?>
                                                <a href="index.php?action=approve&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-info">در انتظار تایید</a>
                                            <?php endif ?>


                                            <a href="index.php?action=delete&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return Delete()">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            نظری یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<script>
  function Delete() {
    return confirm("از حذف این نظر مطمئن هستید؟");
  }
</script>

<?php
include "../../include/layout/footer.php"
?>