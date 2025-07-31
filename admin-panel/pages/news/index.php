<?php
include "../../include/layout/header.php";

$news = $db->query("SELECT news.id,news.title,news.image,news.description,news.status, user.name AS reporter_name FROM news INNER JOIN user ON news.reporter_id = user.id ORDER BY id DESC");

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $db->prepare('DELETE FROM news WHERE id = :id');

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
}

if (isset($_GET['status']) && isset($_GET['id'])) {
    if($_GET['status'] == 'confirm') {
        $id = $_GET['id'];
    $query = $db->prepare("UPDATE news SET status = 'confirmed' WHERE id = :id");

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
    };
    if($_GET['status'] == 'reject') {
        $id = $_GET['id'];
    $query = $db->prepare("UPDATE news SET status = 'rejected' WHERE id = :id");

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
    }
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
                <h1 class="fs-3 fw-bold">اخبار</h1>

                <div class="btn-toolbar mb-2 mb-md-0">
                </div>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <?php if ($news->rowCount() > 0) : ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>عنوان</th>
                                    <th>خبرنگار</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($news as $news_1) : ?>
                                    <tr>
                                        <th><?= $news_1['id'] ?></th>
                                        <td><?= $news_1['title'] ?></td>
                                        <td><?= $news_1['reporter_name'] ?></td>
                                        <td>
                                        <?php if ($news_1['status'] == 'confirmed') : ?>
                                                <button class="btn btn-sm btn-outline-dark disabled">تایید شده</button>
                                            <?php elseif($news_1['status'] == 'pending') : ?>
                                                <a href="index.php?status=confirm&id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-info">تایید</a>
                                                <a href="index.php?status=reject&id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-info">رد کردن</a>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-warning disabled">رد شده</button>
                                            <?php endif ?>
                                            <a href="index.php?action=delete&id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirmDelete()">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            خبری ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<?php
include "../../include/layout/footer.php"
?>

<script>
  function confirmDelete() {
    return confirm("از حذف این خبر مطمئن هستید؟");
  }
</script>