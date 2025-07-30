<?php
    include "./include/layout/header.php";

    if(isset($_GET['news'])) {
        $news_id = $_GET['news'];

        $query = 
        'SELECT news.title,news.image,news.description,news.status,category.name AS category_name, user.name AS reporter_name
        FROM news 
        INNER JOIN category ON news.category_id = category.id
        INNER JOIN user ON news.reporter_id = user.id
        WHERE news.id = :id';

        $news = $db->prepare($query);
        $news->execute(['id' => "$news_id"]);
        $news = $news->fetch();
    }
?>

        <main>
            <!-- Content -->
            <section class="mt-4">
                <div class="row">
                    <!-- Posts & Comments Content -->
                    <?php if(empty($news)): ?>

                    
                    <div class="alert alert-danger">
                        خبر مورد نظر پیدا نشد !
                    </div>
                    
                    <?php else: ?>

                    <div class="col-lg-8">
                        <div class="row justify-content-center">
                            <!-- Post Section -->
                            <div class="col">
                                <div class="card">
                                    <img src="./assets/images/<?= $news['image'] ?>" class="card-img-top" alt="post-image" />
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title fw-bold">
                                            <?= $news['title'] ?>
                                            </h5>
                                            <div>
                                                <span class="badge text-bg-secondary"><?= $news['category_name'] ?></span>
                                            </div>
                                        </div>
                                        <p class="card-text text-secondary text-justify pt-3">
                                        <?= $news['description'] ?>
                                        </p>
                                        <div>
                                            <p class="fs-6 mt-5 mb-0">
                                            خبرنگار:
                                            <?= $news['reporter_name'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="mt-4" />

                            <!-- Comment Section -->
                            <div class="col">

                            <?php 
                            $invalidInputWriter = '';
                            $invalidInputComment = '';
                            $message = '';

                            if(isset($_POST['postComment'])){
                                if(empty(trim($_POST['writer']))) {
                                    $invalidInputName = 'نام را وارد کنید';
                                } elseif(empty(trim($_POST['text']))) {
                                    $invalidInputComment = 'نظر خود را وارد کنید';
                                } else {
                                    $writer = $_POST['writer'];
                                    $text = $_POST['text'];

                                    $commentInsert = $db->prepare("INSERT INTO comment (writer, text, news_id) VALUES (:writer, :text, :news_id)");
                                    $commentInsert->execute(['writer' => $writer, 'text' => $text, 'news_id' => $news_id]);

                                    $message = 'نظر شما ثبت شد';
                                }
                            } ?>
                                <!-- Comment Form -->
                                <div class="card">
                                    <div class="card-body">
                                        <p class="fw-bold fs-5">
                                            ارسال کامنت
                                        </p>
                                        <form method="POST">
                                            <div class="text-success"><?= $message ?></div>
                                            <div class="mb-3">
                                                <label class="form-label">نام</label>
                                                <input type="text" name="writer" class="form-control" />
                                                <div class="form-text text-danger"><?= $invalidInputWriter ?></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">متن کامنت</label>
                                                <textarea class="form-control" name="text" rows="3"></textarea>
                                                <div class="form-text text-danger"><?= $invalidInputComment ?></div>
                                            </div>
                                            <button type="submit" name="postComment" class="btn btn-dark">
                                                ارسال
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <hr class="mt-4" />
                                <!-- Comment Content -->
                                <?php 
                                $comments = $db->prepare("SELECT * FROM comment WHERE news_id = :id AND status = 'confirmed' ");
                                $comments->execute(['id' => $news_id]);
                                 ?>
                                <p class="fw-bold fs-6">تعداد کامنت : <?= $comments->rowCount() ?> </p>

                                <?php if($comments->rowCount() > 0): ?>

                                <?php foreach($comments as $comment): ?>
                                <div class="card bg-light-subtle mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="./assets/images/profile.png" width="45" height="45"
                                                alt="user-profle" />

                                            <h5 class="card-title me-2 mb-0">
                                                <?= $comment['writer'] ?>
                                            </h5>
                                        </div>

                                        <p class="card-text pt-3 pr-3">
                                        <?= $comment['text'] ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endforeach ?>

                                <?php else: ?>
                                    <div class="alert alert-danger" role="alert">
                                        نظری برای این خبر ثبت نشده است.
                                    </div>
                                <?php endif ?>

                            </div>
                        </div>
                    </div>

                    <?php endif ?>
                    
                    <?php
                    include "./include/layout/sidebar.php"
                    ?>
                </div>
            </section>
        </main>

        <?php
    include "./include/layout/footer.php"
?>