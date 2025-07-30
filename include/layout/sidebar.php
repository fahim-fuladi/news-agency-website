<?php
$categories = $db->query("SELECT * FROM category");
?>

<!-- Sidebar Section -->
<div class="col-lg-4">
                        <!-- Sesrch Section --> 
                        <div class="card">
                            <div class="card-body">
                                <p class="fw-bold fs-6">جستجو در اخبار</p>
                                <form action="search.php" method="GET">
                                    <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control" placeholder="جستجو ..." />
                                        <button class="btn btn-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Categories Section -->
                        <div class="card mt-4">
                            <div class="fw-bold fs-6 card-header">دسته بندی ها</div>
                            <ul class="list-group list-group-flush p-0">
                            <?php if($categories->rowCount() > 0): ?>
                                <?php foreach($categories as $category): ?>
                                <li class="list-group-item">
                                    <a class="link-body-emphasis text-decoration-none" href="index.php?category=<?= $category['id'] ?>"><?= $category['name'] ?></a>
                                </li>
                                <?php endforeach ?>
                            <?php endif ?>
                            </ul>
                        </div>
                    </div>