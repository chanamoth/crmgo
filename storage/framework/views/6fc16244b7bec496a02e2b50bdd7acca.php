<style>
.disabledCookie {
    pointer-events: none;
    opacity: 0.4;
}
</style>

<?php
$logo = \App\Models\Utility::get_file('uploads/logo/');
$logos = \App\Models\Utility::get_file('');
$dark_logo = Utility::getValByName('company_logo_dark');

$light_logo = Utility::getValByName('company_logo_light');
$company_favicon = Utility::getValByName('favicon');
$lang = \App\Models\Utility::getValByName('default_language');
$setting = App\Models\Utility::settings();
$color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
$flag = (!empty($setting['color_flag'])) ? $setting['color_flag'] : 'false';
$EmailTemplates = App\Models\EmailTemplate::all();
// dd($EmailTemplate->template);

//$logo=\App\Models\Utility::get_file('uploads/logo/');
$file_type = config('files_types');

$local_storage_validation = $setting['local_storage_validation'];
$local_storage_validations = explode(',', $local_storage_validation);

$s3_storage_validation = $setting['s3_storage_validation'];
$s3_storage_validations = explode(',', $s3_storage_validation);

$wasabi_storage_validation = $setting['wasabi_storage_validation'];
$wasabi_storage_validations = explode(',', $wasabi_storage_validation);
$meta_image = \App\Models\Utility::get_file('meta');

?>
<?php $__env->startPush('css-page'); ?>
<?php if($color == 'theme-3'): ?>
<style>
.btn-check:checked+.btn-outline-primary,
.btn-check:active+.btn-outline-primary,
.btn-outline-primary:active,
.btn-outline-primary.active,
.btn-outline-primary.dropdown-toggle.show {
    color: #ffffff;
    background-color: #6fd943 !important;
    border-color: #6fd943 !important;
}


.btn-outline-primary:hover {
    color: #ffffff;
    background-color: #6fd943 !important;
    border-color: #6fd943 !important;
}

.btn[class*="btn-outline-"]:hover {

    border-color: #6fd943 !important;
}
</style>
<?php endif; ?>
<?php if($color == 'theme-2'): ?>
<style>
.btn-check:checked+.btn-outline-primary,
.btn-check:active+.btn-outline-primary,
.btn-outline-primary:active,
.btn-outline-primary.active,
.btn-outline-primary.dropdown-toggle.show {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
    border-color: #4ebbd3 !important;

}

.btn-outline-primary:hover {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
    border-color: #4ebbd3 !important;
}

.btn.btn-outline-primary {
    color: #1F3996;
    border-color: #4ebbd3 !important;
}
</style>
<?php endif; ?>
<?php if($color == 'theme-4'): ?>
<style>
.btn-check:checked+.btn-outline-primary,
.btn-check:active+.btn-outline-primary,
.btn-outline-primary:active,
.btn-outline-primary.active,
.btn-outline-primary.dropdown-toggle.show {
    color: #ffffff;
    background-color: #584ed2 !important;
    border-color: #584ed2 !important;

}

.btn-outline-primary:hover {
    color: #ffffff;
    background-color: #584ed2 !important;
    border-color: #584ed2 !important;
}

.btn.btn-outline-primary {
    color: #584ed2;
    border-color: #584ed2 !important;
}
</style>
<?php endif; ?>
<?php if($color == 'theme-1'): ?>
<style>
.btn-check:checked+.btn-outline-primary,
.btn-check:active+.btn-outline-primary,
.btn-outline-primary:active,
.btn-outline-primary.active,
.btn-outline-primary.dropdown-toggle.show {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
    border-color: #51459d !important;
}


body.theme-1 .btn-outline-primary:hover {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
    border-color: #51459d !important;
}
</style>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
<script type="text/javascript">
$(document).on("click", '.send_email', function(e) {
    e.preventDefault();
    var title = $(this).attr('data-title');
    var size = 'md';
    var url = $(this).attr('data-url');

    if (typeof url != 'undefined') {
        $("#exampleModal .modal-title").html(title);
        $("#exampleModal .modal-dialog").addClass('modal-' + size);
        $("#exampleModal").modal('show');

        $.post(url, {
            _token: '<?php echo e(csrf_token()); ?>',
            mail_driver: $("#mail_driver").val(),
            mail_host: $("#mail_host").val(),
            mail_port: $("#mail_port").val(),
            mail_username: $("#mail_username").val(),
            mail_password: $("#mail_password").val(),
            mail_encryption: $("#mail_encryption").val(),
            mail_from_address: $("#mail_from_address").val(),
            mail_from_name: $("#mail_from_name").val(),

        }, function(data) {
            $('#exampleModal .body').html(data);
        });
    }
});
$(document).on('submit', '#test_email', function(e) {
    e.preventDefault();
    $("#email_sending").show();
    var post = $(this).serialize();
    var url = $(this).attr('action');
    $.ajax({
        type: "post",
        url: url,
        data: post,
        cache: false,
        beforeSend: function() {
            $('#test_email .btn-create').attr('disabled', 'disabled');
        },
        success: function(data) {

            if (data.is_success) {
                toastrs('Success', data.message, 'success');
            } else {
                toastrs('Error', data.message, 'error');
            }
            $("#email_sending").hide();
            $('#exampleModal').modal('hide');


        },
        complete: function() {
            $('#test_email .btn-create').removeAttr('disabled');
        },
    });
});
</script>

<script type="text/javascript">
$(document).on("click", ".email-template-checkbox", function() {
    var chbox = $(this);
    $.ajax({
        url: chbox.attr('data-url'),
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: chbox.val()
        },
        type: 'post',
        success: function(response) {
            if (response.is_success) {
                toastr('Success', response.success, 'success');
                if (chbox.val() == 1) {
                    $('#' + chbox.attr('id')).val(0);
                } else {
                    $('#' + chbox.attr('id')).val(1);
                }
            } else {
                toastr('Error', response.error, 'error');
            }
        },
        error: function(response) {
            response = response.responseJSON;
            if (response.is_success) {
                toastr('Error', response.error, 'error');
            } else {
                toastr('Error', response, 'error');
            }
        }
    })
});
</script>

<script type="text/javascript">
var scrollSpy = new bootstrap.ScrollSpy(document.body, {
    target: '#useradd-sidenav',
    offset: 300
})
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('.list-group-item').on('click', function() {
        var href = $(this).attr('data-href');
        $('.tabs-card').addClass('d-none');
        $(href).removeClass('d-none');  
        $('#tabs .list-group-item').removeClass('text');
        $(this).addClass('text');
    });
});
</script>

<script>
    $(document).on("change", "select[name='estimate_template'], input[name='estimate_color']", function() {
        var template = $("select[name='estimate_template']").val();
        var color = $("input[name='estimate_color']:checked").val();
        $('#estimate_frame').attr('src', '<?php echo e(url('/estimate/preview')); ?>/' + template + '/' + color);
    });
    $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function() {
        var template = $("select[name='invoice_template']").val();
        var color = $("input[name='invoice_color']:checked").val();
        $('#invoice_frame').attr('src', '<?php echo e(url('/invoice/preview')); ?>/' + template + '/' + color);
    });
</script>

<script type="text/javascript">
var scrollSpy = new bootstrap.ScrollSpy(document.body, {
    target: '#useradd-sidenav',
    offset: 300,
})
$(".list-group-item").click(function() {
    $('.list-group-item').filter(function() {
        //return this.href == id;
    }).parent().removeClass('text-primary');
});

function check_theme(color_val) {
    $('#theme_color').prop('checked', false);
    $('input[value="' + color_val + '"]').prop('checked', true);
    $('[data-value="' + color_val + '"]').parent().children('a').removeClass('active_color');
    $('[data-value="' + color_val + '"]').addClass('active_color').removeClass('theme_color');

}

$(document).on('change', '[name=storage_setting]', function() {
    if ($(this).val() == 's3') {
        $('.s3-setting').removeClass('d-none');
        $('.wasabi-setting').addClass('d-none');
        $('.local-setting').addClass('d-none');
    } else if ($(this).val() == 'wasabi') {
        $('.s3-setting').addClass('d-none');
        $('.wasabi-setting').removeClass('d-none');
        $('.local-setting').addClass('d-none');
    } else {
        $('.s3-setting').addClass('d-none');
        $('.wasabi-setting').addClass('d-none');
        $('.local-setting').removeClass('d-none');
    }
});
</script>

<script>
if ($('#cust-darklayout').length > 0) {
    var custthemedark = document.querySelector("#cust-darklayout");
    custthemedark.addEventListener("click", function() {
        if (custthemedark.checked) {
            $('#style').attr('href', '<?php echo e(env('
                APP_URL ')); ?>' + 'assets/css/style-dark.css');

            $('.dash-sidebar .main-logo a img').attr('src', '<?php echo e($logo . $light_logo); ?>');
        } else {
            $('#style').attr('href', '<?php echo e(env('
                APP_URL ')); ?>' + 'assets/css/style.css');
            $('.dash-sidebar .main-logo a img').attr('src', '<?php echo e($logo . $dark_logo); ?>');

        }
    });
}
if ($('#cust-theme-bg').length > 0) {
    var custthemebg = document.querySelector("#cust-theme-bg");
    custthemebg.addEventListener("click", function() {
        if (custthemebg.checked) {
            document.querySelector(".dash-sidebar").classList.add("transprent-bg");
            document
                .querySelector(".dash-header:not(.dash-mob-header)")
                .classList.add("transprent-bg");
        } else {
            document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
            document
                .querySelector(".dash-header:not(.dash-mob-header)")
                .classList.remove("transprent-bg");
        }
    });
}
</script>

<script>       
    $('.colorPicker').on('click', function(e) {
               $('body').removeClass('custom-color');
               if (/^theme-\d+$/) {
                   $('body').removeClassRegex(/^theme-\d+$/);
               }
               $('body').addClass('custom-color');
               $('.themes-color-change').removeClass('active_color');
               $(this).addClass('active_color');
               const input = document.getElementById("color-picker");
               setColor();
               input.addEventListener("input", setColor);
               function setColor() {
                document.documentElement.style.setProperty('--color-customColor', input.value);
                }
               $(`input[name='color_flag`).val('true');
           });
   
           $('.themes-color-change').on('click', function() {
   
           $(`input[name='color_flag`).val('false');
   
               var color_val = $(this).data('value');
               $('body').removeClass('custom-color');
               if(/^theme-\d+$/)
               {
                   $('body').removeClassRegex(/^theme-\d+$/);                
               }
               $('body').addClass(color_val);
               $('.theme-color').prop('checked', false);
               $('.themes-color-change').removeClass('active_color');
               $('.colorPicker').removeClass('active_color');
               $(this).addClass('active_color');
               $(`input[value=${color_val}]`).prop('checked', true);
           });
           
           $.fn.removeClassRegex = function(regex) {
       return $(this).removeClass(function(index, classes) {
           return classes.split(/\s+/).filter(function(c) {
               return regex.test(c);
           }).join(' ');
       });
   };
   </script>
   
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Settings')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
<div class="d-inline-block">
    <h5 class="h4 d-inline-block font-weight-400 mb-0"><?php echo e(__('Settings')); ?></h5>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
<li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Settings')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <!--Company-->
                    <?php if(\Auth::user()->type == 'company'): ?>
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#site-Setting"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Site Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#company-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Company Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#system-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('System Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#email-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#estimate-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Estimate Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#invoice-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Invoice Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#payment-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Payment Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#time-tracker-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Time Tracker Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#zoom-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Zoom Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#slack-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Slack Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#telegram-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Telegram Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#twillio-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Twillio Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#email-notification-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Notification Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#google-calendar-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Google Calendar Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#webhook-settings" id="webhook-tab"
                            class="list-group-item list-group-item-action border-0"> <?php echo e(__('Webhook Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                    </div>
                    <?php endif; ?>

                    <!--Super Admin-->
                    <?php if(\Auth::user()->type == 'super admin'): ?>
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#brand-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Brand Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#email-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#pusher-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Pusher Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#payment-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Payment Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#recaptcha-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('ReCaptcha Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#storage-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Storage Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#cache-settings"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Cache Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#SEO-settings" id="SEO-tab" class="list-group-item list-group-item-action border-0">
                            <?php echo e(__('SEO Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#cookie-settings" id="cookie-settings-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Cookie Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#pills-chatgpt-settings" id="cookie-settings-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('ChatGpt Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xl-9">
                <?php if(\Auth::user()->type == 'company'): ?>
                <div id="site-Setting" class="card">
                    <?php echo e(Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-header">
                        <h5><?php echo e(__('Site Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your site details')); ?></small>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="small-title"><?php echo e(__('Dark Logo')); ?></h5>
                                    </div>
                                    <div class="card-body setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="logo-content text-center py-2">

                                                    <a href="<?php echo e($logo . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png') . '?timestamp=' . time()); ?>"
                                                        target="_blank">
                                                        <img id="blah" alt="your image"
                                                            src="<?php echo e($logo . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png') . '?timestamp=' . time()); ?>"
                                                            width="150px" class="big-logo">
                                                    </a>

                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="choose-files mt-4">
                                                    <label for="company_logo_dark">
                                                        <div class=" bg-primary logo m-auto"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input type="file" class="form-control file"
                                                            name="company_logo_dark" id="company_logo_dark"
                                                            data-filename="edit-logo" accept=".jpeg,.jpg,.png"
                                                            accept=".jpeg,.jpg,.png"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="small-title"><?php echo e(__('Light Logo')); ?></h5>
                                    </div>
                                    <div class="card-body setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="logo-content text-center py-2">
                                                    <a href="<?php echo e($logo . (isset($light_logo) && !empty($light_logo) ? $light_logo : '2-logo-light.png') . '?timestamp=' . time()); ?>"
                                                        target="_blank">
                                                        <img id="blah1" alt="your image"
                                                            src="<?php echo e($logo . (isset($light_logo) && !empty($light_logo) ? $light_logo : '2-logo-light.png') . '?timestamp=' . time()); ?>"
                                                            width="150px" class="big-logo img_setting"
                                                            style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="choose-files mt-4">
                                                    <label for="company_logo_light">
                                                        <div class=" bg-primary white_logo m-auto"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input type="file" class="form-control file"
                                                            name="company_logo_light" id="company_logo_light"
                                                            data-filename="edit-white_logo" accept=".jpeg,.jpg,.png"
                                                            accept=".jpeg,.jpg,.png"
                                                            onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="small-title"><?php echo e(__('Favicon')); ?></h5>
                                    </div>
                                    <div class="card-body setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="logo-content text-center py-2">
                                                    <!-- <img src="<?php echo e(asset(Storage::url('uploads/logo/favicon.png'))); ?>"
                                                                                                                                                                                        class="small-logo" alt="" /> -->
                                                    <a href="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : '2_favicon.png') . '?timestamp=' . time()); ?>"
                                                        target="_blank">
                                                        <img id="blah2" alt="your image"
                                                            src="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : '2_favicon.png') . '?timestamp=' . time()); ?>"
                                                            width="80px" class="big-logo img_setting">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="choose-files mt-4">
                                                    <label for="favicon">
                                                        <div class="bg-primary favicon m-auto"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input type="file" class="form-control file"
                                                            name="company_favicon" id="favicon"
                                                            data-filename="edit-favicon" accept=".jpeg,.jpg,.png"
                                                            accept=".jpeg,.jpg,.png"
                                                            onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('title_text', __('Title Text'), ['class' => 'col-form-label text-dark'])); ?>

                                    <?php echo e(Form::text('title_text', Utility::getValByName('title_text'), ['class' => 'form-control', 'placeholder' => __('Enter Header Title Text')])); ?>

                                    <?php $__errorArgs = ['title_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-title_text" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('footer_text', __('Footer Text'), ['class' => 'col-form-label text-dark'])); ?>

                                    <?php echo e(Form::text('footer_text', Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')])); ?>

                                    <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-footer_text" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('default_language', __('Default Language'), ['class' => 'col-form-label text-dark'])); ?>

                                    <select name="default_language" id="default_language" class="form-control select2">
                                        <?php $__currentLoopData = Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if(Utility::getValByName('default_language')==$code): ?> selected <?php endif; ?>
                                            value="<?php echo e($code); ?>"><?php echo e(Str::upper($language)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['default_language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-default_language" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col switch-width">
                                    <div class="form-group ml-2 mr-3 ">
                                        <label class="form-label"><?php echo e(__('Enable RTL')); ?></label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                class="" name="SITE_RTL" id="SITE_RTL"
                                                <?php echo e($settings['SITE_RTL'] == 'on' ? 'checked="checked"' : ''); ?>>
                                            <label class="custom-control-label" for="SITE_RTL"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h4 class="small-title"><?php echo e(__('Theme Customizer')); ?></h4>
                            <div class="setting-card setting-logo-box p-3">
                                <div class="row">
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="credit-card" class="me-2"></i><?php echo e(__('Primary color settings')); ?>

                                        </h6>

                                        <hr class="my-2" />
                                        <div class="color-wrp">
                                            <div class="theme-color themes-color">
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-1' ? 'active_color' : ''); ?>" data-value="theme-1"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-1"<?php echo e($color == 'theme-1' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-2' ? 'active_color' : ''); ?>" data-value="theme-2"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-2"<?php echo e($color == 'theme-2' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-3' ? 'active_color' : ''); ?>" data-value="theme-3"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-3"<?php echo e($color == 'theme-3' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-4' ? 'active_color' : ''); ?>" data-value="theme-4"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-4"<?php echo e($color == 'theme-4' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-5' ? 'active_color' : ''); ?>" data-value="theme-5"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-5"<?php echo e($color == 'theme-5' ? 'checked' : ''); ?>>
                                                <br>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-6' ? 'active_color' : ''); ?>" data-value="theme-6"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-6"<?php echo e($color == 'theme-6' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-7' ? 'active_color' : ''); ?>" data-value="theme-7"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-7"<?php echo e($color == 'theme-7' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-8' ? 'active_color' : ''); ?>" data-value="theme-8"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-8"<?php echo e($color == 'theme-8' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-9' ? 'active_color' : ''); ?>" data-value="theme-9"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-9"<?php echo e($color == 'theme-9' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-10' ? 'active_color' : ''); ?>" data-value="theme-10"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-10"<?php echo e($color == 'theme-10' ? 'checked' : ''); ?>>
                                            </div>
                                            <div class="color-picker-wrp ">
                                                    <input type="color" value="<?php echo e($color ? $color : ''); ?>" class="colorPicker <?php echo e(isset($flag) && $flag == 'true' ? 'active_color' : ''); ?>" name="custom_color" id="color-picker">                                             
                                                    <input type='hidden' name="color_flag" value = <?php echo e(isset($flag) && $flag == 'true' ? 'true' : 'false'); ?>>
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="layout" class="me-2"></i><?php echo e(__('Sidebar settings')); ?>

                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="cust_theme_bg" <?php echo e(!empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : ''); ?>/>
                                            <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg"
                                            ><?php echo e(__('Transparent layout')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="sun" class="me-2"></i><?php echo e(__('Layout settings')); ?>

                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout"<?php echo e(!empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : ''); ?> />
                                            <label class="form-check-label f-w-600 pl-1" for="cust-darklayout"><?php echo e(__('Dark Layout')); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Company Setting-->
                <div id="company-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Company Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your company details')); ?></small>
                    </div>
                    <?php echo e(Form::model($settings, ['route' => 'company.setting', 'method' => 'post'])); ?>

                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_name *', __('Company Name *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_name', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Your Company Name')])); ?>

                                <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_name" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_address', __('Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_address', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Your Address')])); ?>

                                <?php $__errorArgs = ['company_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_address" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_city', __('City'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_city', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Your City')])); ?>

                                <?php $__errorArgs = ['company_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_city" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_state', __('State'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_state', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Your State')])); ?>

                                <?php $__errorArgs = ['company_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_state" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_zipcode', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Zip/Post Code')])); ?>

                                <?php $__errorArgs = ['company_zipcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_zipcode" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group  col-md-6">
                                <?php echo e(Form::label('company_country', __('Country'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_country', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Your Country')])); ?>

                                <?php $__errorArgs = ['company_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_country" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_telephone', __('Telephone'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_telephone', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Telephone Number')])); ?>

                                <?php $__errorArgs = ['company_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_telephone" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_email', __('System Email *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Your System Email')])); ?>

                                <?php $__errorArgs = ['company_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_email" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_email_from_name', __('Email (From Name) *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('company_email_from_name', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Your Email (From Name)')])); ?>

                                <?php $__errorArgs = ['company_email_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_email_from_name" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('registration_number', __('Company Registration Number *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('registration_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Company Registration Number')])); ?>

                                <?php $__errorArgs = ['registration_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-registration_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('vat_number', __('VAT Number *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('vat_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Your VAT Number')])); ?>

                                <?php $__errorArgs = ['vat_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-vat_number" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo e(Form::label('timezone', __('Timezone'), ['class' => 'form-label'])); ?>

                                <select type="text" name="timezone" class="form-control custom-select" id="timezone">
                                    <option value=""><?php echo e(__('Select Timezone')); ?></option>
                                    <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k); ?>" <?php echo e($setting['timezone'] == $k ? 'selected' : ''); ?>>
                                        <?php echo e($timezone); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_start_time', __('Company Start Time *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::time('company_start_time', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['company_start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_start_time" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('company_end_time', __('Company End Time *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::time('company_end_time', null, ['class' => 'form-control'])); ?>

                                <?php $__errorArgs = ['company_end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-company_end_time" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--System Setting-->
                <div id="system-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('System Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your system details')); ?></small>
                    </div>

                    <?php echo e(Form::model($settings, ['route' => 'system.setting', 'method' => 'post'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('site_currency', __('Currency *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('site_currency', $settings['site_currency'], ['class' => 'form-control font-style', 'placeholder' => 'Enter Currency'])); ?>

                                <?php $__errorArgs = ['site_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-site_currency" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('site_currency_symbol', __('Currency Symbol *'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('site_currency_symbol', $settings['site_currency_symbol'], ['class' => 'form-control', 'placeholder' => 'Enter Currency Symbol'])); ?>

                                <?php $__errorArgs = ['site_currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-site_currency_symbol" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"
                                        for="example3cols3Input"><?php echo e(__('Currency Symbol Position')); ?></label>
                                    <div class="row px-2 ps-3">
                                        <div class="form-check col-md-6 ">
                                            <input class="form-check-input" type="radio"
                                                name="site_currency_symbol_position" value="pre"
                                                 <?php if($settings['site_currency_symbol_position']=='pre' ): ?> checked <?php endif; ?>
                                                id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                <?php echo e(__('Pre')); ?>

                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio"
                                                name="site_currency_symbol_position" value="post"
                                                 <?php if($settings['site_currency_symbol_position']=='post' ): ?> checked <?php endif; ?>
                                                id="flexCheckChecked" checked>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                <?php echo e(__('Post')); ?>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_date_format" class="form-label"><?php echo e(__('Date Format')); ?></label>
                                <select type="text" name="site_date_format" class="form-control selectric"
                                    id="site_date_format">
                                    <option value="M j, Y" <?php if(@$settings['site_date_format']=='M j, Y' ): ?>
                                        selected="selected" <?php endif; ?>>Jan 1,2015
                                    </option>
                                    <option value="d-m-Y" <?php if(@$settings['site_date_format']=='d-m-Y' ): ?>
                                        selected="selected" <?php endif; ?>>d-m-y</option>
                                    <option value="m-d-Y" <?php if(@$settings['site_date_format']=='m-d-Y' ): ?>
                                        selected="selected" <?php endif; ?>>m-d-y</option>
                                    <option value="Y-m-d" <?php if(@$settings['site_date_format']=='Y-m-d' ): ?>
                                        selected="selected" <?php endif; ?>>y-m-d</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_time_format" class="form-label"><?php echo e(__('Time Format')); ?></label>
                                <select type="text" name="site_time_format" class="form-control selectric"
                                    id="site_time_format">
                                    <option value="g:i A" <?php if(@$settings['site_time_format']=='g:i A' ): ?>
                                        selected="selected" <?php endif; ?>>10:30 PM
                                    </option>
                                    <option value="g:i a" <?php if(@$settings['site_time_format']=='g:i a' ): ?>
                                        selected="selected" <?php endif; ?>>10:30 pm
                                    </option>
                                    <option value="H:i" <?php if(@$settings['site_time_format']=='H:i' ): ?> selected="selected"
                                        <?php endif; ?>>22:30</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('client_prefix', __('Client Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('client_prefix', null, ['class' => 'form-control', 'placeholder' => 'Enter Client Prefix'])); ?>

                                <?php $__errorArgs = ['client_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-client_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('employee_prefix', __('Employee Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('employee_prefix', null, ['class' => 'form-control', 'placeholder' => 'Enter Employee Prefix '])); ?>

                                <?php $__errorArgs = ['employee_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-employee_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('estimate_prefix', __('Estimate Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('estimate_prefix', null, ['class' => 'form-control', 'placeholder' => 'Enter Estimate Prefix'])); ?>

                                <?php $__errorArgs = ['estimate_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-estimate_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('invoice_prefix', __('Invoice Prefix'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('invoice_prefix', null, ['class' => 'form-control', 'placeholder' => 'Enter Invoice Prefix'])); ?>

                                <?php $__errorArgs = ['invoice_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-invoice_prefix" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('footer_title', __('Estimate/Invoice Footer Title'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('footer_title', null, ['class' => 'form-control', 'placeholder' => 'Estimate/Invoice Footer Title'])); ?>

                                <?php $__errorArgs = ['footer_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-footer_title" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-12">
                                <?php echo e(Form::label('footer_notes', __('Estimate/Invoice Footer Notes'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::textarea('footer_notes', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Estimate/Invoice Footer Notes'])); ?>

                                <?php $__errorArgs = ['footer_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-footer_notes" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>


                </div>

                
                <div id="email-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Email Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your email details')); ?></small>
                    </div>
                    <?php echo e(Form::open(['route' => 'email.setting', 'method' => 'post'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_driver', $settings['mail_driver'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')])); ?>

                                <?php $__errorArgs = ['mail_driver'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_driver" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_host', __('Mail Host'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_host', $settings['mail_host'], ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')])); ?>

                                <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_driver" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_port', __('Mail Port'))); ?>

                                <?php echo e(Form::text('mail_port', $settings['mail_port'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')])); ?>

                                <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_port" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_username', __('Mail Username'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_username', $settings['mail_username'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')])); ?>

                                <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_username" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_password', __('Mail Password'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_password', $settings['mail_password'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')])); ?>

                                <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_password" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_encryption', $settings['mail_encryption'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')])); ?>

                                <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_encryption" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group  col-md-6">
                                <?php echo e(Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_from_address', $settings['mail_from_address'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')])); ?>

                                <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_from_address" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_from_name', $settings['mail_from_name'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')])); ?>

                                <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_from_name" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div
                            class="d-flex justify-content-between justify-content-xs-center align-items-center flex-wrap">
                            <div class="form-group ">
                                <a href="#" data-url="<?php echo e(route('test.mail')); ?>" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-bs-whatever="<?php echo e(__('Send Test Mail')); ?>"
                                    class="btn btn-print-invoice btn-primary send_email">
                                    <?php echo e(__('Send Test Mail')); ?>

                                </a>
                            </div>

                            <div class="form-group text-end">
                                <input class="btn btn-print-invoice  btn-primary " type="submit"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Estimate Setting-->
                <div id="estimate-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Estimate Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit details about your Company estimate')); ?></small>
                    </div>
                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-3">
                                <div class="card-header card-body">
                                    <h5></h5>
                                    <form id="setting-form" method="post"
                                        action="<?php echo e(route('estimate.template.setting')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="address"
                                                class="col-form-label"><?php echo e(__('Estimation Template')); ?></label>
                                            <select class="form-control" name="estimate_template" data-toggle="select">
                                                <?php $__currentLoopData = Utility::templateData()['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php echo e(isset($settings['estimate_template']) && $settings['estimate_template'] == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($template); ?> </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Color Input')); ?></label>
                                            <div class="row gutters-xs">
                                                <?php $__currentLoopData = Utility::templateData()['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="estimate_color" type="radio" value="<?php echo e($color); ?>"
                                                            class="colorinput-input"
                                                            <?php echo e(isset($settings['estimate_color']) && $settings['estimate_color'] == $color ? 'checked' : ''); ?>>
                                                        <span class="colorinput-color"
                                                            style="background: #<?php echo e($color); ?>"></span>
                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Estimation Logo')); ?></label>
                                            <div class="choose-files">
                                                <label for="estimation_logo">
                                                    <div class=" bg-primary estimation_logo_update" style="width:180px">
                                                        <i class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <input type="file" class="form-control file" name="estimation_logo"
                                                        id="estimation_logo" data-filename="edit-logo"
                                                        accept=".jpeg,.jpg,.png" accept=".jpeg,.jpg,.png">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="<?php echo e(__('Save')); ?>"
                                                class="btn btn-print-invoice  btn-primary ">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <?php if(isset($settings['estimate_template']) && isset($settings['estimate_color'])): ?>
                                <iframe id="estimate_frame" class="w-100 h-1220" frameborder="0"
                                    src="<?php echo e(route('estimate.preview', [$settings['estimate_template'], $settings['estimate_color']])); ?>"></iframe>
                                <?php else: ?>
                                <iframe id="estimate_frame" class="w-100 h-1220" frameborder="0"
                                    src="<?php echo e(route('estimate.preview', ['template1', 'fffff'])); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Invoice Setting-->
                <div id="invoice-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Invoice Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit details about your Company invoice')); ?></small>
                    </div>
                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-3">
                                <div class="card-header card-body">
                                    <h5></h5>
                                    <form id="setting-form" method="post"
                                        action="<?php echo e(route('invoice.template.setting')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="address"
                                                class="col-form-label"><?php echo e(__('Invoice Template')); ?></label>
                                            <select class="form-control select2" name="invoice_template">
                                                <?php $__currentLoopData = Utility::templateData()['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php echo e(isset($settings['invoice_template']) && $settings['invoice_template'] == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($template); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Color Input')); ?></label>
                                            <div class="row gutters-xs">
                                                <?php $__currentLoopData = Utility::templateData()['colors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="invoice_color" type="radio" value="<?php echo e($color); ?>"
                                                            class="colorinput-input"
                                                            <?php echo e(isset($settings['invoice_color']) && $settings['invoice_color'] == $color ? 'checked' : ''); ?>>
                                                        <span class="colorinput-color"
                                                            style="background: #<?php echo e($color); ?>"></span>
                                                    </label>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label"><?php echo e(__('Invoice Logo')); ?></label>
                                            <div class="choose-files">
                                                <label for="invoice_logo">
                                                    <div class=" bg-primary invoice_logo_update" style="width:180px"> <i
                                                            class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <input type="file" class="form-control file" name="invoice_logo"
                                                        id="invoice_logo" data-filename="edit-logo"
                                                        accept=".jpeg,.jpg,.png" accept=".jpeg,.jpg,.png">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="<?php echo e(__('Save')); ?>"
                                                class="btn btn-print-invoice  btn-primary ">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <?php if(isset($settings['invoice_template']) && isset($settings['invoice_color'])): ?>
                                <iframe id="invoice_frame" class="w-100 h-1220" frameborder="0"
                                    src="<?php echo e(route('invoice.preview', [$settings['invoice_template'], $settings['invoice_color']])); ?>"></iframe>
                                <?php else: ?>
                                <iframe id="invoice_frame" class="w-100 h-1220" frameborder="0"
                                    src="<?php echo e(route('invoice.preview', ['template1', 'fffff'])); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Payment Setting-->
                <div id="payment-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Payment Settings')); ?></h5>
                        <small
                            class="text-muted"><?php echo e(__('These details will be used to collect invoice payments. Each invoice will have a payment button based on the below configuration.')); ?></small>
                    </div>
                    <form id="setting-form" method="post" action="<?php echo e(route('company.payment.setting')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">

                                </div>
                            </div>
                            <div class="faq justify-content-center">
                                <div class="col-sm-12 col-md-10 col-xxl-12">
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">

                                        <!-- Bank Transfer -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading15">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse15"
                                                    aria-expanded="false" aria-controls="collapse15">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Bank Transfer')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_bank_transfer_enabled"
                                                                id="is_bank_transfer_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_bank_transfer_enabled']) && $company_payment_setting['is_bank_transfer_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label"
                                                                for="customswitchv1-1"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse15" class="accordion-collapse collapse"
                                                aria-labelledby="heading15" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <textarea name="bank_details" id="bank_details" cols="30"
                                                                rows="6" class="form-control"><?php echo e(!isset($company_payment_setting['bank_details']) || is_null($company_payment_setting['bank_details']) ? '' : $company_payment_setting['bank_details']); ?>

                                                                </textarea>
                                                            <small class="text-xs">
                                                                <?php echo e(__('Example: bank name </br> Account Number : 0000 0000 </br>')); ?>

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stripe -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="false" aria-controls="collapseOne">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Stripe')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_stripe_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_stripe_enabled"
                                                                name="is_stripe_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_stripe_enabled']) && $company_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <?php echo e(Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-form-label'])); ?>

                                                                    <?php echo e(Form::text('stripe_key', isset($company_payment_setting['stripe_key']) ? $company_payment_setting['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Stripe Key')])); ?>

                                                                    <?php if($errors->has('stripe_key')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('stripe_key')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <?php echo e(Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'col-form-label'])); ?>

                                                                    <?php echo e(Form::text('stripe_secret', isset($company_payment_setting['stripe_secret']) ? $company_payment_setting['stripe_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Stripe Secret')])); ?>

                                                                    <?php if($errors->has('stripe_secret')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('stripe_secret')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paypal -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Paypal')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paypal_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_paypal_enabled"
                                                                name="is_paypal_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_paypal_enabled']) && $company_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="d-flex">
                                                        <div class="mr-2" style="margin-right: 15px;">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-label text-dark me-2">
                                                                        <input type="radio" name="paypal_mode"
                                                                            value="sandbox" class="form-check-input"
                                                                            <?php echo e((isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == '') || (isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-label text-dark me-2">
                                                                        <input type="radio" name="paypal_mode"
                                                                            value="live" class="form-check-input"
                                                                            <?php echo e(isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label class="col-form-label"
                                                                        for="paypal_client_id"><?php echo e(__('Client ID')); ?></label>
                                                                    <input type="text" name="paypal_client_id"
                                                                        id="paypal_client_id" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['paypal_client_id']) || is_null($company_payment_setting['paypal_client_id']) ? '' : $company_payment_setting['paypal_client_id']); ?>"
                                                                        placeholder="<?php echo e(__('Client ID')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label class="col-form-label"
                                                                        for="paypal_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="paypal_secret_key"
                                                                        id="paypal_secret_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['paypal_secret_key']) ? $company_payment_setting['paypal_secret_key'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paystack -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Paystack')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paystack_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_paystack_enabled"
                                                                name="is_paystack_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="paystack_public_key"
                                                                        id="paystack_public_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['paystack_public_key']) ? $company_payment_setting['paystack_public_key'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Public Key')); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key"
                                                                        class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="paystack_secret_key"
                                                                        id="paystack_secret_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['paystack_secret_key']) ? $company_payment_setting['paystack_secret_key'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Secret Key')); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Flutterwave -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFour">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Flutterwave')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_flutterwave_enabled"
                                                                value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_flutterwave_enabled"
                                                                name="is_flutterwave_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="flutterwave_public_key"
                                                                        id="flutterwave_public_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['flutterwave_public_key']) ? $company_payment_setting['flutterwave_public_key'] : ''); ?>"
                                                                        placeholder="Public Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key"
                                                                        class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="flutterwave_secret_key"
                                                                        id="flutterwave_secret_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['flutterwave_secret_key']) ? $company_payment_setting['flutterwave_secret_key'] : ''); ?>"
                                                                        placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Razorpay -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFive">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                    aria-expanded="false" aria-controls="collapseFive">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Razorpay')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_razorpay_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_razorpay_enabled"
                                                                name="is_razorpay_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse"
                                                aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id"
                                                                        class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="razorpay_public_key"
                                                                        id="razorpay_public_key" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['razorpay_public_key']) || is_null($company_payment_setting['razorpay_public_key']) ? '' : $company_payment_setting['razorpay_public_key']); ?>"
                                                                        placeholder="Public Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key"
                                                                        class="col-form-label">
                                                                        <?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="razorpay_secret_key"
                                                                        id="razorpay_secret_key" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['razorpay_secret_key']) || is_null($company_payment_setting['razorpay_secret_key']) ? '' : $company_payment_setting['razorpay_secret_key']); ?>"
                                                                        placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mercado Pago -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingseven">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseseven"
                                                    aria-expanded="false" aria-controls="collapseseven">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Mercado Pago')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_mercado_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_mercado_enabled"
                                                                name="is_mercado_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseseven" class="accordion-collapse collapse"
                                                aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="col-md-12 pb-4">
                                                        <label class="coingate-label col-form-label"
                                                            for="mercado_mode"><?php echo e(__('Mercado Mode')); ?></label>
                                                        <br>
                                                        <div class="d-flex">
                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark me-2">
                                                                            <input type="radio" name="mercado_mode"
                                                                                value="sandbox" class="form-check-input"
                                                                                <?php echo e((isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == '') || (isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Sandbox')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mr-2">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark me-2">
                                                                            <input type="radio" name="mercado_mode"
                                                                                value="live" class="form-check-input"
                                                                                <?php echo e(isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Live')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="mercado_access_token"
                                                                        class="col-form-label"><?php echo e(__('Access Token')); ?></label>
                                                                    <input type="text" name="mercado_access_token"
                                                                        id="mercado_access_token" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['mercado_access_token']) ? $company_payment_setting['mercado_access_token'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Access Token')); ?>" />
                                                                    <?php if($errors->has('mercado_secret_key')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('mercado_access_token')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paytm -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingSix">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                                    aria-expanded="false" aria-controls="collapseSix">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Paytm')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paytm_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_paytm_enabled"
                                                                name="is_paytm_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseSix" class="accordion-collapse collapse"
                                                aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="col-md-12 pb-4">
                                                        <label class="paypal-label col-form-label"
                                                            for="paypal_mode"><?php echo e(__('Paytm Environment')); ?></label>
                                                        <br>
                                                        <div class="d-flex">
                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark me-2">
                                                                            <input type="radio" name="paytm_mode"
                                                                                value="local" class="form-check-input"
                                                                                <?php echo e(!isset($company_payment_setting['paytm_mode']) || $company_payment_setting['paytm_mode'] == '' || $company_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Local')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mr-2">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark me-2">
                                                                            <input type="radio" name="paytm_mode"
                                                                                value="production"
                                                                                class="form-check-input"
                                                                                <?php echo e(isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Production')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gy-4">
                                                        <div class="col-lg-4">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paytm_public_key"
                                                                        class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                    <input type="text" name="paytm_merchant_id"
                                                                        id="paytm_merchant_id" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['paytm_merchant_id']) ? $company_payment_setting['paytm_merchant_id'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Merchant ID')); ?>" />
                                                                    <?php if($errors->has('paytm_merchant_id')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('paytm_merchant_id')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paytm_secret_key"
                                                                        class="col-form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                    <input type="text" name="paytm_merchant_key"
                                                                        id="paytm_merchant_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['paytm_merchant_key']) ? $company_payment_setting['paytm_merchant_key'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Merchant Key')); ?>" />
                                                                    <?php if($errors->has('paytm_merchant_key')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('paytm_merchant_key')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paytm_industry_type"
                                                                        class="col-form-label"><?php echo e(__('Industry Type')); ?></label>
                                                                    <input type="text" name="paytm_industry_type"
                                                                        id="paytm_industry_type" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['paytm_industry_type']) ? $company_payment_setting['paytm_industry_type'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Industry Type')); ?>" />
                                                                    <?php if($errors->has('paytm_industry_type')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('paytm_industry_type')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mollie -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingeight">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseeight"
                                                    aria-expanded="false" aria-controls="collapseeight">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Mollie')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_mollie_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_mollie_enabled"
                                                                name="is_mollie_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseeight" class="accordion-collapse collapse"
                                                aria-labelledby="headingeight" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="col-form-label"><?php echo e(__('Mollie Api Key')); ?></label>
                                                                    <input type="text" name="mollie_api_key"
                                                                        id="mollie_api_key" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['mollie_api_key']) || is_null($company_payment_setting['mollie_api_key']) ? '' : $company_payment_setting['mollie_api_key']); ?>"
                                                                        placeholder="Mollie Api Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="mollie_profile_id"
                                                                        class="col-form-label"><?php echo e(__('Mollie Profile Id')); ?></label>
                                                                    <input type="text" name="mollie_profile_id"
                                                                        id="mollie_profile_id" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['mollie_profile_id']) || is_null($company_payment_setting['mollie_profile_id']) ? '' : $company_payment_setting['mollie_profile_id']); ?>"
                                                                        placeholder="Mollie Profile Id">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="mollie_partner_id"
                                                                        class="col-form-label"><?php echo e(__('Mollie Partner Id')); ?></label>
                                                                    <input type="text" name="mollie_partner_id"
                                                                        id="mollie_partner_id" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['mollie_partner_id']) || is_null($company_payment_setting['mollie_partner_id']) ? '' : $company_payment_setting['mollie_partner_id']); ?>"
                                                                        placeholder="Mollie Partner Id">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Skrill -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingnine">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapsenine"
                                                    aria-expanded="false" aria-controls="collapsenine">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Skrill')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_skrill_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_skrill_enabled"
                                                                name="is_skrill_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapsenine" class="accordion-collapse collapse"
                                                aria-labelledby="headingnine" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="col-form-label"><?php echo e(__('Skrill Email')); ?></label>
                                                                    <input type="email" name="skrill_email"
                                                                        id="skrill_email" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['skrill_email']) ? $company_payment_setting['skrill_email'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Enter Skrill Email')); ?>" />
                                                                    <?php if($errors->has('skrill_email')): ?>
                                                                    <span class="invalid-feedback d-block">
                                                                        <?php echo e($errors->first('skrill_email')); ?>

                                                                    </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CoinGate -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingten">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseten"
                                                    aria-expanded="false" aria-controls="collapseten">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('CoinGate')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_coingate_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_coingate_enabled"
                                                                name="is_coingate_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseten" class="accordion-collapse collapse"
                                                aria-labelledby="headingten" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="col-md-12 pb-4">
                                                        <label class="col-form-label"
                                                            for="coingate_mode"><?php echo e(__('CoinGate Mode')); ?></label>
                                                        <br>
                                                        <div class="d-flex">
                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark me-2">
                                                                            <input type="radio" name="coingate_mode"
                                                                                value="sandbox" class="form-check-input"
                                                                                <?php echo e(!isset($company_payment_setting['coingate_mode']) || $company_payment_setting['coingate_mode'] == '' || $company_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Sandbox')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mr-2">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark me-2">
                                                                            <input type="radio" name="coingate_mode"
                                                                                value="live" class="form-check-input"
                                                                                <?php echo e(isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Live')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="coingate_auth_token"
                                                                        class="col-form-label"><?php echo e(__('CoinGate Auth Token')); ?></label>
                                                                    <input type="text" name="coingate_auth_token"
                                                                        id="coingate_auth_token" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['coingate_auth_token']) || is_null($company_payment_setting['coingate_auth_token']) ? '' : $company_payment_setting['coingate_auth_token']); ?>"
                                                                        placeholder="CoinGate Auth Token">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PaymentWall -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingeleven">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseeleven"
                                                    aria-expanded="false" aria-controls="collapseeleven">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('PaymentWall')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paymentwall_enabled"
                                                                value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_paymentwall_enabled"
                                                                name="is_paymentwall_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_paymentwall_enabled']) && $company_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseeleven" class="accordion-collapse collapse"
                                                aria-labelledby="headingeleven" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_public_key"
                                                                        class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="paymentwall_public_key"
                                                                        id="paymentwall_public_key" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['paymentwall_public_key']) || is_null($company_payment_setting['paymentwall_public_key']) ? '' : $company_payment_setting['paymentwall_public_key']); ?>"
                                                                        placeholder="<?php echo e(__('Public Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_private_key"
                                                                        class="col-form-label"><?php echo e(__('Private Key')); ?></label>
                                                                    <input type="text" name="paymentwall_private_key"
                                                                        id="paymentwall_private_key"
                                                                        class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['paymentwall_private_key']) || is_null($company_payment_setting['paymentwall_private_key']) ? '' : $company_payment_setting['paymentwall_private_key']); ?>"
                                                                        placeholder="<?php echo e(__('Private Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Toyyibpay -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-2-13">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse12"
                                                    aria-expanded="true" aria-controls="collapse12">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Toyyibpay')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <label class="custom-control-label form-control-label"
                                                            for="is_toyyibpay_enabled">
                                                            <span class="me-2"><?php echo e(__('Enable:')); ?></span></label>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_toyyibpay_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_toyyibpay_enabled" id="is_toyyibpay_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_toyyibpay_enabled']) && $company_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse12" class="accordion-collapse collapse"
                                                aria-labelledby="heading-2-13" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="toyyibpay_secret_key"
                                                                    class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="toyyibpay_secret_key"
                                                                    id="toyyibpay_secret_key" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['toyyibpay_secret_key']) || is_null($company_payment_setting['toyyibpay_secret_key']) ? '' : $company_payment_setting['toyyibpay_secret_key']); ?>"
                                                                    placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="category_code"
                                                                    class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                                <input type="text" name="category_code"
                                                                    id="category_code" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['category_code']) || is_null($company_payment_setting['category_code']) ? '' : $company_payment_setting['category_code']); ?>"
                                                                    placeholder="<?php echo e(__('Category Code')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payfast -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading13">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse13"
                                                    aria-expanded="false" aria-controls="collapse13">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Payfast')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_payfast_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_payfast_enabled" id="is_payfast_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_payfast_enabled']) && $company_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label for="customswitch1-2"
                                                                class="form-check-label"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>

                                            <div class="accordion-collapse collapse" id="collapse13"
                                                aria-labelledby="heading13" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">

                                                        <label class="col-form-label"
                                                            for="payfast_mode"><?php echo e(__('Payfast Mode')); ?></label>
                                                        <br>
                                                        <div class="d-flex">
                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-labe text-dark">
                                                                            <input type="radio" name="payfast_mode"
                                                                                value="sandbox" class="form-check-input"
                                                                                <?php echo e(!isset($company_payment_setting['payfast_mode']) || $company_payment_setting['payfast_mode'] == '' || $company_payment_setting['payfast_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Sandbox')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mr-2">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-labe text-dark">
                                                                            <input type="radio" name="payfast_mode"
                                                                                value="live" class="form-check-input"
                                                                                <?php echo e(isset($store_settings['payfast_mode']) && $store_settings['payfast_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Live')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="merchant_id"
                                                                    class="col-form-label"><?php echo e(__('Merchant id')); ?></label>
                                                                <input type="text" name="payfast_merchant_id"
                                                                    id="payfast_merchant_id" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['payfast_merchant_id']) || is_null($company_payment_setting['payfast_merchant_id']) ? '' : $company_payment_setting['payfast_merchant_id']); ?>"
                                                                    placeholder="<?php echo e(__('Payfast Merchant Id')); ?>">

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="merchant_key"
                                                                    class="col-form-label"><?php echo e('Merchant key'); ?></label>
                                                                <input type="text" name="payfast_merchant_key"
                                                                    id="payfast_merchant_key" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['payfast_merchant_key']) || is_null($company_payment_setting['payfast_merchant_key']) ? '' : $company_payment_setting['payfast_merchant_key']); ?>"
                                                                    placeholder="<?php echo e(__('Payfast Merchant Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="payfast_signature"
                                                                    class="col-form-label"><?php echo e(__('Payfast Signature')); ?></label>
                                                                <input type="text" name="payfast_signature"
                                                                    id="payfast_signature" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['payfast_signature']) || is_null($company_payment_setting['payfast_signature']) ? '' : $company_payment_setting['payfast_signature']); ?>"
                                                                    placeholder="<?php echo e(__('Paystack Signature')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- iyzipay -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading14">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                    aria-expanded="false" aria-controls="collapse14">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Iyzipay')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_iyzipay_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_iyzipay_enabled"
                                                                name="is_iyzipay_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_iyzipay_enabled']) && $company_payment_setting['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse14" class="accordion-collapse collapse"
                                                aria-labelledby="heading14" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="d-flex">
                                                        <div class="mr-2" style="margin-right: 15px;">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-label text-dark me-2">
                                                                        <input type="radio" name="iyzipay_mode"
                                                                            value="sandbox" class="form-check-input"
                                                                            <?php echo e((isset($company_payment_setting['iyzipay_mode']) && $company_payment_setting['iyzipay_mode'] == '') || (isset($company_payment_setting['iyzipay_mode']) && $company_payment_setting['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-label text-dark me-2">
                                                                        <input type="radio" name="iyzipay_mode"
                                                                            value="live" class="form-check-input"
                                                                            <?php echo e(isset($company_payment_setting['iyzipay_mode']) && $company_payment_setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label class="col-form-label"
                                                                        for="iyzipay_public_key"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="iyzipay_public_key"
                                                                        id="iyzipay_public_key" class="form-control"
                                                                        value="<?php echo e(!isset($company_payment_setting['iyzipay_public_key']) || is_null($company_payment_setting['iyzipay_public_key']) ? '' : $company_payment_setting['iyzipay_public_key']); ?>"
                                                                        placeholder="<?php echo e(__('Public Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label class="col-form-label"
                                                                        for="iyzipay_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="iyzipay_secret_key"
                                                                        id="iyzipay_secret_key" class="form-control"
                                                                        value="<?php echo e(isset($company_payment_setting['iyzipay_secret_key']) ? $company_payment_setting['iyzipay_secret_key'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sspay -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-2-131">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse121"
                                                    aria-expanded="true" aria-controls="collapse121">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Sspay')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <label class="custom-control-label form-control-label"
                                                            for="is_sspay_enabled">
                                                            <span class="me-2"><?php echo e(__('Enable:')); ?></span></label>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_sspay_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_sspay_enabled" id="is_sspay_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_sspay_enabled']) && $company_payment_setting['is_sspay_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse121" class="accordion-collapse collapse"
                                                aria-labelledby="heading-2-131" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sspay_secret_key"
                                                                    class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="sspay_secret_key"
                                                                    id="sspay_secret_key" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['sspay_secret_key']) || is_null($company_payment_setting['sspay_secret_key']) ? '' : $company_payment_setting['sspay_secret_key']); ?>"
                                                                    placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sspay_category_code"
                                                                    class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                                <input type="text" name="sspay_category_code"
                                                                    id="sspay_category_code" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['sspay_category_code']) || is_null($company_payment_setting['sspay_category_code']) ? '' : $company_payment_setting['sspay_category_code']); ?>"
                                                                    placeholder="<?php echo e(__('Category Code')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- paytab -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwenty">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwenty"
                                                    aria-expanded="true" aria-controls="collapseTwenty">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Paytab')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                        <div
                                                            class="form-check form-switch d-inline-block custom-switch-v1">
                                                            <input type="hidden" name="is_paytab_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paytab_enabled" id="is_paytab_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_paytab_enabled']) && $company_payment_setting['is_paytab_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="custom-control-label form-label"
                                                                for="is_paytab_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseTwenty" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwenty" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paytab_profile_id"
                                                                    class="col-form-label"><?php echo e(__('Profile Id')); ?></label>
                                                                <input type="text" name="paytab_profile_id"
                                                                    id="paytab_profile_id" class="form-control"
                                                                    value="<?php echo e(isset($company_payment_setting['paytab_profile_id']) ? $company_payment_setting['paytab_profile_id'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Profile Id')); ?>">
                                                            </div>
                                                            <?php if($errors->has('paytab_profile_id')): ?>
                                                            <span class="invalid-feedback d-block">
                                                                <?php echo e($errors->first('paytab_profile_id')); ?>

                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paytab_server_key"
                                                                    class="col-form-label"><?php echo e(__('Server Key')); ?></label>
                                                                <input type="text" name="paytab_server_key"
                                                                    id="paytab_server_key" class="form-control"
                                                                    value="<?php echo e(isset($company_payment_setting['paytab_server_key']) ? $company_payment_setting['paytab_server_key'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Sspay Secret')); ?>">
                                                            </div>
                                                            <?php if($errors->has('paytab_server_key')): ?>
                                                            <span class="invalid-feedback d-block">
                                                                <?php echo e($errors->first('paytab_server_key')); ?>

                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paytab_region"
                                                                    class="form-label"><?php echo e(__('Region')); ?></label>
                                                                <input type="text" name="paytab_region"
                                                                    id="paytab_region"
                                                                    class="form-control form-control-label"
                                                                    value="<?php echo e(isset($company_payment_setting['paytab_region']) ? $company_payment_setting['paytab_region'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Region')); ?>" /><br>
                                                                <?php if($errors->has('paytab_region')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytab_region')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwentyOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne"
                                                    aria-expanded="false" aria-controls="collapseTwentyOne">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Benefit')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_benefit_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_benefit_enabled" id="is_benefit_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_benefit_enabled']) && $company_payment_setting['is_benefit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label"
                                                                for="is_benefit_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseTwentyOne" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwentyOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('benefit_api_key', __('Benefit Key'), ['class' => 'col-form-label'])); ?>

                                                                <?php echo e(Form::text('benefit_api_key', isset($company_payment_setting['benefit_api_key']) ? $company_payment_setting['benefit_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Benefit Key')])); ?>

                                                                <?php $__errorArgs = ['benefit_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-benefit_api_key" role="alert">
                                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('benefit_secret_key', __('Benefit Secret Key'), ['class' => 'col-form-label'])); ?>

                                                                <?php echo e(Form::text('benefit_secret_key', isset($company_payment_setting['benefit_secret_key']) ? $company_payment_setting['benefit_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Benefit Secret key')])); ?>

                                                                <?php $__errorArgs = ['benefit_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-benefit_secret_key" role="alert">
                                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwentyTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo"
                                                    aria-expanded="false" aria-controls="collapseTwentyTwo">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Cashfree')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_cashfree_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_cashfree_enabled" id="is_cashfree_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_cashfree_enabled']) && $company_payment_setting['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label"
                                                                for="is_cashfree_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseTwentyTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwentyTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('cashfree_api_key', __('Cashfree Key'), ['class' => 'col-form-label'])); ?>

                                                                <?php echo e(Form::text('cashfree_api_key', isset($company_payment_setting['cashfree_api_key']) ? $company_payment_setting['cashfree_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')])); ?>

                                                                <?php $__errorArgs = ['cashfree_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-cashfree_api_key" role="alert">
                                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('cashfree_secret_key', __('Cashfree Secret Key'), ['class' => 'col-form-label'])); ?>

                                                                <?php echo e(Form::text('cashfree_secret_key', isset($company_payment_setting['cashfree_secret_key']) ? $company_payment_setting['cashfree_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')])); ?>

                                                                <?php $__errorArgs = ['cashfree_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-cashfree_secret_key" role="alert">
                                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                                </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item card shadow-none">
                                            <h2 class="accordion-header" id="heading-2-20">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse20"
                                                    aria-expanded="true" aria-controls="collapse20">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Aamarpay')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_aamarpay_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_aamarpay_enabled']) && $company_payment_setting['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label for="customswitch1-2"
                                                                class="form-check-label"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse20" class="accordion-collapse collapse"
                                                aria-labelledby="heading-2-20" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="aamarpay_store_id"
                                                                    class="form-label"><?php echo e(__(' Store Id')); ?></label>
                                                                <input type="text" name="aamarpay_store_id"
                                                                    id="aamarpay_store_id" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['aamarpay_store_id']) || is_null($company_payment_setting['aamarpay_store_id']) ? '' : $company_payment_setting['aamarpay_store_id']); ?>"
                                                                    placeholder="<?php echo e(__('Enter Store Id')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="aamarpay_signature_key"
                                                                    class="form-label"><?php echo e(__('Signature Key')); ?></label>
                                                                <input type="text" name="aamarpay_signature_key"
                                                                    id="aamarpay_signature_key" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['aamarpay_signature_key']) || is_null($company_payment_setting['aamarpay_signature_key']) ? '' : $company_payment_setting['aamarpay_signature_key']); ?>"
                                                                    placeholder="<?php echo e(__('Enter Signature Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="aamarpay_description"
                                                                    class="form-label"><?php echo e(__('Description')); ?></label>
                                                                <input type="text" name="aamarpay_description"
                                                                    id="aamarpay_description" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['aamarpay_description']) || is_null($company_payment_setting['aamarpay_description']) ? '' : $company_payment_setting['aamarpay_description']); ?>"
                                                                    placeholder="<?php echo e(__('Enter Signature Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwentyfour">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwentyfive"
                                                    aria-expanded="true" aria-controls="collapseTwentyfive">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('PayTr')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paytr_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_paytr_enabled" id="is_paytr_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_paytr_enabled']) && $company_payment_setting['is_paytr_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label for="customswitch1-2"
                                                                class="form-check-label"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseTwentyfive" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwentyfour" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row pt-2">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('paytr_merchant_id', __('Merchant Id'), ['class' => 'form-label'])); ?>

                                                                <?php echo e(Form::text('paytr_merchant_id', isset($company_payment_setting['paytr_merchant_id']) ? $company_payment_setting['paytr_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Id')])); ?><br>
                                                                <?php if($errors->has('paytr_merchant_id')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytr_merchant_id')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('paytr_merchant_key', __('Merchant Key'), ['class' => 'form-label'])); ?>

                                                                <?php echo e(Form::text('paytr_merchant_key', isset($company_payment_setting['paytr_merchant_key']) ? $company_payment_setting['paytr_merchant_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')])); ?><br>
                                                                <?php if($errors->has('paytr_merchant_key')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytr_merchant_key')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('paytr_merchant_salt', __('Merchant Salt'), ['class' => 'form-label'])); ?>

                                                                <?php echo e(Form::text('paytr_merchant_salt', isset($company_payment_setting['paytr_merchant_salt']) ? $company_payment_setting['paytr_merchant_salt'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Salt')])); ?><br>
                                                                <?php if($errors->has('paytr_merchant_salt')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytr_merchant_salt')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-yookassa">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse19" aria-expanded="true"
                                                    aria-controls="collapse19">
                                                    <span class="d-flex align-items-center"><?php echo e(__('Yookassa')); ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_yookassa_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_yookassa_enabled" id="is_yookassa_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_yookassa_enabled']) && $company_payment_setting['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label"
                                                                for="is_yookassa_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse19" class="accordion-collapse collapse"
                                                aria-labelledby="heading-yookassa" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="yookassa_shop_id"
                                                                    class="col-form-label"><?php echo e(__('Shop ID Key')); ?></label>
                                                                <input type="text" name="yookassa_shop_id"
                                                                    id="yookassa_shop_id" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['yookassa_shop_id']) || is_null($company_payment_setting['yookassa_shop_id']) ? '' : $company_payment_setting['yookassa_shop_id']); ?>"
                                                                    placeholder="<?php echo e(__('Shop ID Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="yookassa_secret_key"
                                                                    class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="yookassa_secret_key"
                                                                    id="yookassa_secret_key" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['yookassa_secret_key']) || is_null($company_payment_setting['yookassa_secret_key']) ? '' : $company_payment_setting['yookassa_secret_key']); ?>"
                                                                    placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-midtrans">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse16" aria-expanded="true"
                                                    aria-controls="collapse16">
                                                    <span class="d-flex align-items-center"><?php echo e(__('Midtrans')); ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_midtrans_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_midtrans_enabled" id="is_midtrans_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_midtrans_enabled']) && $company_payment_setting['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label"
                                                                for="is_midtrans_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse16" class="accordion-collapse collapse"
                                                aria-labelledby="heading-midtrans" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                            <div class="row pt-2">
                                                                <label class="pb-2"
                                                                    for="midtrans_mode"><?php echo e(__('Midtrans Mode')); ?></label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2"
                                                                        style="margin-right: 15px; width: 190px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="midtrans_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        <?php echo e(!isset($company_payment_setting['midtrans_mode']) || $company_payment_setting['midtrans_mode'] == '' || $company_payment_setting['midtrans_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                                    <?php echo e(__('Sandbox')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2" style="width: 190px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="midtrans_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        <?php echo e(isset($company_payment_setting['midtrans_mode']) && $company_payment_setting['midtrans_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                    <?php echo e(__('Live')); ?>

                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="midtrans_secret"
                                                                    class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="midtrans_secret"
                                                                    id="midtrans_secret" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['midtrans_secret']) || is_null($company_payment_setting['midtrans_secret']) ? '' : $company_payment_setting['midtrans_secret']); ?>"
                                                                    placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-xendit">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse17" aria-expanded="true"
                                                    aria-controls="collapse17">
                                                    <span class="d-flex align-items-center"><?php echo e(__('Xendit')); ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_xendit_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                name="is_xendit_enabled" id="is_xendit_enabled"
                                                                <?php echo e(isset($company_payment_setting['is_xendit_enabled']) && $company_payment_setting['is_xendit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="form-check-label"
                                                                for="is_xendit_enabled"></label>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse17" class="accordion-collapse collapse"
                                                aria-labelledby="heading-xendit" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="xendit_api_key"
                                                                    class="col-form-label"><?php echo e(__('API Key')); ?></label>
                                                                <input type="text" name="xendit_api_key"
                                                                    id="xendit_api_key" class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['xendit_api_key']) || is_null($company_payment_setting['xendit_api_key']) ? '' : $company_payment_setting['xendit_api_key']); ?>"
                                                                    placeholder="<?php echo e(__('API Key')); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="xendit_token"
                                                                    class="col-form-label"><?php echo e(__('Token')); ?></label>
                                                                <input type="text" name="xendit_token" id="xendit_token"
                                                                    class="form-control"
                                                                    value="<?php echo e(!isset($company_payment_setting['xendit_token']) || is_null($company_payment_setting['xendit_token']) ? '' : $company_payment_setting['xendit_token']); ?>"
                                                                    placeholder="<?php echo e(__('Token')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="form-group">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                    </form>
                </div>

                <!--Time Tracker Setting-->
                <div id="time-tracker-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Time Tracker Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit Time Tracker settings.')); ?></small>
                    </div>

                    <?php echo e(Form::open(['url' => route('setting.timeTracker'), 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label mb-0"><?php echo e(__('Application URL')); ?></label> <br>
                                <small><?php echo e(__('Application URL to log into the app')); ?></small>
                                <?php echo e(Form::text('apps_url', URL::to('/'), ['class' => 'form-control', 'placeholder' => __('Application URL'), 'readonly' => 'true'])); ?>

                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label mb-0"><?php echo e(__('Tracking Interval')); ?></label> <br>
                                <small><?php echo e(__('Image Screenshot Take Interval time ( 1 = 1 min)')); ?></small>
                                <?php echo e(Form::number('interval_time', isset($settings['interval_time']) ? $settings['interval_time'] : '10', ['class' => 'form-control', 'placeholder' => __('Enter Tracking Interval'), 'required' => 'required'])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Zoom Setting-->
                <div id="zoom-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Zoom Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your Zoom settings.')); ?></small>
                    </div>

                    <?php echo e(Form::open(['url' => route('setting.ZoomSettings'), 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Zoom Account ID')); ?></label>
                                    <input type="text" name="zoom_account_id" class="form-control"
                                        placeholder="Enter Zoom Account ID"
                                        value="<?php echo e(!empty($settings['zoom_account_id']) ? $settings['zoom_account_id'] : ''); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Zoom Client ID')); ?></label>
                                    <input type="text" name="zoom_client_id" class="form-control"
                                        placeholder="Enter Zoom Client ID"
                                        value="<?php echo e(!empty($settings['zoom_client_id']) ? $settings['zoom_client_id'] : ''); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Zoom Client Secret Key')); ?></label>
                                    <input type="text" name="zoom_client_secret" class="form-control"
                                        placeholder="Enter Zoom Client Secret Key"
                                        value="<?php echo e(!empty($settings['zoom_client_secret']) ? $settings['zoom_client_secret'] : ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Slack Setting-->
                <div id="slack-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Slack Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your Slack settings')); ?></small>
                    </div>

                    <?php echo e(Form::open(['route' => 'slack.setting', 'id' => 'slack-setting', 'method' => 'post', 'class' => 'd-contents'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo e(Form::label('slack', __('Slack Webhook URL'), ['class' => 'form-label'])); ?>


                                <div class="col-md-8">
                                    <?php echo e(Form::text('slack_webhook', isset($settings['slack_webhook']) ? $settings['slack_webhook'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Slack Webhook URL'), 'required' => 'required'])); ?>

                                </div>
                            </div>

                            <div class="col-md-12 mt-4 mb-2">
                                <h5 class="small-title"><?php echo e(__('Module Settings')); ?></h5>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Holiday')); ?></span>
                                            <?php echo e(Form::checkbox('holiday_create_notification', '1', isset($settings['holiday_create_notification']) && $settings['holiday_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'holiday_create_notification'])); ?>

                                            <label class="form-check-label" for="holiday_create_notification"></label>
                                        </div>

                                    </li>
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Meeting')); ?></span>
                                            <?php echo e(Form::checkbox('meeting_create_notification', '1', isset($settings['meeting_create_notification']) && $settings['meeting_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'meeting_create_notification'])); ?>

                                            <label class="form-check-label" for="meeting_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Company Policy')); ?></span>
                                            <?php echo e(Form::checkbox('company_policy_create_notification', '1', isset($settings['company_policy_create_notification']) && $settings['company_policy_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'company_policy_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="company_policy_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Award')); ?></span>
                                            <?php echo e(Form::checkbox('award_create_notification', '1', isset($settings['award_create_notification']) && $settings['award_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'award_create_notification'])); ?>

                                            <label class="form-check-label" for="award_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Lead')); ?></span>
                                            <?php echo e(Form::checkbox('lead_create_notification', '1', isset($settings['lead_create_notification']) && $settings['lead_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'lead_create_notification'])); ?>

                                            <label class="form-check-label" for="lead_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Deal')); ?></span>
                                            <?php echo e(Form::checkbox('deal_create_notification', '1', isset($settings['deal_create_notification']) && $settings['deal_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'deal_create_notification'])); ?>

                                            <label class="form-check-label" for="deal_create_notification"></label>
                                        </div>
                                    </li>


                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('Lead to Deal Conversion')); ?></span>
                                            <?php echo e(Form::checkbox('convert_lead_to_deal_notification', '1', isset($settings['convert_lead_to_deal_notification']) && $settings['convert_lead_to_deal_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'convert_lead_to_deal_notification'])); ?>

                                            <label class="form-check-label"
                                                for="convert_lead_to_deal_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Estimation')); ?></span>
                                            <?php echo e(Form::checkbox('estimation_create_notification', '1', isset($settings['estimation_create_notification']) && $settings['estimation_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'estimation_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="estimation_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Project')); ?></span>
                                            <?php echo e(Form::checkbox('project_create_notification', '1', isset($settings['project_create_notification']) && $settings['project_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'project_create_notification'])); ?>

                                            <label class="form-check-label" for="project_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Project Status')); ?></span>
                                            <?php echo e(Form::checkbox('project_status_updated_notification', '1', isset($settings['project_status_updated_notification']) && $settings['project_status_updated_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'project_status_updated_notification'])); ?>

                                            <label class="form-check-label"
                                                for="project_status_updated_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Task')); ?></span>
                                            <?php echo e(Form::checkbox('task_create_notification', '1', isset($settings['task_create_notification']) && $settings['task_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'task_create_notification'])); ?>

                                            <label class="form-check-label" for="task_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('Task Moved')); ?></span>
                                            <?php echo e(Form::checkbox('task_move_notification', '1', isset($settings['task_move_notification']) && $settings['task_move_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'task_move_notification'])); ?>

                                            <label class="form-check-label" for="task_move_notification"></label>
                                        </div>
                                    </li>


                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Task Comment')); ?></span>
                                            <?php echo e(Form::checkbox('task_comment_notification', '1', isset($settings['task_comment_notification']) && $settings['task_comment_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'task_comment_notification'])); ?>

                                            <label class="form-check-label" for="task_comment_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Milestone')); ?></span>
                                            <?php echo e(Form::checkbox('milestone_create_notification', '1', isset($settings['milestone_create_notification']) && $settings['milestone_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'milestone_create_notification'])); ?>

                                            <label class="form-check-label" for="milestone_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Invoice')); ?></span>
                                            <?php echo e(Form::checkbox('invoice_create_notification', '1', isset($settings['invoice_create_notification']) && $settings['invoice_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'invoice_create_notification'])); ?>

                                            <label class="form-check-label" for="invoice_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('Invoice Status Updated')); ?></span>
                                            <?php echo e(Form::checkbox('invoice_status_updated_notification', '1', isset($settings['invoice_status_updated_notification']) && $settings['invoice_status_updated_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'invoice_status_updated_notification'])); ?>

                                            <label class="form-check-label"
                                                for="invoice_status_updated_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Payment')); ?></span>
                                            <?php echo e(Form::checkbox('payment_create_notification', '1', isset($settings['payment_create_notification']) && $settings['payment_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'payment_create_notification'])); ?>

                                            <label class="form-check-label" for="payment_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Contract')); ?></span>
                                            <?php echo e(Form::checkbox('contract_create_notification', '1', isset($settings['contract_create_notification']) && $settings['contract_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'contract_create_notification'])); ?>

                                            <label class="form-check-label" for="contract_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Support Ticket')); ?></span>
                                            <?php echo e(Form::checkbox('support_create_notification', '1', isset($settings['support_create_notification']) && $settings['support_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'support_create_notification'])); ?>

                                            <label class="form-check-label" for="support_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Event')); ?></span>
                                            <?php echo e(Form::checkbox('event_create_notification', '1', isset($settings['contract_create_notification']) && $settings['event_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'event_create_notification'])); ?>

                                            <label class="form-check-label" for="event_create_notification"></label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>

                    <?php echo e(Form::close()); ?>


                </div>

                <!--Telegram Setting-->
                <div id="telegram-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Telegram Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your Telegram settings')); ?></small>
                    </div>

                    <?php echo e(Form::open(['route' => 'telegram.setting', 'id' => 'telegram-setting', 'method' => 'post', 'class' => 'd-contents'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('telegrambot', __('Telegram Access Token'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('telegrambot', isset($settings['telegrambot']) ? $settings['telegrambot'] : '', ['class' => 'form-control active telegrambot', 'placeholder' => 'Enter Telegram Access Token'])); ?>

                                    <p><?php echo e(__('Get Chat ID')); ?> : https://api.telegram.org/bot-TOKEN-/getUpdates
                                    </p>
                                    <?php if($errors->has('telegrambot')): ?>
                                    <span class="invalid-feedback d-block">
                                        <?php echo e($errors->first('telegrambot')); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('telegramchatid', __('Telegram Chat Id'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('telegramchatid', isset($settings['telegramchatid']) ? $settings['telegramchatid'] : '', ['class' => 'form-control active telegramchatid', 'placeholder' => 'Enter Telegram Chat Id'])); ?>

                                    <?php if($errors->has('telegramchatid')): ?>
                                    <span class="invalid-feedback d-block">
                                        <?php echo e($errors->first('telegramchatid')); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 mb-2">
                                <h4 class="small-title"><?php echo e(__('Module Settings')); ?></h4>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Holiday')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_holiday_create_notification', '1', isset($settings['telegram_holiday_create_notification']) && $settings['telegram_holiday_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_holiday_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_holiday_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Meeting')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_meeting_create_notification', '1', isset($settings['telegram_meeting_create_notification']) && $settings['telegram_meeting_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_meeting_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_meeting_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Company Policy')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_company_policy_create_notification', '1', isset($settings['telegram_company_policy_create_notification']) && $settings['telegram_company_policy_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_company_policy_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_company_policy_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Award')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_award_create_notification', '1', isset($settings['telegram_award_create_notification']) && $settings['telegram_award_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_award_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_award_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Lead')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_lead_create_notification', '1', isset($settings['telegram_lead_create_notification']) && $settings['telegram_lead_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_lead_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_lead_create_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span><?php echo e(__('New Deal')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_deal_create_notification', '1', isset($settings['telegram_deal_create_notification']) && $settings['telegram_deal_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_deal_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_deal_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('Lead to Deal Conversion')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_convert_lead_to_deal_notification', '1', isset($settings['telegram_convert_lead_to_deal_notification']) && $settings['telegram_convert_lead_to_deal_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_convert_lead_to_deal_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_convert_lead_to_deal_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Estimation')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_estimation_create_notification', '1', isset($settings['telegram_estimation_create_notification']) && $settings['telegram_estimation_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_estimation_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_estimation_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Project')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_project_create_notification', '1', isset($settings['telegram_project_create_notification']) && $settings['telegram_project_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_project_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_project_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Project Status')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_project_status_updated_notification', '1', isset($settings['telegram_project_status_updated_notification']) && $settings['telegram_project_status_updated_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_project_status_updated_notification'])); ?>

                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Task')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_task_create_notification', '1', isset($settings['telegram_task_create_notification']) && $settings['telegram_task_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_task_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_task_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('Task Moved')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_task_move_notification', '1', isset($settings['telegram_task_move_notification']) && $settings['telegram_task_move_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_task_move_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_task_move_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Task Comment')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_task_comment_notification', '1', isset($settings['telegram_task_comment_notification']) && $settings['telegram_task_comment_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_task_comment_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_task_comment_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Milestone')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_milestone_create_notification', '1', isset($settings['telegram_milestone_create_notification']) && $settings['telegram_milestone_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_milestone_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_milestone_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Invoice')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_invoice_create_notification', '1', isset($settings['telegram_invoice_create_notification']) && $settings['telegram_invoice_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_invoice_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_invoice_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('Invoice Status Updated')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_invoice_status_updated_notification', '1', isset($settings['telegram_invoice_status_updated_notification']) && $settings['telegram_invoice_status_updated_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_invoice_status_updated_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_invoice_status_updated_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Payment')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_payment_create_notification', '1', isset($settings['telegram_payment_create_notification']) && $settings['telegram_payment_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_payment_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_payment_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Contract')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_contract_create_notification', '1', isset($settings['telegram_contract_create_notification']) && $settings['telegram_contract_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_contract_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_contract_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Support Ticket')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_support_create_notification', '1', isset($settings['telegram_support_create_notification']) && $settings['telegram_support_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_support_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_support_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Event')); ?></span>
                                            <?php echo e(Form::checkbox('telegram_event_create_notification', '1', isset($settings['telegram_event_create_notification']) && $settings['telegram_event_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_event_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="telegram_event_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>


                </div>

                <!--Twillio Setting-->
                <div id="twillio-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Twillio Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your Twilio settings')); ?></small>
                    </div>
                    <?php echo e(Form::open(['route' => 'twilio.setting', 'id' => 'twilio-setting', 'method' => 'post', 'class' => 'd-contents'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('twilio_sid', __('Twilio SID '), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('twilio_sid', isset($settings['twilio_sid']) ? $settings['twilio_sid'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio SID'), 'required' => 'required'])); ?>

                                    <?php $__errorArgs = ['twilio_sid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-twilio_sid" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('twilio_token', __('Twilio Token'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('twilio_token', isset($settings['twilio_token']) ? $settings['twilio_token'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio Token'), 'required' => 'required'])); ?>

                                    <?php $__errorArgs = ['twilio_token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-twilio_token" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('twilio_from', __('Twilio From'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('twilio_from', isset($settings['twilio_from']) ? $settings['twilio_from'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio From'), 'required' => 'required'])); ?>

                                    <?php $__errorArgs = ['twilio_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-twilio_from" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 mb-2">
                                <h4 class="small-title"><?php echo e(__('Module Settings')); ?></h4>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('Leave Approved/Rejected')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_leave_approve_reject_notification', '1', isset($settings['twilio_leave_approve_reject_notification']) && $settings['twilio_leave_approve_reject_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_leave_approve_reject_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_leave_approve_reject_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Award')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_award_create_notification', '1', isset($settings['twilio_award_create_notification']) && $settings['twilio_award_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_award_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_award_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Trip')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_trip_create_notification', '1', isset($settings['twilio_trip_create_notification']) && $settings['twilio_trip_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_trip_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_trip_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Ticket')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_ticket_create_notification', '1', isset($settings['twilio_ticket_create_notification']) && $settings['twilio_ticket_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_ticket_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_ticket_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Event')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_event_create_notification', '1', isset($settings['twilio_event_create_notification']) && $settings['twilio_event_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_event_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_event_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Project')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_project_create_notification', '1', isset($settings['twilio_project_create_notification']) && $settings['twilio_project_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_project_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_project_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Task')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_task_create_notification', '1', isset($settings['twilio_task_create_notification']) && $settings['twilio_task_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_task_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_task_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Contract')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_contract_create_notification', '1', isset($settings['twilio_contract_create_notification']) && $settings['twilio_contract_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_contract_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_contract_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Invoice')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_invoice_create_notification', '1', isset($settings['twilio_invoice_create_notification']) && $settings['twilio_invoice_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_invoice_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_invoice_create_notification"></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Invoice Payment')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_invoice_payment_create_notification', '1', isset($settings['twilio_invoice_payment_create_notification']) && $settings['twilio_invoice_payment_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_invoice_payment_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_invoice_payment_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-switch form-switch-right">
                                            <span><?php echo e(__('New Payment')); ?></span>
                                            <?php echo e(Form::checkbox('twilio_payment_create_notification', '1', isset($settings['twilio_payment_create_notification']) && $settings['twilio_payment_create_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_payment_create_notification'])); ?>

                                            <label class="form-check-label"
                                                for="twilio_payment_create_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Email Notification Setting-->
                <div id="email-notification-settings" class="card">
                    <div class="col-md-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5><?php echo e(__('Email Notification Settings')); ?></h5>
                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::model($settings, ['route' => ['status.email.language'], 'method' => 'post'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="row">
                                <!-- <div class=""> -->
                                <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                    <div class="list-group">
                                        <div class="list-group-item form-switch form-switch-right">
                                            <label class="form-label"
                                                style="margin-left:5%;"><?php echo e($EmailTemplate->name); ?></label>
                                            <input class="form-check-input" name='<?php echo e($EmailTemplate->id); ?>'
                                                id="email_tempalte_<?php echo e($EmailTemplate->template->id); ?>" type="checkbox"
                                                <?php if($EmailTemplate->template->is_active == 1): ?> checked="checked" <?php endif; ?>
                                            type="checkbox" value="1"
                                            data-url="<?php echo e(route('status.email.language', [$EmailTemplate->template->id])); ?>"
                                            />
                                            <label class="form-check-label"
                                                for="email_tempalte_<?php echo e($EmailTemplate->template->id); ?>"></label>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="card-footer p-0">
                                <div class="col-sm-12 mt-3 px-2">
                                    <div class="text-end">
                                        <input class="btn btn-print-invoice  btn-primary " type="submit"
                                            value="<?php echo e(__('Save Changes')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
                <!-- </form>  -->

                <!--Google Calendar Setting-->
                <div id="google-calendar-settings" class="card">
                    <?php echo e(Form::open(['url' => route('google.calender.settings'), 'enctype' => 'multipart/form-data', 'onsubmit' => 'return validateForm(e)'])); ?>

                    <div class="card-header d-flex justify-content-between">
                        <h5><?php echo e(__('Google Calendar Settings')); ?></h5>
                        <div class="col-6 py-2 text-end">
                            <div class="custom-control custom-switch">
                                
                                <input type="checkbox" data-toggle="switchbutton" class="form-check-input"
                                    name="is_googleCal_enabled" id="is_googleCal_enabled"
                                    <?php echo e(isset($settings['is_googleCal_enabled']) && $settings['is_googleCal_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <?php echo e(Form::label('Google calendar id', __('Google Calendar Id'), ['class' => 'col-form-label'])); ?>

                                <?php echo e(Form::text('google_clender_id', !empty($settings['google_clender_id']) ? $settings['google_clender_id'] : '', ['class' => 'form-control ', 'placeholder' => 'Google Calendar Id', 'id' => 'google_clender_id'])); ?>

                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <?php echo e(Form::label('Google calendar json file', __('Google Calendar json File'), ['class' => 'col-form-label'])); ?>

                                <input type="file" class="form-control" name="google_calender_json_file" id="file">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn-submit btn btn-primary" type="submit">
                            <?php echo e(__('Save Changes')); ?>

                        </button>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Webhook Settings-->
                <div class="card" id="webhook-settings">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class=""><?php echo e(__('Webhook Settings')); ?></h5>
                            <small class="text-dark font-weight-bold">
                                <?php echo e(__('Edit your Webhook Settings')); ?>

                            </small>
                        </div>
                        <a href="#" data-url="<?php echo e(route('webhook.create')); ?>" data-bs-toggle="modal"
                            data-bs-target="#exampleModal"
                            class="btn btn-sm btn-primary btn-icon wid-30 hei-30 d-inline-flex align-items-center justify-content-center m-1"
                            data-bs-whatever="<?php echo e(__('Create Webhook')); ?>">
                            <i class="ti ti-plus text-white" data-bs-toggle="tooltip"
                                data-bs-original-title="<?php echo e(__('Create')); ?>"></i>
                        </a>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        
                                        <th scope="sort"><?php echo e(__('Module')); ?></th>
                                        <th scope="sort"><?php echo e(__('Url')); ?></th>
                                        <th scope="sort"><?php echo e(__('Method')); ?></th>
                                        <th scope="sort"><?php echo e(__('Action')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($data) && count($data) > 0): ?>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($wh->module); ?></td>
                                        <td><?php echo e($wh->url); ?></td>
                                        <td><?php echo e($wh->method); ?></td>
                                        <td class="Action">

                                            <div class="action-btn  ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-info d-flex btn-sm d-inline-flex align-items-center wid-30 hei-30 rounded"
                                                    data-size="md" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                    data-url="<?php echo e(route('webhook.edit', $wh->id)); ?>"
                                                    data-bs-whatever="<?php echo e(__('Edit Webhook Settings')); ?>">
                                                    <i class="ti ti-edit" data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Edit Webhook Settings')); ?>"></i>
                                                </a>
                                            </div>

                                            <div class="action-btn  ms-2">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['webhook.destroy',
                                                $wh->id]]); ?>

                                                <a href="#!"
                                                    class="mx-3 btn bg-danger btn-sm d-flex wid-30 hei-30 rounded align-items-center show_confirm">
                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Delete')); ?>"></i>
                                                </a>
                                                <?php echo Form::close(); ?>

                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="text-center">
                                                <i class="fas fa-user-slash text-primary fs-40"></i>
                                                <h2><?php echo e(__('Opps...')); ?></h2>
                                                <h6> <?php echo __('No Data Available...!'); ?> </h6>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php endif; ?>

                
                <?php if(\Auth::user()->type == 'super admin'): ?>
                <!--Brand Setting-->
                <div id="brand-settings" class="card">
                    <?php echo e(Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-header">
                        <h5><?php echo e(__('Brand Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your brand details')); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="small-title"><?php echo e(__('Dark Logo')); ?></h5>
                                    </div>
                                    <div class="card-body setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="logo-content  text-center py-2">

                                                    <a href="<?php echo e($logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?timestamp=' . time()); ?>"
                                                        target="_blank">
                                                        <img id="blah" alt="your image"
                                                            src="<?php echo e($logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') . '?timestamp=' . time()); ?>"
                                                            width="150px" class="big-logo">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="choose-files mt-5">
                                                    <label for="logo">
                                                        <div class=" bg-primary logo m-auto"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input type="file" class="form-control file" name="logo"
                                                            id="logo" data-filename="edit-logo" accept=".jpeg,.jpg,.png"
                                                            accept=".jpeg,.jpg,.png">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="small-title"><?php echo e(__('Light Logo')); ?></h5>
                                    </div>
                                    <div class="card-body setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="logo-content text-center py-2">

                                                    <a href="<?php echo e($logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?timestamp=' . time()); ?>"
                                                        target="_blank">
                                                        <img id="blah1" alt="your image"
                                                            src="<?php echo e($logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?timestamp=' . time()); ?>"
                                                            width="150px" class="big-logo img_setting"
                                                            style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="choose-files mt-5">
                                                    <label for="white_logo">
                                                        <div class=" bg-primary white_logo m-auto"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input type="file" class="form-control file" name="white_logo"
                                                            id="white_logo" data-filename="edit-white_logo"
                                                            accept=".jpeg,.jpg,.png" accept=".jpeg,.jpg,.png">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="small-title"><?php echo e(__('Favicon')); ?></h5>
                                    </div>
                                    <div class="card-body setting-card setting-logo-box p-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="logo-content  text-center py-2">
                                                    <a href="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp=' . time()); ?>"
                                                        target="_blank">
                                                        <img id="blah2" alt="your image"
                                                            src="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp=' . time()); ?>"
                                                            width="80px" class="big-logo img_setting">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="choose-files mt-5">
                                                    <label for="favicon">
                                                        <div class="bg-primary favicon m-auto"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input type="file" class="form-control file" name="favicon"
                                                            id="favicon" data-filename="edit-favicon"
                                                            accept=".jpeg,.jpg,.png" accept=".jpeg,.jpg,.png">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('title_text', __('Title Text'), ['class' => 'col-form-label text-dark'])); ?>

                                    <?php echo e(Form::text('title_text', Utility::getValByName('title_text'), ['class' => 'form-control', 'placeholder' => __('Enter Header Title Text')])); ?>

                                    <?php $__errorArgs = ['title_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-title_text" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('footer_text', __('Footer Text'), ['class' => 'col-form-label text-dark'])); ?>

                                    <?php echo e(Form::text('footer_text', Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')])); ?>

                                    <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-footer_text" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('default_language', __('Default Language'), ['class' => 'col-form-label text-dark'])); ?>

                                    <select name="default_language" id="default_language" class="form-control select2">
                                        <?php $__currentLoopData = Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if(Utility::getValByName('default_language')==$code): ?> selected <?php endif; ?>
                                            value="<?php echo e($code); ?>"><?php echo e(Str::upper($language)); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['default_language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-default_language" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col switch-width col-lg-3">
                                        <div class="form-group ml-2 mr-3 ">
                                            <label class="form-label text-dark"><?php echo e(__('Enable RTL')); ?></label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                    class="" name="SITE_RTL" id="SITE_RTL"
                                                    <?php echo e($settings['SITE_RTL'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                <label class="custom-control-label" for="SITE_RTL"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col switch-width">
                                        <div class="form-group mr-3">
                                            <label class="form-label text-dark "
                                                for="display_landing_page"><?php echo e(__('Enable Landing Page')); ?></label>
                                            <div class="custom-control custom-switch ">
                                                <input type="checkbox" name="display_landing_page"
                                                    class="form-check-input" id="display_landing_page"
                                                    data-toggle="switchbutton"
                                                    <?php echo e($settings['display_landing_page'] == 'on' ? 'checked="checked"' : ''); ?>

                                                    data-onstyle="primary">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col switch-width">
                                        <div class="form-group mr-3">
                                            <label class="form-label text-dark text-dark"
                                                for="SIGNUP"><?php echo e(__('Sign Up')); ?></label>
                                            <div class="">
                                                <input type="checkbox" name="SIGNUP" id="SIGNUP"
                                                    data-toggle="switchbutton"
                                                    <?php echo e($settings['SIGNUP'] == 'on' ? 'checked="checked"' : ''); ?>

                                                    data-onstyle="primary">
                                                <label class="form-check-labe" for="SIGNUP"></label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col switch-width">
                                        <div class="form-group mr-3">
                                            <label class="form-label text-dark text-dark"
                                                for="Email Verificattion"><?php echo e(__('Email Verificattion')); ?></label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="email_verificattion"
                                                    id="email_verificattion" data-toggle="switchbutton"
                                                    <?php echo e($settings['email_verificattion'] == 'on' ? 'checked="checked"' : ''); ?>

                                                    data-onstyle="primary">
                                                <label class="form-check-labe" for="email_verificattion"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <h4 class="small-title"><?php echo e(__('Theme Customizer')); ?></h4>
                            <div class="setting-card setting-logo-box p-3">
                                <div class="row">
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="credit-card" class="me-2"></i><?php echo e(__('Primary color settings')); ?>

                                        </h6>

                                        <hr class="my-2" />
                                        <div class="color-wrp">
                                            <div class="theme-color themes-color">
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-1' ? 'active_color' : ''); ?>" data-value="theme-1"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-1"<?php echo e($color == 'theme-1' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-2' ? 'active_color' : ''); ?>" data-value="theme-2"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-2"<?php echo e($color == 'theme-2' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-3' ? 'active_color' : ''); ?>" data-value="theme-3"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-3"<?php echo e($color == 'theme-3' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-4' ? 'active_color' : ''); ?>" data-value="theme-4"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-4"<?php echo e($color == 'theme-4' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-5' ? 'active_color' : ''); ?>" data-value="theme-5"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-5"<?php echo e($color == 'theme-5' ? 'checked' : ''); ?>>
                                                <br>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-6' ? 'active_color' : ''); ?>" data-value="theme-6"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-6"<?php echo e($color == 'theme-6' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-7' ? 'active_color' : ''); ?>" data-value="theme-7"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-7"<?php echo e($color == 'theme-7' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-8' ? 'active_color' : ''); ?>" data-value="theme-8"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-8"<?php echo e($color == 'theme-8' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-9' ? 'active_color' : ''); ?>" data-value="theme-9"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-9"<?php echo e($color == 'theme-9' ? 'checked' : ''); ?>>
                                                <a href="#!" class="themes-color-change <?php echo e($color == 'theme-10' ? 'active_color' : ''); ?>" data-value="theme-10"></a>
                                                <input type="radio" class="theme_color d-none" name="color" value="theme-10"<?php echo e($color == 'theme-10' ? 'checked' : ''); ?>>
                                            </div>
                                            <div class="color-picker-wrp ">
                                                    <input type="color" value="<?php echo e($color ? $color : ''); ?>" class="colorPicker <?php echo e(isset($flag) && $flag == 'true' ? 'active_color' : ''); ?>" name="custom_color" id="color-picker">                                             
                                                    <input type='hidden' name="color_flag" value = <?php echo e(isset($flag) && $flag == 'true' ? 'true' : 'false'); ?>>
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="layout" class="me-2"></i><?php echo e(__('Sidebar settings')); ?>

                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="cust_theme_bg" <?php echo e(!empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : ''); ?>/>
                                            <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg"
                                            ><?php echo e(__('Transparent layout')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xl-4 col-md-4">
                                        <h6 class="mt-2">
                                            <i data-feather="sun" class="me-2"></i><?php echo e(__('Layout settings')); ?>

                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout"<?php echo e(!empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : ''); ?> />
                                            <label class="form-check-label f-w-600 pl-1" for="cust-darklayout"><?php echo e(__('Dark Layout')); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Mail Setting-->
                <div id="email-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Email Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit your email details')); ?></small>
                    </div>
                    <?php echo e(Form::open(['route' => 'email.setting', 'method' => 'post'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_driver', $setting['mail_driver'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')])); ?>

                                <?php $__errorArgs = ['mail_driver'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_driver" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_host', __('Mail Host'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_host', $setting['mail_host'], ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')])); ?>

                                <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_driver" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_port', __('Mail Port'))); ?>

                                <?php echo e(Form::text('mail_port', $setting['mail_port'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')])); ?>

                                <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_port" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_username', __('Mail Username'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_username', $setting['mail_username'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')])); ?>

                                <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_username" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_password', __('Mail Password'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_password', $setting['mail_password'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')])); ?>

                                <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_password" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_encryption', $setting['mail_encryption'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')])); ?>

                                <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_encryption" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group  col-md-6">
                                <?php echo e(Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_from_address', $setting['mail_from_address'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')])); ?>

                                <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_from_address" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('mail_from_name', $setting['mail_from_name'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')])); ?>

                                <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_from_name" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div
                            class="d-flex justify-content-between justify-content-xs-center align-items-center flex-wrap">
                            <div class="form-group">
                                <a href="#" data-url="<?php echo e(route('test.mail')); ?>" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-bs-whatever="<?php echo e(__('Send Test Mail')); ?>"
                                    class="btn btn-print-invoice btn-primary send_email">
                                    <?php echo e(__('Send Test Mail')); ?>

                                </a>
                            </div>

                            <div class="form-group text-end">
                                <input class="btn btn-print-invoice  btn-primary " type="submit"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Pusher Setting-->
                <div id="pusher-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Pusher Settings')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Edit Pusher settings')); ?></small>
                    </div>
                    <?php echo e(Form::model($setting, ['route' => 'pusher.setting', 'method' => 'post'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('pusher_app_id', __('Pusher App Id'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('pusher_app_id', isset($setting['pusher_app_id']) ? $setting['pusher_app_id'] : '', ['class' => 'form-control font-style', 'placeholder' => __('Enter Pusher App Id')])); ?>

                                <?php $__errorArgs = ['pusher_app_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-pusher_app_id" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('pusher_app_key', __('Pusher App Key'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('pusher_app_key', isset($setting['pusher_app_key']) ? $setting['pusher_app_key'] : '', ['class' => 'form-control font-style', 'placeholder' => __('Enter Pusher App Key')])); ?>

                                <?php $__errorArgs = ['pusher_app_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-pusher_app_key" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('pusher_app_secret', __('Pusher App Secret'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('pusher_app_secret', isset($setting['pusher_app_secret']) ? $setting['pusher_app_secret'] : '', ['class' => 'form-control font-style', 'placeholder' => __('Enter Pusher App Secret Key')])); ?>

                                <?php $__errorArgs = ['pusher_app_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-pusher_app_secret" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('pusher_app_cluster', __('Pusher App Cluster'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('pusher_app_cluster', isset($setting['pusher_app_cluster']) ? $setting['pusher_app_cluster'] : '', ['class' => 'form-control font-style', 'placeholder' => __('Enter Pusher App Cluster')])); ?>

                                <?php $__errorArgs = ['pusher_app_cluster'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-pusher_app_cluster" role="alert">
                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div class="form-group text-end">
                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--payment Setting-->
                <div id="payment-settings" class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Payment Settings')); ?></h5>
                        <small
                            class="text-muted"><?php echo e(__('These details will be used to collect subscription plan payments.Each subscription plan will have a payment button based on the below configuration.')); ?></small>
                    </div>
                    <?php echo e(Form::open(['route' => 'payment.setting', 'method' => 'post'])); ?>

                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="col-form-label"><?php echo e(__('Currency')); ?> *</label>
                                <?php echo e(Form::text('currency', isset($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : '', ['class' => 'form-control font-style', 'required', 'placeholder' => __('Enter Currency')])); ?>

                                <small class="text-xs">
                                    <?php echo e(__('Note: Add currency code as per three-letter ISO code.')); ?>

                                    <a href="https://stripe.com/docs/currencies"
                                        target="_blank"><?php echo e(__('You can find out how to do that here')); ?></a>
                                </small>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="currency_symbol" class="col-form-label"><?php echo e(__('Currency Symbol')); ?></label>
                                <?php echo e(Form::text('currency_symbol', isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '', ['class' => 'form-control', 'required', 'placeholder' => __('Enter Currency Symbol')])); ?>

                            </div>
                        </div>
                        <div class="faq justify-content-center">
                            <div class="col-sm-12 col-md-10 col-xxl-12">
                                <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                    <!-- Manually -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading14">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                aria-expanded="false" aria-controls="collapse14">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Manually')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_manually_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_manually_enabled" id="is_manually_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_manually_enabled']) && $admin_payment_setting['is_manually_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label" for="customswitchv1-1"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse14" class="accordion-collapse collapse"
                                            aria-labelledby="heading14" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <?php echo e(__('Requesting Manual payment for the planned amount for the subscriptions plan.')); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Bank Transfer -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading15">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse15"
                                                aria-expanded="false" aria-controls="collapse15">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Bank Transfer')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_bank_transfer_enabled"
                                                            id="is_bank_transfer_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_bank_transfer_enabled']) && $admin_payment_setting['is_bank_transfer_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label" for="customswitchv1-1"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse15" class="accordion-collapse collapse"
                                            aria-labelledby="heading15" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <textarea name="bank_details" id="bank_details" cols="30"
                                                            rows="6" class="form-control"><?php echo e(!isset($admin_payment_setting['bank_details']) || is_null($admin_payment_setting['bank_details']) ? '' : $admin_payment_setting['bank_details']); ?>

                                                                </textarea>
                                                        <small class="text-xs">
                                                            <?php echo e(__('Example: bank name </br> Account Number : 0000 0000 </br>')); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Stripe -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                aria-expanded="false" aria-controls="collapseOne">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Stripe')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_stripe_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_stripe_enabled"
                                                            name="is_stripe_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-form-label'])); ?>

                                                                <?php echo e(Form::text('stripe_key', isset($admin_payment_setting['stripe_key']) ? $admin_payment_setting['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Stripe Key')])); ?>

                                                                <?php if($errors->has('stripe_key')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('stripe_key')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <?php echo e(Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'col-form-label'])); ?>

                                                                <?php echo e(Form::text('stripe_secret', isset($admin_payment_setting['stripe_secret']) ? $admin_payment_setting['stripe_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Stripe Secret')])); ?>

                                                                <?php if($errors->has('stripe_secret')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('stripe_secret')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paypal -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Paypal')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_paypal_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_paypal_enabled"
                                                            name="is_paypal_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="d-flex">
                                                    <div class="mr-2" style="margin-right: 15px;">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio" name="paypal_mode"
                                                                        value="sandbox" class="form-check-input"
                                                                        <?php echo e((isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == '') || (isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                    <?php echo e(__('Sandbox')); ?>

                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-2">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio" name="paypal_mode" value="live"
                                                                        class="form-check-input"
                                                                        <?php echo e(isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                    <?php echo e(__('Live')); ?>

                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label class="col-form-label"
                                                                    for="paypal_client_id"><?php echo e(__('Client ID')); ?></label>
                                                                <input type="text" name="paypal_client_id"
                                                                    id="paypal_client_id" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['paypal_client_id']) || is_null($admin_payment_setting['paypal_client_id']) ? '' : $admin_payment_setting['paypal_client_id']); ?>"
                                                                    placeholder="<?php echo e(__('Client ID')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label class="col-form-label"
                                                                    for="paypal_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="paypal_secret_key"
                                                                    id="paypal_secret_key" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['paypal_secret_key']) ? $admin_payment_setting['paypal_secret_key'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Secret Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paystack -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="false" aria-controls="collapseThree">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Paystack')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_paystack_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_paystack_enabled"
                                                            name="is_paystack_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                <input type="text" name="paystack_public_key"
                                                                    id="paystack_public_key" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['paystack_public_key']) ? $admin_payment_setting['paystack_public_key'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Public Key')); ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="paystack_secret_key"
                                                                    id="paystack_secret_key" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['paystack_secret_key']) ? $admin_payment_setting['paystack_secret_key'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Secret Key')); ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Flutterwave -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                aria-expanded="false" aria-controls="collapseFour">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Flutterwave')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_flutterwave_enabled"
                                                            name="is_flutterwave_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse"
                                            aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                <input type="text" name="flutterwave_public_key"
                                                                    id="flutterwave_public_key" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['flutterwave_public_key']) ? $admin_payment_setting['flutterwave_public_key'] : ''); ?>"
                                                                    placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="flutterwave_secret_key"
                                                                    id="flutterwave_secret_key" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['flutterwave_secret_key']) ? $admin_payment_setting['flutterwave_secret_key'] : ''); ?>"
                                                                    placeholder="Secret Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Razorpay -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFive">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                aria-expanded="false" aria-controls="collapseFive">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Razorpay')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_razorpay_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_razorpay_enabled"
                                                            name="is_razorpay_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseFive" class="accordion-collapse collapse"
                                            aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                <input type="text" name="razorpay_public_key"
                                                                    id="razorpay_public_key" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['razorpay_public_key']) || is_null($admin_payment_setting['razorpay_public_key']) ? '' : $admin_payment_setting['razorpay_public_key']); ?>"
                                                                    placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key" class="col-form-label">
                                                                    <?php echo e(__('Secret Key')); ?></label>
                                                                <input type="text" name="razorpay_secret_key"
                                                                    id="razorpay_secret_key" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['razorpay_secret_key']) || is_null($admin_payment_setting['razorpay_secret_key']) ? '' : $admin_payment_setting['razorpay_secret_key']); ?>"
                                                                    placeholder="Secret Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paytm -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSix">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                                aria-expanded="false" aria-controls="collapseSix">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Paytm')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_paytm_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_paytm_enabled"
                                                            name="is_paytm_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseSix" class="accordion-collapse collapse"
                                            aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="col-md-12 pb-4">
                                                    <label class="paypal-label col-form-label"
                                                        for="paypal_mode"><?php echo e(__('Paytm Environment')); ?></label>
                                                    <br>
                                                    <div class="d-flex">
                                                        <div class="mr-2" style="margin-right: 15px;">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="paytm_mode"
                                                                            value="local" class="form-check-input"
                                                                            <?php echo e(!isset($admin_payment_setting['paytm_mode']) || $admin_payment_setting['paytm_mode'] == '' || $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Local')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="paytm_mode"
                                                                            value="production" class="form-check-input"
                                                                            <?php echo e(isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Production')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gy-4">
                                                    <div class="col-lg-4">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paytm_public_key"
                                                                    class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                <input type="text" name="paytm_merchant_id"
                                                                    id="paytm_merchant_id" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['paytm_merchant_id']) ? $admin_payment_setting['paytm_merchant_id'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Merchant ID')); ?>" />
                                                                <?php if($errors->has('paytm_merchant_id')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytm_merchant_id')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key"
                                                                    class="col-form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                <input type="text" name="paytm_merchant_key"
                                                                    id="paytm_merchant_key" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['paytm_merchant_key']) ? $admin_payment_setting['paytm_merchant_key'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Merchant Key')); ?>" />
                                                                <?php if($errors->has('paytm_merchant_key')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytm_merchant_key')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paytm_industry_type"
                                                                    class="col-form-label"><?php echo e(__('Industry Type')); ?></label>
                                                                <input type="text" name="paytm_industry_type"
                                                                    id="paytm_industry_type" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['paytm_industry_type']) ? $admin_payment_setting['paytm_industry_type'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Industry Type')); ?>" />
                                                                <?php if($errors->has('paytm_industry_type')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('paytm_industry_type')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mercado Pago -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingseven">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseseven"
                                                aria-expanded="false" aria-controls="collapseseven">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Mercado Pago')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_mercado_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_mercado_enabled"
                                                            name="is_mercado_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseseven" class="accordion-collapse collapse"
                                            aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="col-md-12 pb-4">
                                                    <label class="coingate-label col-form-label"
                                                        for="mercado_mode"><?php echo e(__('Mercado Mode')); ?></label>
                                                    <br>
                                                    <div class="d-flex">
                                                        <div class="mr-2" style="margin-right: 15px;">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="mercado_mode"
                                                                            value="sandbox" class="form-check-input"
                                                                            <?php echo e((isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '') || (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="mercado_mode"
                                                                            value="live" class="form-check-input"
                                                                            <?php echo e(isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="mercado_access_token"
                                                                    class="col-form-label"><?php echo e(__('Access Token')); ?></label>
                                                                <input type="text" name="mercado_access_token"
                                                                    id="mercado_access_token" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Access Token')); ?>" />
                                                                <?php if($errors->has('mercado_secret_key')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('mercado_access_token')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mollie -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingeight">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseeight"
                                                aria-expanded="false" aria-controls="collapseeight">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Mollie')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_mollie_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_mollie_enabled"
                                                            name="is_mollie_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseeight" class="accordion-collapse collapse"
                                            aria-labelledby="headingeight" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key"
                                                                    class="col-form-label"><?php echo e(__('Mollie Api Key')); ?></label>
                                                                <input type="text" name="mollie_api_key"
                                                                    id="mollie_api_key" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['mollie_api_key']) || is_null($admin_payment_setting['mollie_api_key']) ? '' : $admin_payment_setting['mollie_api_key']); ?>"
                                                                    placeholder="Mollie Api Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="mollie_profile_id"
                                                                    class="col-form-label"><?php echo e(__('Mollie Profile Id')); ?></label>
                                                                <input type="text" name="mollie_profile_id"
                                                                    id="mollie_profile_id" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['mollie_profile_id']) || is_null($admin_payment_setting['mollie_profile_id']) ? '' : $admin_payment_setting['mollie_profile_id']); ?>"
                                                                    placeholder="Mollie Profile Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="mollie_partner_id"
                                                                    class="col-form-label"><?php echo e(__('Mollie Partner Id')); ?></label>
                                                                <input type="text" name="mollie_partner_id"
                                                                    id="mollie_partner_id" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['mollie_partner_id']) || is_null($admin_payment_setting['mollie_partner_id']) ? '' : $admin_payment_setting['mollie_partner_id']); ?>"
                                                                    placeholder="Mollie Partner Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Skrill -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingnine">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapsenine"
                                                aria-expanded="false" aria-controls="collapsenine">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Skrill')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_skrill_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_skrill_enabled"
                                                            name="is_skrill_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapsenine" class="accordion-collapse collapse"
                                            aria-labelledby="headingnine" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key"
                                                                    class="col-form-label"><?php echo e(__('Skrill Email')); ?></label>
                                                                <input type="email" name="skrill_email"
                                                                    id="skrill_email" class="form-control"
                                                                    value="<?php echo e(isset($admin_payment_setting['skrill_email']) ? $admin_payment_setting['skrill_email'] : ''); ?>"
                                                                    placeholder="<?php echo e(__('Enter Skrill Email')); ?>" />
                                                                <?php if($errors->has('skrill_email')): ?>
                                                                <span class="invalid-feedback d-block">
                                                                    <?php echo e($errors->first('skrill_email')); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CoinGate -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingten">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseten"
                                                aria-expanded="false" aria-controls="collapseten">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('CoinGate')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_coingate_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_coingate_enabled"
                                                            name="is_coingate_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseten" class="accordion-collapse collapse"
                                            aria-labelledby="headingten" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="col-md-12 pb-4">
                                                    <label class="col-form-label"
                                                        for="coingate_mode"><?php echo e(__('CoinGate Mode')); ?></label>
                                                    <br>
                                                    <div class="d-flex">
                                                        <div class="mr-2" style="margin-right: 15px;">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="coingate_mode"
                                                                            value="sandbox" class="form-check-input"
                                                                            <?php echo e(!isset($admin_payment_setting['coingate_mode']) || $admin_payment_setting['coingate_mode'] == '' || $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="coingate_mode"
                                                                            value="live" class="form-check-input"
                                                                            <?php echo e(isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="coingate_auth_token"
                                                                    class="col-form-label"><?php echo e(__('CoinGate Auth Token')); ?></label>
                                                                <input type="text" name="coingate_auth_token"
                                                                    id="coingate_auth_token" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['coingate_auth_token']) || is_null($admin_payment_setting['coingate_auth_token']) ? '' : $admin_payment_setting['coingate_auth_token']); ?>"
                                                                    placeholder="CoinGate Auth Token">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PaymentWall -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingeleven">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseeleven"
                                                aria-expanded="false" aria-controls="collapseeleven">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('PaymentWall')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            id="customswitchv1-1 is_paymentwall_enabled"
                                                            name="is_paymentwall_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseeleven" class="accordion-collapse collapse"
                                            aria-labelledby="headingeleven" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paymentwall_public_key"
                                                                    class="col-form-label"><?php echo e(__('Public Key')); ?></label>
                                                                <input type="text" name="paymentwall_public_key"
                                                                    id="paymentwall_public_key" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['paymentwall_public_key']) || is_null($admin_payment_setting['paymentwall_public_key']) ? '' : $admin_payment_setting['paymentwall_public_key']); ?>"
                                                                    placeholder="<?php echo e(__('Public Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="input-edits">
                                                            <div class="form-group">
                                                                <label for="paymentwall_private_key"
                                                                    class="col-form-label"><?php echo e(__('Private Key')); ?></label>
                                                                <input type="text" name="paymentwall_private_key"
                                                                    id="paymentwall_private_key" class="form-control"
                                                                    value="<?php echo e(!isset($admin_payment_setting['paymentwall_private_key']) || is_null($admin_payment_setting['paymentwall_private_key']) ? '' : $admin_payment_setting['paymentwall_private_key']); ?>"
                                                                    placeholder="<?php echo e(__('Private Key')); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Toyyibpay -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-2-13">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse12"
                                                aria-expanded="true" aria-controls="collapse12">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Toyyibpay')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_toyyibpay_enabled">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span></label>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="is_toyyibpay_enabled" id="is_toyyibpay_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse12" class="accordion-collapse collapse"
                                            aria-labelledby="heading-2-13" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="toyyibpay_secret_key"
                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                            <input type="text" name="toyyibpay_secret_key"
                                                                id="toyyibpay_secret_key" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['toyyibpay_secret_key']) || is_null($admin_payment_setting['toyyibpay_secret_key']) ? '' : $admin_payment_setting['toyyibpay_secret_key']); ?>"
                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="category_code"
                                                                class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                            <input type="text" name="category_code" id="category_code"
                                                                class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['category_code']) || is_null($admin_payment_setting['category_code']) ? '' : $admin_payment_setting['category_code']); ?>"
                                                                placeholder="<?php echo e(__('Category Code')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payfast -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading13">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse13"
                                                aria-expanded="false" aria-controls="collapse13">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Payfast')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_payfast_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_payfast_enabled" id="is_payfast_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label for="customswitch1-2" class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>

                                        <div class="accordion-collapse collapse" id="collapse13"
                                            aria-labelledby="heading13" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">

                                                    <label class="col-form-label"
                                                        for="payfast_mode"><?php echo e(__('Payfast Mode')); ?></label> <br>
                                                    <div class="d-flex">
                                                        <div class="mr-2" style="margin-right: 15px;">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="payfast_mode"
                                                                            value="sandbox" class="form-check-input"
                                                                            <?php echo e(!isset($admin_payment_setting['payfast_mode']) || $admin_payment_setting['payfast_mode'] == '' || $admin_payment_setting['payfast_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Sandbox')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            <div class="border card p-3">
                                                                <div class="form-check">
                                                                    <label class="form-check-labe text-dark">
                                                                        <input type="radio" name="payfast_mode"
                                                                            value="live" class="form-check-input"
                                                                            <?php echo e(isset($store_settings['payfast_mode']) && $store_settings['payfast_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                        <?php echo e(__('Live')); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="merchant_id"
                                                                class="col-form-label"><?php echo e(__('Merchant id')); ?></label>
                                                            <input type="text" name="payfast_merchant_id"
                                                                id="payfast_merchant_id" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['payfast_merchant_id']) || is_null($admin_payment_setting['payfast_merchant_id']) ? '' : $admin_payment_setting['payfast_merchant_id']); ?>"
                                                                placeholder="<?php echo e(__('Payfast Merchant Id')); ?>">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="merchant_key"
                                                                class="col-form-label"><?php echo e('Merchant key'); ?></label>
                                                            <input type="text" name="payfast_merchant_key"
                                                                id="payfast_merchant_key" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['payfast_merchant_key']) || is_null($admin_payment_setting['payfast_merchant_key']) ? '' : $admin_payment_setting['payfast_merchant_key']); ?>"
                                                                placeholder="<?php echo e(__('Payfast Merchant Key')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="payfast_signature"
                                                                class="col-form-label"><?php echo e(__('Payfast Signature')); ?></label>
                                                            <input type="text" name="payfast_signature"
                                                                id="payfast_signature" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['payfast_signature']) || is_null($admin_payment_setting['payfast_signature']) ? '' : $admin_payment_setting['payfast_signature']); ?>"
                                                                placeholder="<?php echo e(__('Payfast Signature')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Iyzipay-->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="headingFourteen">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                    aria-expanded="false" aria-controls="collapse14">
                                                    <span class="d-flex align-items-center">
                                                        <?php echo e(__('Iyzipay')); ?>

                                                    </span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_iyzipay_enabled" value="off">
                                                            <input type="checkbox"
                                                                class="form-check-input input-primary"
                                                                id="customswitchv1-1 is_iyzipay_enabled"
                                                                name="is_iyzipay_enabled"
                                                                <?php echo e(isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse14" class="accordion-collapse collapse"
                                                aria-labelledby="headingFourteen" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="col-md-12 pb-4">
                                                        <div class="d-flex">
                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark">
                                                                            <input type="radio" name="iyzipay_mode"
                                                                                value="sandbox" class="form-check-input"
                                                                                <?php echo e((isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == '') || (isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Sandbox')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mr-2">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label text-dark">
                                                                            <input type="radio" name="iyzipay_mode"
                                                                                value="live" class="form-check-input"
                                                                                <?php echo e(isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                            <?php echo e(__('Live')); ?>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gy-4">
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label class="col-form-label"
                                                                        for="iyzipay_public_key"><?php echo e(__('Public Key')); ?></label>
                                                                    <input type="text" name="iyzipay_public_key"
                                                                        id="iyzipay_public_key" class="form-control"
                                                                        value="<?php echo e(!isset($admin_payment_setting['iyzipay_public_key']) || is_null($admin_payment_setting['iyzipay_public_key']) ? '' : $admin_payment_setting['iyzipay_public_key']); ?>"
                                                                        placeholder="<?php echo e(__('Public Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-edits">
                                                                <div class="form-group">
                                                                    <label class="col-form-label"
                                                                        for="iyzipay_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                    <input type="text" name="iyzipay_secret_key"
                                                                        id="iyzipay_secret_key" class="form-control"
                                                                        value="<?php echo e(isset($admin_payment_setting['iyzipay_secret_key']) ? $admin_payment_setting['iyzipay_secret_key'] : ''); ?>"
                                                                        placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sspay -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-2-131">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse121"
                                                aria-expanded="true" aria-controls="collapse121">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Sspay')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_sspay_enabled">
                                                        <span class="me-2"><?php echo e(__('Enable:')); ?></span></label>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_sspay_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="is_sspay_enabled" id="is_sspay_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on' ? 'checked' : ''); ?>>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse121" class="accordion-collapse collapse"
                                            aria-labelledby="heading-2-131" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sspay_secret_key"
                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                            <input type="text" name="sspay_secret_key"
                                                                id="sspay_secret_key" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['sspay_secret_key']) || is_null($admin_payment_setting['sspay_secret_key']) ? '' : $admin_payment_setting['sspay_secret_key']); ?>"
                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sspay_category_code"
                                                                class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                            <input type="text" name="sspay_category_code"
                                                                id="sspay_category_code" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['sspay_category_code']) || is_null($admin_payment_setting['sspay_category_code']) ? '' : $admin_payment_setting['sspay_category_code']); ?>"
                                                                placeholder="<?php echo e(__('Category Code')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- paytab -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwenty">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwenty"
                                                aria-expanded="true" aria-controls="collapseTwenty">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Paytab')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                        <input type="hidden" name="is_paytab_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="is_paytab_enabled" id="is_paytab_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="custom-control-label form-label"
                                                            for="is_paytab_enabled"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseTwenty" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwenty" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytab_profile_id"
                                                                class="col-form-label"><?php echo e(__('Profile Id')); ?></label>
                                                            <input type="text" name="paytab_profile_id"
                                                                id="paytab_profile_id" class="form-control"
                                                                value="<?php echo e(isset($admin_payment_setting['paytab_profile_id']) ? $admin_payment_setting['paytab_profile_id'] : ''); ?>"
                                                                placeholder="<?php echo e(__('Profile Id')); ?>">
                                                        </div>
                                                        <?php if($errors->has('paytab_profile_id')): ?>
                                                        <span class="invalid-feedback d-block">
                                                            <?php echo e($errors->first('paytab_profile_id')); ?>

                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytab_server_key"
                                                                class="col-form-label"><?php echo e(__('Server Key')); ?></label>
                                                            <input type="text" name="paytab_server_key"
                                                                id="paytab_server_key" class="form-control"
                                                                value="<?php echo e(isset($admin_payment_setting['paytab_server_key']) ? $admin_payment_setting['paytab_server_key'] : ''); ?>"
                                                                placeholder="<?php echo e(__('Sspay Secret')); ?>">
                                                        </div>
                                                        <?php if($errors->has('paytab_server_key')): ?>
                                                        <span class="invalid-feedback d-block">
                                                            <?php echo e($errors->first('paytab_server_key')); ?>

                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytab_region"
                                                                class="form-label"><?php echo e(__('Region')); ?></label>
                                                            <input type="text" name="paytab_region" id="paytab_region"
                                                                class="form-control form-control-label"
                                                                value="<?php echo e(isset($admin_payment_setting['paytab_region']) ? $admin_payment_setting['paytab_region'] : ''); ?>"
                                                                placeholder="<?php echo e(__('Region')); ?>" /><br>
                                                            <?php if($errors->has('paytab_region')): ?>
                                                            <span class="invalid-feedback d-block">
                                                                <?php echo e($errors->first('paytab_region')); ?>

                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwentyOne">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne"
                                                aria-expanded="false" aria-controls="collapseTwentyOne">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Benefit')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_benefit_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_benefit_enabled" id="is_benefit_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="is_benefit_enabled"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseTwentyOne" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwentyOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('benefit_api_key', __('Benefit Key'), ['class' => 'col-form-label'])); ?>

                                                            <?php echo e(Form::text('benefit_api_key', isset($admin_payment_setting['benefit_api_key']) ? $admin_payment_setting['benefit_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Benefit Key')])); ?>

                                                            <?php $__errorArgs = ['benefit_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-benefit_api_key" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('benefit_secret_key', __('Benefit Secret Key'), ['class' => 'col-form-label'])); ?>

                                                            <?php echo e(Form::text('benefit_secret_key', isset($admin_payment_setting['benefit_secret_key']) ? $admin_payment_setting['benefit_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Benefit Secret key')])); ?>

                                                            <?php $__errorArgs = ['benefit_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-benefit_secret_key" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwentyTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwentyTwo"
                                                aria-expanded="false" aria-controls="collapseTwentyTwo">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Cashfree')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_cashfree_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_cashfree_enabled" id="is_cashfree_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="is_cashfree_enabled"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseTwentyTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwentyTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row gy-4">

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('cashfree_api_key', __('Cashfree Key'), ['class' => 'col-form-label'])); ?>

                                                            <?php echo e(Form::text('cashfree_api_key', isset($admin_payment_setting['cashfree_api_key']) ? $admin_payment_setting['cashfree_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')])); ?>

                                                            <?php $__errorArgs = ['cashfree_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-cashfree_api_key" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('cashfree_secret_key', __('Cashfree Secret Key'), ['class' => 'col-form-label'])); ?>

                                                            <?php echo e(Form::text('cashfree_secret_key', isset($admin_payment_setting['cashfree_secret_key']) ? $admin_payment_setting['cashfree_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')])); ?>

                                                            <?php $__errorArgs = ['cashfree_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-cashfree_secret_key" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="accordion-item card shadow-none">
                                        <h2 class="accordion-header" id="heading-2-20">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse20"
                                                aria-expanded="true" aria-controls="collapse20">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('Aamarpay')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_aamarpay_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label for="customswitch1-2" class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse20" class="accordion-collapse collapse"
                                            aria-labelledby="heading-2-20" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="aamarpay_store_id"
                                                                class="form-label"><?php echo e(__(' Store Id')); ?></label>
                                                            <input type="text" name="aamarpay_store_id"
                                                                id="aamarpay_store_id" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['aamarpay_store_id']) || is_null($admin_payment_setting['aamarpay_store_id']) ? '' : $admin_payment_setting['aamarpay_store_id']); ?>"
                                                                placeholder="<?php echo e(__('Enter Store Id')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="aamarpay_signature_key"
                                                                class="form-label"><?php echo e(__('Signature Key')); ?></label>
                                                            <input type="text" name="aamarpay_signature_key"
                                                                id="aamarpay_signature_key" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['aamarpay_signature_key']) || is_null($admin_payment_setting['aamarpay_signature_key']) ? '' : $admin_payment_setting['aamarpay_signature_key']); ?>"
                                                                placeholder="<?php echo e(__('Enter Signature Key')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="aamarpay_description"
                                                                class="form-label"><?php echo e(__('Description')); ?></label>
                                                            <input type="text" name="aamarpay_description"
                                                                id="aamarpay_description" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['aamarpay_description']) || is_null($admin_payment_setting['aamarpay_description']) ? '' : $admin_payment_setting['aamarpay_description']); ?>"
                                                                placeholder="<?php echo e(__('Enter Signature Key')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwentyfour">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwentyfive"
                                                aria-expanded="true" aria-controls="collapseTwentyfive">
                                                <span class="d-flex align-items-center">
                                                    <?php echo e(__('PayTR')); ?>

                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable:')); ?></span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_paytr_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_paytr_enabled" id="is_paytr_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label for="customswitch1-2" class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseTwentyfive" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwentyfour" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row pt-2">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('paytr_merchant_id', __('Merchant Id'), ['class' => 'form-label'])); ?>

                                                            <?php echo e(Form::text('paytr_merchant_id', isset($admin_payment_setting['paytr_merchant_id']) ? $admin_payment_setting['paytr_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Id')])); ?><br>
                                                            <?php if($errors->has('paytr_merchant_id')): ?>
                                                            <span class="invalid-feedback d-block">
                                                                <?php echo e($errors->first('paytr_merchant_id')); ?>

                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('paytr_merchant_key', __('Merchant Key'), ['class' => 'form-label'])); ?>

                                                            <?php echo e(Form::text('paytr_merchant_key', isset($admin_payment_setting['paytr_merchant_key']) ? $admin_payment_setting['paytr_merchant_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')])); ?><br>
                                                            <?php if($errors->has('paytr_merchant_key')): ?>
                                                            <span class="invalid-feedback d-block">
                                                                <?php echo e($errors->first('paytr_merchant_key')); ?>

                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php echo e(Form::label('paytr_merchant_salt', __('Merchant Salt'), ['class' => 'form-label'])); ?>

                                                            <?php echo e(Form::text('paytr_merchant_salt', isset($admin_payment_setting['paytr_merchant_salt']) ? $admin_payment_setting['paytr_merchant_salt'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Salt')])); ?><br>
                                                            <?php if($errors->has('paytr_merchant_salt')): ?>
                                                            <span class="invalid-feedback d-block">
                                                                <?php echo e($errors->first('paytr_merchant_salt')); ?>

                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-yookassa">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse19" aria-expanded="true"
                                                aria-controls="collapse19">
                                                <span class="d-flex align-items-center"><?php echo e(__('Yookassa')); ?></span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_yookassa_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_yookassa_enabled" id="is_yookassa_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="is_yookassa_enabled"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse19" class="accordion-collapse collapse"
                                            aria-labelledby="heading-yookassa" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="yookassa_shop_id"
                                                                class="col-form-label"><?php echo e(__('Shop ID Key')); ?></label>
                                                            <input type="text" name="yookassa_shop_id"
                                                                id="yookassa_shop_id" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['yookassa_shop_id']) || is_null($admin_payment_setting['yookassa_shop_id']) ? '' : $admin_payment_setting['yookassa_shop_id']); ?>"
                                                                placeholder="<?php echo e(__('Shop ID Key')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="yookassa_secret_key"
                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                            <input type="text" name="yookassa_secret_key"
                                                                id="yookassa_secret_key" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['yookassa_secret_key']) || is_null($admin_payment_setting['yookassa_secret_key']) ? '' : $admin_payment_setting['yookassa_secret_key']); ?>"
                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-midtrans">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse16" aria-expanded="true"
                                                aria-controls="collapse16">
                                                <span class="d-flex align-items-center"><?php echo e(__('Midtrans')); ?></span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_midtrans_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_midtrans_enabled" id="is_midtrans_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="is_midtrans_enabled"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse16" class="accordion-collapse collapse"
                                            aria-labelledby="heading-midtrans" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                        <div class="row pt-2">
                                                            <label class="pb-2"
                                                                for="midtrans_mode"><?php echo e(__('Midtrans Mode')); ?></label>
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2"
                                                                    style="margin-right: 15px; width: 190px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label
                                                                                class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="midtrans_mode"
                                                                                    value="sandbox"
                                                                                    class="form-check-input"
                                                                                    <?php echo e(!isset($admin_payment_setting['midtrans_mode']) || $admin_payment_setting['midtrans_mode'] == '' || $admin_payment_setting['midtrans_mode'] == 'sandbox' ? 'checked="checked"' : ''); ?>>
                                                                                <?php echo e(__('Sandbox')); ?>

                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2" style="width: 190px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label
                                                                                class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="midtrans_mode"
                                                                                    value="live"
                                                                                    class="form-check-input"
                                                                                    <?php echo e(isset($admin_payment_setting['midtrans_mode']) && $admin_payment_setting['midtrans_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                <?php echo e(__('Live')); ?>

                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="midtrans_secret"
                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                            <input type="text" name="midtrans_secret"
                                                                id="midtrans_secret" class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['midtrans_secret']) || is_null($admin_payment_setting['midtrans_secret']) ? '' : $admin_payment_setting['midtrans_secret']); ?>"
                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-xendit">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse17" aria-expanded="true"
                                                aria-controls="collapse17">
                                                <span class="d-flex align-items-center"><?php echo e(__('Xendit')); ?></span>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2"><?php echo e(__('Enable')); ?>:</span>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden" name="is_xendit_enabled" value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="is_xendit_enabled" id="is_xendit_enabled"
                                                            <?php echo e(isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                        <label class="form-check-label" for="is_xendit_enabled"></label>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse17" class="accordion-collapse collapse"
                                            aria-labelledby="heading-xendit" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="xendit_api_key"
                                                                class="col-form-label"><?php echo e(__('API Key')); ?></label>
                                                            <input type="text" name="xendit_api_key" id="xendit_api_key"
                                                                class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['xendit_api_key']) || is_null($admin_payment_setting['xendit_api_key']) ? '' : $admin_payment_setting['xendit_api_key']); ?>"
                                                                placeholder="<?php echo e(__('API Key')); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="xendit_token"
                                                                class="col-form-label"><?php echo e(__('Token')); ?></label>
                                                            <input type="text" name="xendit_token" id="xendit_token"
                                                                class="form-control"
                                                                value="<?php echo e(!isset($admin_payment_setting['xendit_token']) || is_null($admin_payment_setting['xendit_token']) ? '' : $admin_payment_setting['xendit_token']); ?>"
                                                                placeholder="<?php echo e(__('Token')); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-0">
                            <div class="col-sm-12 mt-3 px-2">
                                <div class="text-end">
                                    <input class="btn btn-print-invoice  btn-primary" type="submit"
                                        value="<?php echo e(__('Save Changes')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Recaptcha Setting-->
                <div id="recaptcha-settings" class="card">
                    <form method="POST" action="<?php echo e(route('recaptcha.settings.store')); ?>" accept-charset="UTF-8">
                        <?php echo csrf_field(); ?>
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5><?php echo e(__('ReCaptcha Settings')); ?></h5>
                                        <small class="text-secondary font-weight-bold">
                                            <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                target="_blank" class="text-blue">
                                                <small>(<?php echo e(__('How to Get Google reCaptcha Site and Secret key')); ?>)</small>
                                            </a>
                                        </small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="recaptcha_module" id="recaptcha_module"
                                                    data-toggle="switchbutton" value="yes"
                                                    <?php echo e(isset($setting['recaptcha_module']) && $setting['recaptcha_module'] ==  'yes' ? 'checked="checked"' : ''); ?>

                                                    data-onstyle="primary">
                                                <label class="form-check-labe" for="recaptcha_module"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label class="form-label"><?php echo e(__('Google Recaptcha Key')); ?></label>
                                        <input class="form-control" placeholder="<?php echo e(__('Enter Google Recaptcha Key')); ?>"
                                            name="google_recaptcha_key" type="text"
                                            value="<?php echo e(!isset($setting['google_recaptcha_key']) || is_null($setting['google_recaptcha_key']) ? '' : $setting['google_recaptcha_key']); ?>"
                                            id="google_recaptcha_key">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label class="form-label"><?php echo e(__('Google Recaptcha Secret')); ?></label>
                                        <input class="form-control "
                                            placeholder="<?php echo e(__('Enter Google Recaptcha Secret')); ?>"
                                            name="google_recaptcha_secret" type="text"
                                            value="<?php echo e(!isset($setting['google_recaptcha_secret']) || is_null($setting['google_recaptcha_secret']) ? '' : $setting['google_recaptcha_secret']); ?>"
                                            id="google_recaptcha_secret">
                                    </div>
                                </div>
                                <div class="card-footer p-0">
                                    <div class="col-sm-12 mt-3 px-2">
                                        <div class="text-end">
                                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                                value="<?php echo e(__('Save Changes')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!--Storage Setting-->
                <div id="storage-settings" class="card">
                    <?php echo e(Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h5 class=""><?php echo e(__('Storage Settings')); ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                                    autocomplete="off" <?php echo e($setting['storage_setting'] == 'local' ? 'checked' : ''); ?>

                                    value="local" checked>
                                <label class="btn btn-outline-primary" for="local-outlined"><?php echo e(__('Local')); ?></label>
                            </div>
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                    autocomplete="off" <?php echo e($setting['storage_setting'] == 's3' ? 'checked' : ''); ?>

                                    value="s3">
                                <label class="btn btn-outline-primary" for="s3-outlined">
                                    <?php echo e(__('AWS S3')); ?></label>
                            </div>

                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                                    autocomplete="off" <?php echo e($setting['storage_setting'] == 'wasabi' ? 'checked' : ''); ?>

                                    value="wasabi">
                                <label class="btn btn-outline-primary" for="wasabi-outlined"><?php echo e(__('Wasabi')); ?></label>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div
                                class="local-setting row <?php echo e($setting['storage_setting'] == 'local' ? ' ' : 'd-none'); ?>">
                                <div class="form-group col-8 switch-width">
                                    <?php echo e(Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' form-label'])); ?>

                                    <select name="local_storage_validation[]" class="multi-select"
                                        id="local_storage_validation" multiple>
                                        <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if(in_array($f, $local_storage_validations)): ?> selected <?php endif; ?>>
                                            <?php echo e($f); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="local_storage_max_upload_size"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                        <input type="number" name="local_storage_max_upload_size" class="form-control"
                                            value="<?php echo e(!isset($setting['local_storage_max_upload_size']) || is_null($setting['local_storage_max_upload_size']) ? '' : $setting['local_storage_max_upload_size']); ?>"
                                            placeholder="<?php echo e(__('Max upload size')); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="s3-setting row <?php echo e($setting['storage_setting'] == 's3' ? ' ' : 'd-none'); ?>">
                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_key"><?php echo e(__('S3 Key')); ?></label>
                                            <input type="text" name="s3_key" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key']); ?>"
                                                placeholder="<?php echo e(__('S3 Key')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_secret"><?php echo e(__('S3 Secret')); ?></label>
                                            <input type="text" name="s3_secret" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret']); ?>"
                                                placeholder="<?php echo e(__('S3 Secret')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_region"><?php echo e(__('S3 Region')); ?></label>
                                            <input type="text" name="s3_region" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region']); ?>"
                                                placeholder="<?php echo e(__('S3 Region')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_bucket"><?php echo e(__('S3 Bucket')); ?></label>
                                            <input type="text" name="s3_bucket" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket']); ?>"
                                                placeholder="<?php echo e(__('S3 Bucket')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_url"><?php echo e(__('S3 URL')); ?></label>
                                            <input type="text" name="s3_url" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url']); ?>"
                                                placeholder="<?php echo e(__('S3 URL')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_endpoint"><?php echo e(__('S3 Endpoint')); ?></label>
                                            <input type="text" name="s3_endpoint" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint']); ?>"
                                                placeholder="<?php echo e(__('S3 Endpoint')); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        <?php echo e(Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-label'])); ?>

                                        <select name="s3_storage_validation[]" class="multi-select"
                                            id="s3_storage_validation" multiple>
                                            <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if(in_array($f, $s3_storage_validations)): ?> selected <?php endif; ?>>
                                                <?php echo e($f); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="s3_max_upload_size"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                            <input type="number" name="s3_max_upload_size" class="form-control"
                                                value="<?php echo e(!isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size']); ?>"
                                                placeholder="<?php echo e(__('Max upload size')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="wasabi-setting row <?php echo e($setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none'); ?>">
                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_key"><?php echo e(__('Wasabi Key')); ?></label>
                                            <input type="text" name="wasabi_key" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key']); ?>"
                                                placeholder="<?php echo e(__('Wasabi Key')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_secret"><?php echo e(__('Wasabi Secret')); ?></label>
                                            <input type="text" name="wasabi_secret" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret']); ?>"
                                                placeholder="<?php echo e(__('Wasabi Secret')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_region"><?php echo e(__('Wasabi Region')); ?></label>
                                            <input type="text" name="wasabi_region" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region']); ?>"
                                                placeholder="<?php echo e(__('Wasabi Region')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="wasabi_bucket"><?php echo e(__('Wasabi Bucket')); ?></label>
                                            <input type="text" name="wasabi_bucket" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket']); ?>"
                                                placeholder="<?php echo e(__('Wasabi Bucket')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_url"><?php echo e(__('Wasabi URL')); ?></label>
                                            <input type="text" name="wasabi_url" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url']); ?>"
                                                placeholder="<?php echo e(__('Wasabi URL')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_root"><?php echo e(__('Wasabi Root')); ?></label>
                                            <input type="text" name="wasabi_root" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root']); ?>"
                                                placeholder="<?php echo e(__('Wasabi Root')); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        <?php echo e(Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label'])); ?>


                                        <select name="wasabi_storage_validation[]" class="multi-select"
                                            id="wasabi_storage_validation" multiple>
                                            <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if(in_array($f, $wasabi_storage_validations)): ?> selected <?php endif; ?>>
                                                <?php echo e($f); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="wasabi_root"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                            <input type="number" name="wasabi_max_upload_size" class="form-control"
                                                value="<?php echo e(!isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size']); ?>"
                                                placeholder="<?php echo e(__('Max upload size')); ?>">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>

                <!--cache Setting-->
                <div id="cache-settings" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="h6 md-0"><?php echo e(__('Cache Settings')); ?></h5>
                                <small class="text-secondary font-weight-bold">
                                    This is a page meant for more advanced users, simply ignore it if you don't
                                    understand what cache is.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12 form-group">
                            <label for="Current cache size" class="col-form-label text-dark">Current cache
                                size</label>
                            <div class="input-group search-form">
                                <input type="text" value="<?php echo e(Utility::GetCacheSize()); ?>" class="form-control" readonly>
                                <span class="input-group-text bg-transparent">MB</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="<?php echo e(url('config-cache')); ?>"
                            class="btn btn-print-invoice btn-primary m-r-10"><?php echo e(__('Clear Cache')); ?></a>
                    </div>
                </div>

                <!--SEO Setting-->
                <div id="SEO-settings" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <h5><?php echo e(__('SEO Settings')); ?></h5>
                            </div>
                            <?php
                            $settings = App\Models\Utility::settings();
                            ?>
                            <?php if(!empty($settings['chatgpt_key'])): ?>
                            <div class="col-lg-6 col-md-6 col-sm-6 text-end">
                                <a data-size="md" class="btn btn-sm btn-primary text-light" data-ajax-popup-over="true"
                                    data-size="md" data-title="<?php echo e(__('Generate')); ?>"
                                    data-url="<?php echo e(route('generate', ['seo'])); ?>" data-toggle="tooltip"
                                    title="<?php echo e(__('Generate')); ?>">
                                    <i class="fas fa-robot"> <?php echo e(__('Generate With AI')); ?></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php echo e(Form::open(['url' => route('seo.settings'), 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('Meta Keywords', __('Meta Keywords'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('meta_keyword', !empty($setting['meta_keyword']) ? $setting['meta_keyword'] : '', ['class' => 'form-control ', 'placeholder' => __('Meta Keywords')])); ?>

                                    <?php $__errorArgs = ['meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-meta_keywords" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('Meta Description', __('Meta Description'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::textarea('meta_description', !empty($setting['meta_description']) ? $setting['meta_description'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Description', 'rows' => 7])); ?>

                                    <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-meta_description" role="alert">
                                        <strong class="text-danger"><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <?php echo e(Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label'])); ?>

                                </div>
                                <div class="setting-card">
                                    <div class="logo-content">
                                        <img id="meta"
                                            src="<?php echo e($meta_image . '/' . (isset($setting['meta_image']) && !empty($setting['meta_image']) ? $setting['meta_image'] : 'meta_image.png')); ?>"
                                            class="img_setting seo_image" max-width="300px" width="100%">
                                    </div>
                                    <div class="choose-files mt-4">
                                        <label for="meta_image">
                                            <div class="bg-primary company_favicon_update"> <i
                                                    class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                            </div>
                                            <input type="file" class="form-control file" id="meta_image"
                                                name="meta_image" data-filename="edit-meta_image"
                                                accept=".jpeg,.jpg,.png" accept=".jpeg,.jpg,.png"
                                                onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                        </label>
                                    </div>
                                    <?php $__errorArgs = ['meta_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="row">
                                        <span class="invalid-logo" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 card-footer text-end">
                        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                            value="<?php echo e(__('Save Changes')); ?>">
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <!--Cookie Setting-->
                <div id="cookie-settings" class="card">
                    <?php echo e(Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post'])); ?>

                    <div
                        class="card-header flex-column flex-lg-row d-flex align-items-lg-center gap-2 justify-content-between">
                        <h5><?php echo e(__('Cookie Settings')); ?></h5>
                        <div class="d-flex align-items-center">
                            <?php echo e(Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3'])); ?>

                            <div class="custom-control custom-switch " onclick="enablecookie()">
                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                    name="enable_cookie" class="form-check-input input-primary CookieStatus"
                                    id="enable_cookie" <?php echo e($settings['enable_cookie'] == 'on' ? 'checked ' : ''); ?>>
                                <label class="custom-control-label mb-1" for="enable_cookie"></label>
                            </div>
                        </div>
                    </div>

                    <div
                        class="card-body cookie_status <?php echo e($setting['enable_cookie'] == 'off' ? 'disabledCookie ' : ''); ?> ">
                        <div class="row">
                            <?php
                            $settings = App\Models\Utility::settings();
                            ?>
                            <?php if(!empty($settings['chatgpt_key'])): ?>
                            <div class="col-md-12">
                                <a data-size="md" class="btn btn-sm btn-primary text-light float-end"
                                    data-ajax-popup-over="true" data-title="<?php echo e(__('Generate')); ?>"
                                    data-url="<?php echo e(route('generate', ['cookie'])); ?>" data-toggle="tooltip"
                                    title="<?php echo e(__('Generate')); ?>">
                                    <i class="fas fa-robot"> <?php echo e(__('Generate With AI')); ?></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                    <input type="checkbox" name="cookie_logging" class="form-check-input input-primary"
                                        id="cookie_logging"
                                        <?php echo e($settings['cookie_logging'] == 'on' ? ' checked ' : ''); ?>>
                                    <label class="form-check-label"
                                        for="cookie_logging"><?php echo e(__('Enable logging')); ?></label>
                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('cookie_title', null, ['class' => 'form-control', 'placeholder' => __('Enter Cookie Title')])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label'])); ?>

                                    <?php echo Form::textarea('cookie_description', null, [
                                    'class' => 'form-control',
                                    'rows' => '3',
                                    'placeholder' => __('Enter Cookie Description'),
                                    ]); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1 ">
                                    <input type="checkbox" name="necessary_cookies"
                                        class="form-check-input input-primary" id="necessary_cookies" checked
                                        onclick="return false">
                                    <label class="form-check-label"
                                        for="necessary_cookies"><?php echo e(__('Strictly necessary cookies')); ?></label>
                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('strictly_cookie_title', null, ['class' => 'form-control', 'placeholder' => __('Enter Strictly Cookie Title')])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label'])); ?>

                                    <?php echo Form::textarea('strictly_cookie_description', null, [
                                    'class' => 'form-control ',
                                    'rows' => '3',
                                    'placeholder' => __('Enter Strictly Cookie Description'),
                                    ]); ?>

                                </div>
                            </div>

                            <div class="col-12">
                                <h5><?php echo e(__('More Information')); ?></h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <?php echo e(Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('more_information_description', null, ['class' => 'form-control', 'placeholder' => __('Contact Us Description')])); ?>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <?php echo e(Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('contactus_url', null, ['class' => 'form-control', 'placeholder' => __('Contact Us URL')])); ?>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer  mb-3">
                        <div class="row ">
                            <div class="col-6 d-flex justify-content-start">
                                <?php if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on'): ?>
                                <label for="file" class="form-label"><?php echo e(__('Download cookie accepted data')); ?></label>
                                <a href="<?php echo e(asset(Storage::url('uploads/sample')) . '/data.csv'); ?>"
                                    class="btn btn-primary mr-3 mx-3">
                                    <i class="ti ti-download"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-6  d-flex justify-content-end">
                                <input class="btn btn-print-invoice  btn-primary cookie_btn" type="submit"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <div class="" id="pills-chatgpt-settings" role="tabpanel" aria-labelledby="pills-chatgpt-tab">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card">
                            <?php echo e(Form::model($settings, ['route' => 'settings.chatgptkey', 'method' => 'post'])); ?>

                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5><?php echo e(__('Chat GPT Key Settings')); ?></h5>
                                        <small class="text-secondary font-weight-bold">
                                            <?php echo e(__('Edit your key details')); ?>

                                        </small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col-12 switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton"
                                                    class="form-check-input" name="is_chatgpt_key_enabled"
                                                    id="is_chatgpt_key_enabled"
                                                    <?php echo e(isset($settings['is_chatgpt_key_enabled']) && $settings['is_chatgpt_key_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                <label class="form-check-labe"
                                                    for="is_chatgpt_key_enabled"><?php echo e(__('')); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group">
                                        <?php echo e(Form::label('chatgpt_key', __('Chat GPT key'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('chatgpt_key', isset($settings['chatgpt_key']) ? $settings['chatgpt_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chatgpt Key Here')])); ?>

                                    </div>
                                    <div class="form-group">
                                        <?php echo e(Form::label('chatgpt_model_name', __('Chat GPT Model Name'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('chatgpt_model_name', isset($settings['chatgpt_model_name']) ? $settings['chatgpt_model_name'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chat GPT Model Name ')])); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit"><?php echo e(__('Save Changes')); ?></button>
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script type="text/javascript">
$('#enable_cookie').change(function() {
    if ($(this).is(':checked')) {
        $('.cookie_status').removeClass('disabledCookie');
        $('#cookie_logging').prop('checked', true);
    } else {
        $('.cookie_status').addClass('disabledCookie');
        $("#cookie_logging").prop('checked', false);
    }
});

$('document').ready(function() {
    $('#bank_details').each(function() {
        $(this).val($(this).val().trim());
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\crm\resources\views/settings/index.blade.php ENDPATH**/ ?>