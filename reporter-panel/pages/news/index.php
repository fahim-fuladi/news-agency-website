<?php
include "../../include/layout/header.php";

$user_id = $_SESSION['id'];

$query = $db->prepare(
  "SELECT news.id,news.title,news.image,news.description,news.status,user.name AS reporter_name,category.name AS category_name
    FROM news 
    INNER JOIN user ON news.reporter_id = user.id 
    INNER JOIN category ON news.category_id = category.id
    WHERE news.reporter_id = :user_id
    ORDER BY news.id DESC
");

$query->execute(['user_id' => $user_id]);
$news = $query;


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $db->prepare('DELETE FROM news WHERE id = :id');

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
                <h1 class="fs-3 fw-bold">اخبار</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="./create.php" class="btn btn-sm btn-dark">
                        ایجاد خبر جدید
                    </a>
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
                                    <th>دسته بندی</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($news as $news_1) : ?>
                                    <tr>
                                        <th><?= $news_1['id'] ?></th>
                                        <td><?= $news_1['title'] ?></td>
                                        <td><?= $news_1['category_name'] ?></td>
                                        <td><?php if ($news_1['status'] == 'confirmed') : ?>
                                                <button class="btn btn-sm btn-outline-success disabled">تایید شده</button>
                                            <?php elseif($news_1['status'] == 'pending') : ?>
                                              <button class="btn btn-sm btn-outline-dark disabled">در انتظار تایید</button>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-warning disabled">رد شده</button>
                                            <?php endif ?></td>
                                        <td>
                                        
                                            <a href="./edit.php?id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?action=delete&id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return Delete()">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            خبری یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>


<script>
  function Delete() {
    return confirm("از حذف این خبر مطمئن هستید؟");
  }
</script>

<?php
include "../../include/layout/footer.php"
?>

