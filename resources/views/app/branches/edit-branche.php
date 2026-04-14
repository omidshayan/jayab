    <?php
    $title = 'ویرایش شعبه: ' . $item['branch_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <div class="content">
        <div class="content-title">ویرایش درس <?= $item['branch_name'] ?></div>
        <div class="box-container">
            <div class="insert">
                <form action="<?=url('edit-branch-store/' . $item['id'])?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام شعبه <?= _star ?> </div>
                            <input type="text" name="branch_name" class="checkInput" value="<?= $item['branch_name'] ?>" placeholder="نام شعبه را وارد نمایید" autocomplete="off" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره</div>
                            <input type="text" name="phone" value="<?= $item['phone'] ?>" placeholder="شماره دوم شعبه را وارد نمایید" autocomplete="off" autofocus />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره دوم</div>
                            <input type="text" name="phone2" value="<?= $item['phone2'] ?>" placeholder="شماره شعبه را وارد نمایید" autocomplete="off" autofocus />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">آدرس</div>
                            <textarea name="address" id="" placeholder="آدرس شعبه را وارد نمائید"><?=$item['address']?></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویــرایــش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('manage-branches') ?>
        </div>

    </div>

    <?php include_once('resources/views/layouts/footer.php') ?>