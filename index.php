<?php
    include "./include/layout/header.php";

    function getNewsByCategory(PDO $db, string $categoryName, int $limit = 4) {
        $query = 
        "SELECT news.id,news.title,news.image,news.description,news.status,category.name AS category_name, user.name AS reporter_name
         FROM news
         INNER JOIN category ON news.category_id = category.id
         INNER JOIN user ON news.reporter_id = user.id
         WHERE category.name = :categoryName AND news.status = 'confirmed'
         ORDER BY news.id DESC
         LIMIT :limit";
    
        $news = $db->prepare($query);
        $news->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);
        $news->bindValue(':limit', $limit, PDO::PARAM_INT);
        $news->execute();
    
        return $news;
    }

    function getNewsFiltered(PDO $db){
        $categoryId = $_GET['category'];
        $query = 
        "SELECT news.id,news.title,news.image,news.description,news.status,category.name AS category_name, user.name AS reporter_name
         FROM news
         INNER JOIN category ON news.category_id = category.id
         INNER JOIN user ON news.reporter_id = user.id
         WHERE news.category_id = :id AND news.status = 'confirmed'
         ORDER BY news.id DESC";
         
        $news = $db->prepare($query);
        $news->bindValue(':id', $categoryId, PDO::PARAM_INT);
        $news->execute();
        //print_r($news->fetchAll());
        return $news;
    }
    
?>

        <main>
        <?php
            include "./include/layout/slider.php"
        ?>

            <!-- Content Section -->
            <section class="mt-4">
                <div class="row">
                    <!-- Posts Content -->
                    <div class="col-lg-12">
                        <div class="row">
                           
                            <?php if(isset($_GET['category'])): 
                                $newsFiltered = getNewsFiltered($db);
                                $newsData = $newsFiltered;
                                $newsData = $newsData->fetchAll();
                                ?>
                                 <?php if(isset($_GET['category']) && empty($newsData)){
                                   echo '<div class="alert alert-danger">خبر مورد نظر پیدا نشد !</div>';
                                 }
                                 ?>
                                <?php foreach ($newsFiltered as $pNews): ?>
                                    <div class="col-sm-3 mb-3">
                                    <div class="card">
                                        <img src="./assets/images/<?= $pNews['image'] ?>" class="card-img-top" alt="post-image" />
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title fw-bold">
                                                <?= $pNews['title'] ?>
                                                </h5>
                                                <div>
                                                    <span class="badge text-bg-secondary"><?= $pNews['category_name'] ?></span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary pt-3">
                                            <?= substr($pNews['description'],0,512) . "..." ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="single.php" class="btn btn-sm btn-dark">مشاهده</a>

                                                <p class="fs-7 mb-0">
                                                <?= $pNews['reporter_name'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                <?php endforeach ?>
                            <?php endif ?>
                            <?php if(!isset($_GET['category'])): ?> 
                            <div class="d-flex justify-content-between align-items-center pt-2">
                                <h3 class="mb-0">
                                    <span class="bg-dark rounded-circle d-inline-block me-2"
                                        style="width: 12px; height: 12px;"></span>
                                    سیاسی
                                </h3>
                                <a href="#" class="btn btn-sm btn-outline-secondary">بیشتر</a>
                            </div>
                            <div class="row g-2">
                                <?php 
                                $politicalNews = getNewsByCategory($db, 'سیاسی');
                                foreach ($politicalNews as $pNews):
                                ?>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <img src="./assets/images/<?= $pNews['image'] ?>" class="card-img-top" alt="post-image" />
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title fw-bold">
                                                <?= $pNews['title'] ?>
                                                </h5>
                                                <div>
                                                    <span class="badge text-bg-secondary"><?= $pNews['category_name'] ?></span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary pt-3">
                                            <?= substr($pNews['description'],0,512) . "..." ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="single.php" class="btn btn-sm btn-dark">مشاهده</a>

                                                <p class="fs-7 mb-0">
                                                <?= $pNews['reporter_name'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                <?php endforeach ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-5">
                                <h3 class="mb-0">
                                    <span class="bg-dark rounded-circle d-inline-block me-2"
                                        style="width: 12px; height: 12px;"></span>
                                    اقتصادی
                                </h3>
                                <a href="#" class="btn btn-sm btn-outline-secondary">بیشتر</a>
                            </div>
                            <div class="row g-2">
                                <?php 
                                $politicalNews = getNewsByCategory($db, 'اقتصادی');
                                foreach ($politicalNews as $pNews):
                                    
                                ?>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <img src="./assets/images/<?= $pNews['image'] ?>" class="card-img-top" alt="post-image" />
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title fw-bold">
                                                <?= $pNews['title'] ?>
                                                </h5>
                                                <div>
                                                    <span class="badge text-bg-secondary"><?= $pNews['category_name'] ?></span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary pt-3">
                                            <?= substr($pNews['description'],0,512) . "..." ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="single.php" class="btn btn-sm btn-dark">مشاهده</a>

                                                <p class="fs-7 mb-0">
                                                <?= $pNews['reporter_name'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                <?php endforeach ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-5">
                                <h3 class="mb-0">
                                    <span class="bg-dark rounded-circle d-inline-block me-2"
                                        style="width: 12px; height: 12px;"></span>
                                    علمی و فناوری
                                </h3>
                                <a href="#" class="btn btn-sm btn-outline-secondary">بیشتر</a>
                            </div>
                            <div class="row g-2">
                                <?php 
                                $politicalNews = getNewsByCategory($db, 'علمی و فناوری');
                                foreach ($politicalNews as $pNews):
                                    
                                ?>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <img src="./assets/images/<?= $pNews['image'] ?>" class="card-img-top" alt="post-image" />
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title fw-bold">
                                                <?= $pNews['title'] ?>
                                                </h5>
                                                <div>
                                                    <span class="badge text-bg-secondary"><?= $pNews['category_name'] ?></span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary pt-3">
                                            <?= substr($pNews['description'],0,512) . "..." ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="single.php" class="btn btn-sm btn-dark">مشاهده</a>

                                                <p class="fs-7 mb-0">
                                                <?= $pNews['reporter_name'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                <?php endforeach ?>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
            </section>
        </main>

<?php
    include "./include/layout/footer.php"
?>