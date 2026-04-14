    <?php
    $title = 'ویرایش نوعیت: ' . $item['type_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <div class="content">
        <div class="content-title">ویرایش نوعیت: <?= $item['type_name'] ?></div>
        <div class="box-container">
            <div class="insert">
                <form id="myForm" action="<?= url('edit-cargo-type-store/' . $item['id']) ?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام نوعیت <?= _star ?> </div>
                            <input type="text" name="type_name" class="checkInput" value="<?= $item['type_name'] ?>" placeholder="نوعیت را وارد نمایید" autocomplete="off" />
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('locations') ?>
        </div>
    </div>

    <?php include_once('resources/views/layouts/footer.php') ?>