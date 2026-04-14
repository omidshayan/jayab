<?php
$title = 'جزئیات کاربر: ' . $employee['employee_name'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
include_once('resources/views/scripts/show-img-modal.php');
$date = explode(' ', $employee['created_at']);
?>

<div id="alert" class="alert" style="display: none;">حالم بده، با برنامه نویس مه تماس بگیر :(</div>

<!-- loading and overlay -->
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>
<!-- Start content -->
<div class="content">
    <div class="content-title"> جزئیات کاربر : <?= $employee['employee_name'] ?></div>

    <!-- start page content -->
    <div class="box-container">

        <div class="accordion-title color-orange">مشخصات عمومی</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام: </div>
                    <div class="info-detaile"><?= $employee['employee_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">ایمیل: </div>
                    <div class="info-detaile"><?= ($employee['father_name'] ? $employee['father_name'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره: </div>
                    <div class="info-detaile"><?= $employee['phone'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">آدرس: </div>
                    <div class="info-detaile"><?= ($employee['address'] ? $employee['address'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات: </div>
                    <div class="info-detaile"><?= ($employee['description'] ? $employee['description'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس: </div>
                    <div class=" m10 flex-justify-align">
                        <?= $employee['image']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/employees/' . $employee['image']) . '" alt="employee image" onclick="openModal(\'' . asset('public/images/employees/' . $employee['image']) . '\')">'
                            : ' - - - - ' ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="#" data-url="<?= url('change-status-employee') ?>" data-id="<?= $employee['id'] ?>" class="changeStatus color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($employee['state'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- salaries -->
        <div class="accordion-title color-orange">جزئیات مالی</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="color-red p10 center fs14">
                    چیزی یافت نشد
                </div>
            </div>
        </div>

        <a href="<?= url('employees') ?>">
            <div class="btn center p5">برگشت</div>
        </a>

    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>