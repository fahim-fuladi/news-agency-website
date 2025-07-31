<?php
include "../../include/layout/header.php";

$users = $db->query(
"SELECT * 
FROM user 
ORDER BY id DESC");

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $db->prepare('DELETE FROM user WHERE id = :id');

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
}

if (isset($_GET['role']) && isset($_GET['id'])) {
    if($_GET['role'] == 'admin') {
        $id = $_GET['id'];
    $query = $db->prepare("UPDATE user SET role = 'admin' WHERE id = :id");

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
    };
    if($_GET['role'] == 'reporter') {
        $id = $_GET['id'];
    $query = $db->prepare("UPDATE user SET role = 'reporter' WHERE id = :id");

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
    }
}
?>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'login' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = $db->prepare("SELECT * FROM user WHERE id = :id");
    $user->execute(['id' => $id]);
    $user = $user->fetch(PDO::FETCH_ASSOC);

    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['id'] = $user['id'];

    if($user['role'] == 'admin'){
        header("Location:../../../admin-panel/index.php");
    } else {
        header("Location:../../../reporter-panel/index.php");
    }
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
                </div>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <?php if ($users->rowCount() > 0) : ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>نام</th>
                                    <th>ایمیل</th>
                                    <th>نقش</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <th><?= $user['id'] ?></th>
                                        <td><?= $user['name'] ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td><?= ($user['role'] == 'admin')? 'مدیر' : 'خبرنگار'  ?></td>
                                        <td>
                                        <a href="index.php?action=login&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">ورود به پنل کاربر</a>
                                        <?php if ($user['role'] == 'admin') : ?>
                                            <a href="index.php?role=reporter&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-info">تغییر وضعیت به خبرنگار</a>
                                            <?php elseif($user['role'] == 'reporter') : ?>
                                                <a href="index.php?role=admin&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-info">تغییر وضعیت به مدیر</a>
                                            <?php else : ?>
                                                <div></div>
                                            <?php endif ?>
                                            <a href="index.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return Delete()">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            کاربری یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<script>
  function Delete() {
    return confirm("از حذف این کاربر مطمئن هستید؟");
  }
</script>

<?php
include "../../include/layout/footer.php"
?>

