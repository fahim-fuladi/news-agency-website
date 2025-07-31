<?php
include "./include/layout/header.php";

$userId = $_SESSION['id'];


$totalNews = $db->prepare("SELECT COUNT(*) FROM news WHERE reporter_id = :user_id");
$totalNews->execute(['user_id' => $userId]);
$totalNews = $totalNews->fetchColumn();

$ConfirmedNews = $db->prepare("SELECT COUNT(*) FROM news WHERE reporter_id = :user_id AND status = 'confirmed'");
$ConfirmedNews->execute(['user_id' => $userId]);
$ConfirmedNews = $ConfirmedNews->fetchColumn();

$totalComments = $db->prepare(
    "SELECT COUNT(*) 
    FROM comment 
    INNER JOIN news ON comment.news_id = news.id 
    WHERE news.reporter_id = :user_id AND comment.status = 'confirmed'
");
$totalComments->execute(['user_id' => $userId]);
$totalComments = $totalComments->fetchColumn();


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

        <!-- Total News -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 p-3">
            <div class="text-success fs-1">
            📰
            </div>
            <h5 class="mt-2">همه اخبار من</h5>
            <p class="fs-4 fw-bold"><?= $totalNews ?></p>
            </div>
        </div>

        <!-- Total Confirmed News -->
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0 p-3">
            <div class="text-success fs-1">
            📰
            </div>
            <h5 class="mt-2">اخبار تایید شده من</h5>
            <p class="fs-4 fw-bold"><?= $ConfirmedNews ?></p>
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