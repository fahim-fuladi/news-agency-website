<?php
include "../../include/layout/header.php";

$news = $db->query("SELECT news.id,news.title,news.image,news.description,news.status, user.name AS reporter_name ,category.name AS category_name
FROM news 
INNER JOIN user ON news.reporter_id = user.id 
INNER JOIN category ON news.category_id = category.id
ORDER BY id DESC");

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
                                        <button 
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#newsModal"
                                            data-title="<?= htmlspecialchars($news_1['title']) ?>"
                                            data-image="/news-agency-website/assets/images/<?= $news_1['image'] ?>"
                                            data-description="<?= htmlspecialchars($news_1['description']) ?>"
                                            data-reporter="<?= htmlspecialchars($news_1['reporter_name']) ?>"
                                            data-category="<?= htmlspecialchars($news_1['category_name']) ?>"
                                            >
                                            جزئیات
                                        </button>
                                        <?php if ($news_1['status'] == 'confirmed') : ?>
                                                <button class="btn btn-sm btn-outline-dark disabled">تایید شده</button>
                                            <?php elseif($news_1['status'] == 'pending') : ?>
                                                <a href="index.php?status=confirm&id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-info">تایید</a>
                                                <a href="index.php?status=reject&id=<?= $news_1['id'] ?>" class="btn btn-sm btn-outline-info">رد کردن</a>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-outline-warning disabled">رد شده</button>
                                            <?php endif ?>
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
                            خبری ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="newsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="card border-0">
          <img id="modalImage" src="" class="card-img-top mb-3" alt="post-image" />
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 id="modalTitle" class="card-title fw-bold"></h5>
              <div>
                <span id="modalCategory" class="badge text-bg-secondary"></span>
              </div>
            </div>
            <p id="modalDescription" class="card-text text-secondary pt-3"></p>
            <div>
              <p id="modalReporter" class="fs-6 mt-5 mb-0"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>

<script>
  const newsModal = document.getElementById('newsModal');
  newsModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const title = button.getAttribute('data-title');
    const image = button.getAttribute('data-image');
    const description = button.getAttribute('data-description');
    const reporter = button.getAttribute('data-reporter');
    const category = button.getAttribute('data-category');

    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalImage').src = image;
    document.getElementById('modalDescription').textContent = description;
    document.getElementById('modalReporter').textContent = "خبرنگار: " + reporter;
    document.getElementById('modalCategory').textContent = category;
  });
</script>


<script>
  function Delete() {
    return confirm("از حذف این خبر مطمئن هستید؟");
  }
</script>

<?php
include "../../include/layout/footer.php"
?>

