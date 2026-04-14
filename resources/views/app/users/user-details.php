<?php
$title = 'جزئیات کاربر: ' . $user['user_name'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
include_once('resources/views/scripts/show-img-modal.php');
?>

<div id="alert" class="alert" style="display: none;"> با برنامه نویس مه تماس بگیر :(</div>
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<div class="content">
    <div class="content-title">
        جزئیات کارمند : <?= $user['user_name'] ?>
        <div>
            <span class="fs15">بالانس کاربر: </span>
            <?= ($account_balance['balance']) ? $this->formatNumber($account_balance['balance']) . _afghani : 0 ?>
        </div>
    </div>
    <div class="box-container">
        <div class="accordion-title color-orange">مشخصات عمومی</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام</div>
                    <div class="info-detaile"><?= $user['user_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره</div>
                    <div class="info-detaile"><?= $user['phone'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام شرکت</div>
                    <div class="info-detaile"><?= ($user['company'] ? $user['company'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">باقیداری از گذشته</div>
                    <div class="info-detaile"><?=  $this->formatNumber(($user['remnants_past'] ? $user['remnants_past'] : '0')) . _afghani?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">آدرس</div>
                    <div class="info-detaile"><?= ($user['address'] ? $user['address'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات</div>
                    <div class="info-detaile"><?= ($user['description'] ? $user['description'] : '- - - - ') ?></div>
                </div>

                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">معرف</div>
                    <div class="info-detaile"><?= ($user['reagent'] ? $user['reagent'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره معرف</div>
                    <div class="info-detaile"><?= ($user['reagent_phone'] ? $user['reagent_phone'] : '- - - - ') ?></div>
                </div>

                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">تاریخ ثبت</div>
                    <div class="info-detaile"><?= jdate('Y/m/d', strtotime($user['created_at'])) ?></div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس: </div>
                    <div class=" m10 flex-justify-align">
                        <?= $user['image']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/users/' . $user['image']) . '" alt="user image" onclick="openModal(\'' . asset('public/images/users/' . $user['image']) . '\')">'
                            : ' - - - - ' ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="#" data-url="<?= url('change-status-user') ?>" data-id="<?= $user['id'] ?>" class="changeStatus color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($user['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <a href="<?= url('users') ?>">
            <div class="btn center p5">برگشت</div>
        </a>
    </div>
</div>

<?php include_once('resources/views/layouts/footer.php') ?>