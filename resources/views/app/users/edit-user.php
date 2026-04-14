    <?php
    $title = 'ویرایش مشتری:' . $user['user_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/error.php');
    ?>

    <div class="content">
        <div class="content-title">ویرایش مشتری: <?= $user['user_name'] ?></div>

        <div class="box-container">
            <div class="insert">
                <form action="<?= url('edit-user-store/' . $user['id']) ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام و تخلص <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= $user['user_name'] ?>" name="user_name" placeholder="نام و تخلص را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">نام شرکت</div>
                            <input type="text" name="company" value="<?= $user['company'] ?>" placeholder="نام شرکت را وارد نمایید" maxlength="40" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره <?= _star ?> </div>
                            <input type="number" class="checkInput" value="<?= $user['phone'] ?>" name="phone" placeholder="شماره را وارد نمایید" />
                        </div>

                        <div class="one">
                            <div class="label-form mb5 fs14">آدرس</div>
                            <textarea name="address" placeholder="آدرس را وارد نمایید"><?= $user['address'] ?></textarea>
                        </div>
                    </div>

                    <div class="accordion-title color-orange mb10">جزئیات مالی</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">باقیداری گذشته </div>
                                    <input type="text" class="validate-number" id="remnants_past" name="remnants_past" value="<?= rtrim(rtrim($user['remnants_past'], '0'), '.') ?>" placeholder="باقیداری قبلی مشتری را وارد نمایید" maxlength="40" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">طلب گذشته </div>
                                    <input type="text" class="validate-number" id="credit_past" value="<?= rtrim(rtrim($user['credit_past'], '0'), '.') ?>" name="credit_past" placeholder="طلب قبلی مشتری را وارد نمایید" maxlength="40" />
                                </div>
                            </div>

                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">توضیحات مربوط به باقیداری</div>
                                    <textarea name="note" placeholder="توضیحات مربوط به باقیداری را وارد نمایید"><?= $user['description'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-title color-orange">جزئیات بیشتر</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">نام معرف </div>
                                    <input type="text" name="reagent" value="<?= $user['reagent'] ?>" placeholder="نام معرف را وارد نمایید" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">شماره معرف</div>
                                    <input type="number" name="reagent_phone" value="<?= $user['reagent_phone'] ?>" placeholder="شماره معرف را وارد نمایید" />
                                </div>
                            </div>
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">ایمیل</div>
                                    <input type="text" name="email" value="<?= $user['email'] ?>" placeholder="ایمیل را وارد نمایید" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">نوت</div>
                                    <textarea name="description" placeholder="نوت را وارد نمایید"><?= $user['description'] ?></textarea>
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
                                <img src="<?= ($user['image'] ? asset('public/images/users/' . $user['image']) : asset('public/assets/img/empty.png')) ?>" class="img" alt="logo">
                            </div>
                            <div class="fs11">تصویر فعلی</div>

                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ویــرایــش" class="btn" />
                </form>
            </div>
            <?= $this->back_link('users') ?>
        </div>
    </div>

    <!-- check for account -->
    <div id="tooltip" style="position:absolute; display:none; background:#111; color:#fff; padding:6px 10px; border-radius:4px; font-size:13px; pointer-events:none; z-index:1000;">
        نمی‌توانید همزمان باقیداری و طلب وارد نمایید
    </div>
    <script>
        const remnants = document.getElementById('remnants_past');
        const credit = document.getElementById('credit_past');
        const tip = document.getElementById('tooltip');

        function updateDisabled() {
            credit.disabled = remnants.value.trim() !== '';
            remnants.disabled = credit.value.trim() !== '';
        }

        [remnants, credit].forEach(input => {
            input.addEventListener('input', updateDisabled);

            input.addEventListener('mousemove', e => {
                if (input.disabled) {
                    tip.style.display = 'block';
                    tip.style.left = e.pageX + 10 + 'px';
                    tip.style.top = e.pageY + 10 + 'px';
                } else {
                    tip.style.display = 'none';
                }
            });

            input.addEventListener('mouseout', () => {
                tip.style.display = 'none';
            });
        });

        // اجرای اولیه هنگام لود صفحه
        updateDisabled();
    </script>

    <?php include_once('resources/views/layouts/footer.php') ?>