<?php
    $query = 
    "SELECT news.title,news.image,news.description,news_slider.active 
    FROM news_slider 
    INNER JOIN news ON news_slider.news_id = news.id 
    WHERE news.status = 'confirmed'";

    $sliders = $db->query($query);
?>

<!-- Slider Section -->
<section>
                <div id="carousel" class="carousel slide">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#carousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carousel" data-bs-slide-to="2"></button>
                    </div>
                    <div class="carousel-inner rounded">
                        <?php foreach ($sliders as $slider): ?>
                            <div class="carousel-item overlay carousel-height <?= ($slider['active']) ? 'active' : '' ?>">
                                <img src="./assets/images/<?= $slider['image'] ?>" class="d-block w-100" alt="post-image" />
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?= $slider['title'] ?></h5>
                                    <p>
                                    <?= substr($slider['description'],0,200)."..." ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>