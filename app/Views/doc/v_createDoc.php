<div class="main-container">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-xl-12 page-content">
        <div class="promo-box">
          <div class="row"> <?php if (!empty(session()->getFlashdata('errors'))): ?> <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <h4 class="text-white">Validate Form</h4>
              </hr /> <?php $i = 1; foreach (session()->getFlashdata('errors') as $key => $value) : ?> <?=$i++?>. <?=$value?> <br /> <?php endforeach; ?>
            </div> <?php endif; ?> <?php if (!empty(session()->getFlashdata('error'))): ?> <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <h4 class="text-white">Validate Form</h4>
              </hr /> <?php $i = 1; foreach (session()->getFlashdata('error') as $key => $value) : ?> <?=$i++?>. <?=$value?> <br /> <?php endforeach; ?>
            </div> <?php endif; ?> <div class="container mb-5">
              <div class="row">
                <form action="" method="post" enctype="multipart/form-data"> <?= csrf_field(); ?>
                  <br>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Nomor surat</label>
                      <input type="text" class="form-control" name="nomer_doc">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Judul surat</label>
                      <input type="text" class="form-control" name="judul_doc">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Sumarry surat</label>
                      <textarea type="text" class="form-control" name="summary_doc"></textarea>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Dibuat Tanggal</label>
                      <input type="date" class="form-control" name="dibuat_tanggal">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Periode Mulai</label>
                      <input type="datetime-local" class="form-control" name="periode_mulai">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Periode Berakhir</label>
                      <input type="datetime-local" class="form-control" name="periode_berakhir">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Permintaan persetujuan</label>
                      <select style="width: 100%" class="ajax-user form-control" multiple="multiple" name="ttd[]"></select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Jenis Dokumen</label>
                      <select class="form-control tagging-select2" style="width: 100%" multiple="multiple" name="jenis_doc[]"> <?php foreach($tagging as $key => $value): ?> <option value="
																	<?=$value?>"> <?=$value?> </option> <?php endforeach; ?> </select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Permintaan persetujuan</label>
                      <select style="width: 100%" class="form-control" onchange="checkMe(this.value)" name="option" <?= !$availableDoc ? 'disabled' : '' ?>>
                        <option value=""></option>
                        <option value="membatalkan">Membatalkan Surat</option>
                        <option value="menambahkan">Menambahkan Surat</option>
                        <option value="mengganti">Menggantikan Surat</option>
                        <option value="merujuk">Merujuk Surat</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Dokumen</label>
                      <select style="width: 100%" class="ajax-doc form-control" id="per" multiple="multiple" name="no_surat[]" disabled="disabled"></select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Lampiran</label>
                      <input type="file" id="" class="form-control" name="lampiran">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Soft Copy</label>
                      <input type="file" id="" class="form-control" name="softCop">
                    </div>
                  </div>
                  <div class="row v-center mt-20">
                    <div class="col-12 mb-25">
                      <div class="checkbox checkbox-primary ml-10">
                        <label class="hidden">
                          <input type="checkbox">
                        </label>
                        <input id="checkbox-login2" type="checkbox" name="rahasia">
                        <label for="checkbox-login2">
                          <span>Bersifat Rahasia / Hanya Orang yang bertanda tanggan</span>
                        </label>
                      </div>
                      <!-- / checkbox -->
                    </div>
                    <!-- / column -->
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary btn-block w-100">Create <i class="bi bi-arrow-right fs-14 ml-5 va-middle"></i>
                      </button>
                    </div>
                    <!-- / column -->
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- / row -->
        </div>
        <!-- / promo-box -->
      </div>
      <!-- / column -->
    </div>
    <!-- / row -->
  </div>
  <!-- / container -->
</div>