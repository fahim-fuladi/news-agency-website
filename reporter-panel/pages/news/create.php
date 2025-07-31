<?php
include "../../include/layout/header.php";

$user_id = $_SESSION['id'];

$categories = $db->query("SELECT * FROM category");

$invalidInputTitle = '';
$invalidInputImage = '';
$invalidInputDescription = '';

if (isset($_POST['addNews'])) {

    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان خبر الزامیست';
    }

    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = 'فیلد تصویر خبر الزامیست';
    }

    if (empty(trim($_POST['description']))) {
        $invalidInputDescription = 'فیلد متن خبر الزامیست';
    }

    if (!empty(trim($_POST['title'])) && !empty(trim($_FILES['image']['name'])) && !empty(trim($_POST['description']))) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $categoryId = $_POST['categoryId'];

        $nameImage = time() . "_" . $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        if (move_uploaded_file($tmpName, "../../../assets/images/$nameImage")) {
            $postInsert = $db->prepare("INSERT INTO news (title, reporter_id ,category_id, description, image) VALUES (:title, :reporter_id, :category_id, :description, :image)");
            $postInsert->execute(['title' => $title, 'reporter_id' => $user_id, 'category_id' => $categoryId, 'description' => $description, 'image' => $nameImage]);

            header("Location:index.php");
            exit();
        } else {
            echo "Upload Error";
        }
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
                <h1 class="fs-3 fw-bold">ایجاد خبر</h1>
            </div>

            <!-- Create Post -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان خبر</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی خبر</label>
                        <select name="categoryId" class="form-select">
                            <?php if ($categories->rowCount() > 0) : ?>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر خبر</label>
                        <input class="form-control" name="image" type="file" />
                        <div class="form-text text-danger"><?= $invalidInputImage ?></div>
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن خبر</label>
                        <textarea class="form-control" name="description" rows="6"></textarea>
                        <div class="form-text text-danger"><?= $invalidInputDescription ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="addNews" class="btn btn-dark">
                            ایجاد
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