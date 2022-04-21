<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>Signin Template · Bootstrap v5.0</title>

    <link rel="stylesheet" href="<?=base_url('css/login.css')?>">



    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>

<body class="text-center">

<div id="main-wrapper" class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-0">
                    <div class="row no-gutters">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="mb-5">
                                    <h3 class="h4 font-weight-bold text-theme">Register</h3>
                                </div>
                                <h6 class="h5 mb-0">Welcome!</h6>
                                <p class="text-muted mt-2 mb-5">Enter your username and password to register account docs.</p>

                                <form action="<?=base_url('register')?>" method="POST">
                                <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input type="text" autocomplete="off" name="name" class="form-control" id="exampleInputEmail1">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Username</label>
                                        <input type="text" autocomplete="off" name="username" class="form-control" id="exampleInputEmail1">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" autocomplete="off" name="password" class="form-control" id="exampleInputPassword1">
                                    </div>
                                    <div class="form-group mb-5">
                                        <label for="exampleInputPassword1">Confirmation Password</label>
                                        <input type="password" autocomplete="off" name="password_conf" class="form-control" id="exampleInputPassword1">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-md"  style="float: left;margin-bottom: 5em;">Register</button>
                                    <a href="<?=base_url('register')?>" class="forgot-link float-right text-primary">Dont Have Account?</a>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->

            <!-- end row -->

        </div>
        <!-- end col -->
    </div>
    <!-- Row -->
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty(session()->getFlashdata('error'))) : ?>
<script>
    Swal.fire({
        title: 'Error!',
        text: '<?=session()->getFlashdata('error')?>',
        icon: 'error',
        confirmButtonText: 'Cool'
    })
</script>
<?php endif; ?>
</body>

</html>