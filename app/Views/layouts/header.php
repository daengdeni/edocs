<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="eDocs - Documentation Template">
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.png">
    <!-- Site Title -->
    <title><?=$title ?? '-'?> - Document</title>
    <!-- Bootstrap 5 Core CSS -->
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?=base_url('assets/css/animate.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/aos.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/prism.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/doc.css')?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Fonts -->
    <link rel="stylesheet" href="/assets/css/fontawesome-all.min.css" type="text/css">
</head>

<body>

    <div id="#top"></div>

    <nav class="navbar navbar-expand-lg navbar-inverse bg-primary absolute top-0 left-auto right-auto w-100">
        <div class="container">
            <a class="navbar-brand" href="index.html"><h3 class="text-white">e-Docs</h3></a>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggle" aria-controls="navbar-toggle" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar top-bar"></span>
                <span class="icon-bar middle-bar"></span>
                <span class="icon-bar bottom-bar"></span>
                <span class="sr-only">Toggle navigation</span>
            </button><!-- / navbar-toggler -->

            <div class="collapse navbar-collapse" id="navbar-toggle">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="<?=base_url()?>"><i class="bi bi-window-sidebar mr-5 fs-14 va-middle"></i> <span class="va-middle">Home</span></a>
                    </li><!-- / dropdown -->
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('/my-doc')?>"><i class="bi bi-file-earmark-text mr-5 fs-14 va-middle"></i> <span class="va-middle">My docs</span></a>
                    </li><!-- / dropdown -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('/my-notif')?>"><i class="fa fa-bell mr-5 fs-14 va-middle"></i> <span class="va-middle">Notification (<?=$countNotif?>)</span></a>
                    </li><!-- / dropdown -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('/trash')?>"><i class="fa fa-trash mr-5 fs-14 va-middle"></i> <span class="va-middle">Trash (<?=$countTrash?>)</span></a>
                    </li><!-- / dropdown -->

                    <li class="nav-item">
                        <a class="nav-link opc-100" href="<?=base_url('logout')?>"><i class="bi bi-person-circle mr-5 fs-14 va-middle bl-1 border-white pl-50"></i> <span class="va-middle">Logout</span></a>
                    </li><!-- / nav-item -->
                </ul><!-- / navbar-nav -->

                <!-- <ul class="navbar-button p-0 m-0 ml-5">
                    <li class="nav-item">
                        <a href="support.html" class="btn btn-sm btn-white"><i class="bi bi-life-preserver fs-14 mr-5 text-primary va-middle"></i> <span>Support</span></a>
                    </li>
                </ul> -->
            </div><!-- / navbar-collapse -->
        </div><!-- / container-fluid -->
    </nav><!-- / navbar -->