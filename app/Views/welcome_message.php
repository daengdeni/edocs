<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link href="<?=base_url('css/index.css')?>" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/prism.css">
    <link rel="stylesheet" href="/assets/css/doc.css">
    <meta name="robots" content="noindex, follow">
    <title>Search Dokumen</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-inverse absolute top-0 left-auto right-auto w-100" id="mobile">
        <div class="container">
            <a class="navbar-brand" href="index.html"><img src="assets/images/logo-white.png" alt=""></a>
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
    <div class="s006">
      <form method="GET" action="">
        <fieldset>
          <legend>What are you looking for?</legend>
          <div class="inner-form">
            <div class="input-field">
              <button class="btn-search" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                </svg>
              </button>
              <input id="search" type="text" placeholder="Search something" autocomplete="off" name="q" value="" />
            </div>
          </div>
          <div class="suggestion" style="display: none;"></div>
        </fieldset>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                callback.apply(context, args);
                }, ms || 0);
            };
        }

        $(document).ready(function() {
            $('#search').keydown(delay(function (e) {
                $('.suggestion').fadeIn()
                const data = `<center><span><i class="fa-solid fa-sync fa-spin"></i> Searching data...</span></center>`
                $('.suggestion').html(data)
                if (this.value != '') {
                    $.ajax({
                        method: 'GET',
                        url: `<?=base_url('ajax-doc')?>?q=${this.value}`
                    }).then((res) => {
                        const json = JSON.parse(res)
                        if (res == '[]') return $('.suggestion').fadeOut();
                        let data = '';
                        json.forEach(element => {
                            data += `
                            <p onclick="clickAku('${element.slug}')">
                                <span>
                                    <i class="fa fa-search" style="padding-right: 15px;"></i>
                                    ${element.text}
                                </span>
                            </p>`
                        });
                        $('.suggestion').css('display','block')
                        $('.suggestion').html(data)
                    })
                } else {
                    $('.suggestion').fadeOut()
                }
            }, 500));
            $('.suggestion p').hover(function () {
                $(this).addClass('active');
            }, function () {
                $(this).removeClass('active');
            });
                
        });

        const clickAku = s => {
            window.location.href = `/see/docs/${s}`
        }

        $('.navbar-toggler').click(() => {
          if ($('#mobile').hasClass('h-100aja')) {
            $('#mobile').removeClass('h-100aja')
          } else {
            $('#mobile').addClass('h-100aja')
          }
        })
    </script>
    <?php if (session()->has('error')): ?>
      <script>
        Swal.fire({
            title: 'Error!',
            text: '<?=session()->get('error'); ?>',
            icon: 'error',
            confirmButtonText: 'Cool'
        })
      </script>
    <?php endif; ?>
  </body>
</html>