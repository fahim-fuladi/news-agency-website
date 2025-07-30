<?php
    include "./include/layout/header.php";

    if(isset($_GET['search'])) {
        $keyword = $_GET['search'];

        $query = 
        'SELECT news.title,news.image,news.description,news.status,category.name AS category_name, user.name AS reporter_name
        FROM news 
        INNER JOIN category ON news.category_id = category.id
        INNER JOIN user ON news.reporter_id = user.id
        WHERE title 
        LIKE :keyword';

        $news = $db->prepare($query);
        $news->execute(['keyword' => "%$keyword%"]);
    }
?>

        <main>
            <!-- Content Section -->
            <section class="mt-4">
                <div class="row">
                    <!-- Posts Content -->
                    <div class="col-lg-8">
                <div class="alert alert-secondary">
                    خبر های مرتبط با کلمه [ <?= $_GET['search'] ?> ]
                </div>

                <?php if ($news->rowCount() == 0) : ?>
                    <div class="alert alert-danger">
                        خبر مورد نظر پیدا نشد !
                    </div>
                <?php else : ?>
                    <div class="row g-3">
                        <?php foreach ($news as $news_1) : ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <img src="./assets/images/<?= $news_1['image'] ?>" class="card-img-top" alt="news-image" />
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title fw-bold">
                                                <?= $news_1['title'] ?>
                                            </h5>
                                            <div>
                                                <span class="badge text-bg-secondary"><?= $news_1['category_name'] ?></span>
                                            </div>
                                        </div>
                                        <p class="card-text text-secondary pt-3">
                                            <?= substr($news_1['description'], 0, 500) . "..." ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="single.html" class="btn btn-sm btn-dark">مشاهده</a>

                                            <p class="fs-7 mb-0">
                                                خبرنگار : <?= $news_1['reporter_name'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>

                    <?php
                    include "./include/layout/sidebar.php"
                    ?>
                </div>
            </section>
        </main>

        <?php
    include "./include/layout/footer.php"
?>