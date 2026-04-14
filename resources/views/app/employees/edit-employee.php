<?php
$title = 'ویرایش کارمند: ' . $employee['employee_name'];
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/error.php');
?>

<div class="content">
    <div class="content-title">ویرایش کارمند: <?= $employee['employee_name'] ?></div>

    <div class="box-container">
        <div class="insert">
            <form action="<?= url('edit-employee/store/' . $employee['id']) ?>" method="POST" enctype="multipart/form-data">
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">نام و تخلص <?= _star ?> </div>
                        <input type="text" class="checkInput" name="employee_name" placeholder="نام و تخلص را وارد نمایید" maxlength="40" value="<?= $employee['employee_name'] ?>" />
                    </div>
                    <div class="one">
                        <div class="label-form mb5 fs14">نام پدر</div>
                        <input type="text" name="father_name" placeholder="نام پدر را وارد نمایید" maxlength="40" value="<?= $employee['father_name'] ?>" />
                    </div>
                </div>
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">شماره <?= _star ?> </div>
                        <input type="number" class="checkInput" name="phone" placeholder="شماره را وارد نمایید" value="<?= $employee['phone'] ?>" />
                    </div>
                    <div class="one">
                        <div class="label-form mb5 fs14">رمزعبور<?= _star ?> </div>
                        <input type="password" disabled placeholder="رمزعبور را وارد نمایید" />
                    </div>
                </div>
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">آدرس</div>
                        <textarea name="address" placeholder="آدرس را وارد نمایید"><?= $employee['address'] ?></textarea>
                    </div>
                    <div class="one">
                        <div class="label-form mb5 fs14">توضیحات</div>
                        <textarea name="description" placeholder="توضیحات را وارد نمایید"><?= $employee['description'] ?></textarea>
                    </div>
                </div>
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">انتخاب عکس</div>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                </div>
                <div id="imagePreview">
                    <img src="" class="img" alt="">
                </div>
                <div>
                    <img src="<?= ($employee['image'] ? asset('public/images/employees/' . $employee['image']) : asset('public/assets/img/empty.png')) ?>" class="img" alt="logo">
                </div>
                <div class="fs11">تصویر فعلی</div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                <input type="submit" id="submit" value="ویــرایش" class="btn" />
            </form>
        </div>
        <a href="<?= url('employees') ?>" class="color text-underline d-flex justify-center fs14">برگشت</a>
    </div>
    <!-- end page content -->

</div>

<?php include_once('resources/views/layouts/footer.php') ?>