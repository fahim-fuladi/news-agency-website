<?php
include "./include/layout/header.php";


$totalUsers = $db->query("SELECT COUNT(*) FROM user")->fetchColumn();
$totalNews = $db->query("SELECT COUNT(*) FROM news")->fetchColumn();
$totalCategories = $db->query("SELECT COUNT(*) FROM category")->fetchColumn();
$totalComments = $db->query("SELECT COUNT(*) FROM comment")->fetchColumn();


?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "./include/layout/sidebar.php"
        ?>

        

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="fs-3 fw-bold">داشبورد</h1>
            </div>
            <div class="row g-4 mt-4">
        <!-- Total Users -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 p-3">
                <div class="text-primary fs-1">
                👤
                </div>
                <h5 class="mt-2">تعداد کاربران</h5>
                <p class="fs-4 fw-bold"><?= $totalUsers ?></p>
             </div>
        </div>

        <!-- Total News -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 p-3">
            <div class="text-success fs-1">
            📰
            </div>
            <h5 class="mt-2">تعداد اخبار</h5>
            <p class="fs-4 fw-bold"><?= $totalNews ?></p>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 p-3">
                <div class="text-warning fs-1">
                    🗂️
                </div>
            <h5 class="mt-2">دسته‌بندی‌ها</h5>
            <p class="fs-4 fw-bold"><?= $totalCategories ?></p>
            </div>
        </div>

        <!-- Comments -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 p-3">
             <div class="text-danger fs-1">
                 💬
                 </div>
                    <h5 class="mt-2">نظرات ثبت‌شده</h5>
                     <p class="fs-4 fw-bold"><?= $totalComments ?></p>
                  </div>
            </div>
        </div>
        </main>
    </div>
</div>

<?php
include "./include/layout/footer.php"
?>