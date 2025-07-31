<?php
include "../../include/layout/header.php";

$user_id = $_SESSION['id'];

if (isset($_GET['id'])) {
    $newsId = $_GET['id'];
    $news = $db->prepare('SELECT * FROM news WHERE id = :id');
    $news->execute(['id' => $newsId]);
    $news = $news->fetch();

    $categories = $db->query("SELECT * FROM category");
}

$invalidInputTitle = '';
$invalidInputDescription = '';

if (isset($_POST['editNews'])) {

    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان خبر الزامیست';
    }

    if (empty(trim($_POST['description']))) {
        $invalidInputDescription = 'فیلد متن خبر الزامیست';
    }

    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['description']))) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $categoryId = $_POST['categoryId'];

        if (!empty(trim($_FILES['image']['name']))) {
            $nameImage = time() . "_" . $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];

            if (move_uploaded_file($tmpName, "../../../assets/images/$nameImage")) {
                $postUpdate = $db->prepare("UPDATE news SET title =:title, reporter_id=:user_id, category_id=:categoryId, description=:description, image=:image WHERE id=:id");
                $postUpdate->execute(['title' => $title, 'user_id' => $user_id, 'categoryId' => $categoryId, 'description' => $description, 'id' => $newsId, 'image' => $nameImage]);
            } else {
                echo "Upload Error";
            }
        } else {
            $postUpdate = $db->prepare("UPDATE news SET title =:title, reporter_id=:user_id, category_id=:categoryId, description=:description WHERE id=:id");
            $postUpdate->execute(['title' => $title, 'user_id' => $user_id, 'categoryId' => $categoryId, 'description' => $description, 'id' => $newsId]);
        }

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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش خبر</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form method="POST" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان خبر</label>
                        <input type="text" name="title" class="form-control" value="<?= $news['title'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی خبر</label>
                        <select name="categoryId" class="form-select">
                            <?php if ($categories->rowCount() > 0) : ?>
                                <?php foreach ($categories as $category) : ?>
                                    <option <?= ($category['id'] == $news['category_id']) ? 'selected' : '' ?> value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر خبر</label>
                        <input name="image" class="form-control" type="file" />
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن خبر</label>
                        <textarea name="description" class="form-control" rows="8"><?= $news['description'] ?></textarea>
                        <div class="form-text text-danger"><?= $invalidInputDescription ?></div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-4">
                        <img class="rounded" src="../../../assets/images/<?= $news['image'] ?>" width="300" />
                    </div>

                    <div class="col-12">
                        <button name="editNews" type="submit" class="btn btn-dark">
                            ویرایش
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php
include "../../include/layout/footer.php"
?>