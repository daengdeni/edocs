    
<div class="footer-wrapper bg-primary dark">
  <footer class="bg-transparent relative">
    <div class="container">
      <div class="row v-center mobile-center">
        <div class="col-lg-4 tablet-lg-text-center tablet-lg-top-30">
          <!-- <img src="assets/images/logo-white.png" class="footer-logo" alt=""> -->
        </div>
        <!-- / footer-left-area -->
        <div class="col-lg-4 footer-center-area text-center tablet-lg-top">
          <a href="#top" class="btn btn-lg btn-icon btn-white btn-circle btn-back-top">
            <i class="bi bi-chevron-up"></i>
          </a>
          <p>© 2022 by <a href="<?=base_url()?>" target="_blank">BUSOL Team</a>
          </p>
        </div>
        <!-- / footer-center-area -->
        <!-- / footer-right-area -->
      </div>
      <!-- / row -->
    </div>
    <!-- / container -->
  </footer>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <!-- Core JavaScript -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/theme.js"></script>

    <!-- aos -->
    <script src="/assets/js/aos.js"></script>
    <!-- prism -->
    <script src="/assets/js/prism.js"></script>
    <!-- / prism -->

    <!-- copy-to-clipboard -->
    <script src="/assets/js/clipboard.min.js"></script>
    <script>
        
        var clipboard = new ClipboardJS('.btn');

        AOS.init({
          duration: 1200,
        })

        function checkMe(p) {
            if (p == '') {
                $('#per').prop('disabled', true)
            } else {
                $('#per').prop('disabled', false)
            }
        }

        $('.ajax-user').select2({
            placeholder: 'Select an item',
            ajax: {
                url: '<?=base_url('ajax-user')?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.value,
                                selected: false
                            }
                        })
                    };
                },
                cache: true
            }
        })

        $('.ajax-doc').select2({
            placeholder: 'Select an item',
            ajax: {
                url: '<?=base_url('ajax-doc')?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.value,
                                selected: false,
                                disabled: item.disabled
                            }
                        })
                    };
                },
                cache: true
            }
        })

        $('.tagging-select2').select2({
            tags: true
        })
        $(document).ready(() => {
            $('input').attr('autocomplete','off')
        })
    </script>
    
    <?php if (session()->has('error')): ?>
      <script>
        Swal.fire({
            title: 'Error!',
            text: '<?=session()->get('error'); ?>',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
      </script>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
      <script>
        Swal.fire({
            title: 'Success!',
            text: '<?=session()->get('success'); ?>',
            icon: 'success',
            confirmButtonText: 'Ok'
        })
      </script>
    <?php endif; ?>
</body>
</html>