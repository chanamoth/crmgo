<?php
    $settings = Utility::settings();
    $color = !empty($settings['color']) ? $settings['color'] : 'theme-3';


    if (isset($settings['color_flag']) && $settings['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }

    //$logo = asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');

    $company_favicon = $settings['favicon'];
    $setting = App\Models\Utility::colorset();
    
    $lang = \App::getLocale('lang');
    if ($lang == 'ar' || $lang == 'he') {
        $settings['SITE_RTL'] = 'on';
    }
    $SITE_RTL = !empty($settings['SITE_RTL']) ? $settings['SITE_RTL'] : '';

    $logos = Utility::get_superadmin_logo();
    //meta tag
    // $meta = DB::table('settings')
    //     ->where('created_by', '=', 1)
    //     ->get();
    // foreach ($meta as $row) {
    //     $settings[$row->name] = $row->value;
    // }
?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($SITE_RTL == 'on' ? 'rtl' : ''); ?>">

<head>
    <title> <?php echo $__env->yieldContent('page-title'); ?> -
        <?php echo e(Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'CRMGo-Saas')); ?>

    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Rajodiya Infotech">
    
    
    <meta name="keyword" content="<?php echo e(!empty($settings['meta_keyword']) ? $settings['meta_keyword'] : ''); ?>">
    <meta name="description" content="<?php echo e(!empty($settings['meta_description']) ? $settings['meta_description'] : ''); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="og:title" content="<?php echo e(!empty($settings['meta_keyword']) ? $settings['meta_keyword'] : ''); ?>">
    <meta property="og:description"
        content="<?php echo e(!empty($settings['meta_description']) ? $settings['meta_description'] : ''); ?>">
    <meta property="og:image"
        content="<?php echo e(asset('storage/meta/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : ''))); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="twitter:title" content="<?php echo e(!empty($settings['meta_keyword']) ? $settings['meta_keyword'] : ''); ?>">
    <meta property="twitter:description"
        content="<?php echo e(!empty($settings['meta_description']) ? $settings['meta_description'] : 'meta_image.png'); ?>">
    <meta property="twitter:image"
        content="<?php echo e(isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : ''); ?>">
    

    <link rel="icon"
        href="<?php echo e($logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png')); ?>"type="image/x-icon" />
    

    <!-- font css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">

    <?php if($setting['cust_darklayout']=='on'): ?>
        <?php if(isset($SITE_RTL) && $SITE_RTL == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>" id="main-style-link">
        <?php endif; ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>">
    <?php else: ?>
        <?php if(isset($SITE_RTL) && $SITE_RTL == 'on'): ?>
            <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>" id="main-style-link">
        <?php else: ?>   
            <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link">
        <?php endif; ?>  
    <?php endif; ?>
    <?php if(isset($SITE_RTL) && $SITE_RTL == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/custom/auth/css/custom-auth-rtl.css')); ?>" id="main-style-link">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/custom/auth/css/custom-auth.css')); ?>" id="main-style-link">
    <?php endif; ?>  
    <?php if($setting['cust_darklayout']=='on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/custom/auth/css/custom-dark.css')); ?>" id="main-style-link">
    <?php endif; ?>

    <?php if(isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on'): ?>
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/customizer.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/custom_assets/css/custom.css')); ?>">

    <style type="text/css">
        img.navbar-brand-img {
            width: 245px;
            height: 61px;
        }
    </style>
     <link rel="stylesheet" href="<?php echo e(asset('css/custom-color.css')); ?>">
    <style>
        :root {
            --color-customColor: <?= $color ?>;    
        }
    </style>
</head>
<body class=" <?php echo e($themeColor); ?> ">

    <div class="custom-login">
        <div class="login-bg-img">
            <img src="<?php echo e(asset('assets/custom/auth/images/theme-3.svg')); ?>" class="login-bg-1">
            <img src="<?php echo e(asset('assets/custom/auth/images/common.svg')); ?>" class="login-bg-2">
        </div>
        <div class="bg-login bg-primary"></div>
        <div class="custom-login-inner">
            <header class="dash-header">
                <nav class="navbar navbar-expand-md default">
                    <div class="container">
                        <div class="navbar-brand">
                            <a href="#">
                                <img src="<?php echo e($logo . (isset($logos) && !empty($logos) ? $logos : 'logo-dark.png') . '?timestamp=' . time()); ?>" alt="<?php echo e(config('app.name', 'CRMGo Saas')); ?>" alt="logo" loading="lazy" class="logo" width="40px" height="40px">
                            </a>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarlogin">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarlogin">
                            <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                                <?php echo $__env->make('landingpage::layouts.buttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <div class="lang-dropdown-only-desk">
                                    <li class="dropdown dash-h-item drp-language">
                                        <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="drp-text"> <?php echo e(isset(Utility::languages()[$lang]) ? ucFirst(Utility::languages()[$lang]) : 'English'); ?>

                                            </span>
                                        </a>
                                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                                            <?php echo $__env->yieldContent('language'); ?>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main class="custom-wrapper">
                <div class="custom-row">
                    <div class="card">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </main>
            <footer>
                <div class="auth-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <span>&copy; <?php echo e((isset($footer_text)) ? $footer_text :config('app.name', 'Storego Saas')); ?> </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php echo $__env->yieldPushContent('custom-scripts'); ?>

    <script src="<?php echo e(asset('public/custom_assets/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendor-all.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/feather.min.js')); ?>"></script>


    <script>
        feather.replace();
    </script>
    <div class="pct-customizer">
        <script>
            feather.replace();
            var pctoggle = document.querySelector("#pct-toggler");
            if (pctoggle) {
                pctoggle.addEventListener("click", function() {
                    if (
                        !document.querySelector(".pct-customizer").classList.contains("active")
                    ) {
                        document.querySelector(".pct-customizer").classList.add("active");
                    } else {
                        document.querySelector(".pct-customizer").classList.remove("active");
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

        if ($('#cust-darklayout').length > 0) {

            var custdarklayout = document.querySelector("#cust-darklayout");
            custdarklayout.addEventListener("click", function() {
                if (custdarklayout.checked) {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "<?php echo e(asset('/storage/uploads/logo/logo-light.png')); ?>");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "<?php echo e(asset('assets/css/style-dark.css')); ?>");
                } else {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "<?php echo e(asset('/storage/uploads/logo/logo-dark.png')); ?>");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "<?php echo e(asset('assets/css/style.css')); ?>");
                }
            });
        }
            var themescolors = document.querySelectorAll(".themes-color > a");
            var themescolors_length = $(".themes-color > a").length;
            if (themescolors_length > 0) {
                for (var h = 0; h < themescolors.length; h++) {
                    var c = themescolors[h];

                    c.addEventListener("click", function(event) {
                        var targetElement = event.target;
                        if (targetElement.tagName == "SPAN") {
                            targetElement = targetElement.parentNode;
                        }
                        var temp = targetElement.getAttribute("data-value");
                        removeClassByPrefix(document.querySelector("body"), "theme-");
                        document.querySelector("body").classList.add(temp);
                    });
                }
            }

            function removeClassByPrefix(node, prefix) {
                for (let i = 0; i < node.classList.length; i++) {
                    let value = node.classList[i];
                    if (value.startsWith(prefix)) {
                        node.classList.remove(value);
                    }
                }
            }
        </script>
        <?php
            $settings = \App\Models\Utility::settings();
        ?>
        ​ <?php if($settings['enable_cookie'] == 'on'): ?>
            <?php echo $__env->make('layouts.cookie_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
</body>

</html>
<?php /**PATH C:\laragon\www\crm\resources\views/layouts/auth.blade.php ENDPATH**/ ?>