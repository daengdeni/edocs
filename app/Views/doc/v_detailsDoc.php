<div class="main-container">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-xl-<?=$owner && $editable || isset($dataAll['persetujuan']) ? '9' : '12'?> page-content">
        <div class="promo-box">
          <div id="Summary" class="doc-wrapper">
            <h6 class="doc-title">Summary <?=!$isBerlaku ? '<b>(Tidak berlaku)</b>' : ''?><a href="#Summary">
                <i class="fas fa-hashtag"></i>
              </a>
            </h6>
            <p><?=$summary_doc?></p>
            <?php if(isset($dataAll['status_docs_kebalikan']) && $isDone && $validDocStatus): ?>
            <hr>
              <p class="mb-0">Dokumen ini sudah <?=$dataAll['status_docs_kebalikan'][0]['status']?> oleh 
                <?php foreach($dataAll['status_docs_kebalikan'] as $key => $stat): ?>
                  <a href="<?=base_url("see/docs/{$stat['slug_doc']}")?>"><?=sprintf('%s - %s', $stat['nomer_doc'], $stat['judul_doc'])?></a>,
                <?php endforeach; ?>
                <?php if(isset($dataAll['status_docs_kebalikan'][0]['periode_mulai'])): ?>
                  <br>Mulai <?=date('d M  Y H:i:s', strtotime($dataAll['status_docs_kebalikan'][0]['periode_mulai']))?>
                <?php endif; ?>
                <?php if(isset($dataAll['status_docs_kebalikan'][0]['periode_berakhir']) && $dataAll['status_docs_kebalikan'][0]['periode_berakhir'] !== '0000-00-00 00:00:00'): ?>
                  sampai <?=date('d M  Y H:i:s', strtotime($dataAll['status_docs_kebalikan'][0]['periode_berakhir']))?>
                  <?php else: ?>
                  sampai waktu yang tidak ditentukan
                <?php endif; ?>
              </p>
            <?php endif; ?>
            <?php if(isset($dataAll['status_docs']) && $isDone): ?>
            <hr>
              <p class="mb-0">Dokumen ini <?=$dataAll['status_docs'][0]['status']?> surat 
                <?php foreach($dataAll['status_docs'] as $key => $stat): ?>
                  <a href="<?=base_url("see/docs/{$stat['slug_doc']}")?>"><?=sprintf('%s - %s', $stat['nomer_doc'], $stat['judul_doc'])?></a>,
                <?php endforeach; ?>

                
              </p>
            <hr>
            <?php endif; ?>
          </div>
          <div class="spacer">&nbsp;</div>
          <?php if(isset($jenis)): ?>
            <?php foreach($jenis as $key => $v): ?>
                <span class="tagging"><?=$v['jenis']?></span>
            <?php endforeach; ?>
          <?php endif;?>
          <div class="spacer">&nbsp;</div>
          <div id="file-structure" class="doc-wrapper">
            <h6 class="doc-title">File <a href="#file-structure">
                <i class="fas fa-hashtag"></i>
              </a>
            </h6>
            <p>*Click on the folder ( <i class="fas fa-folder text-primary fs-18 opc-40"></i>) icons to open / close them. </p>
            <div class="file-structure-wrapper">
              <p class="parent-folder">
                <a data-bs-toggle="collapse" data-parent="#accordion" href="#collapse-folder" aria-expanded="true" aria-controls="collapse-folder" class="folder-wrap">
                  <i class="fas fa-folder"></i>
                  <span class="folder-text">Docs</span>
                </a>
              </p>
              <!-- / parent-folder -->
              <div id="collapse-folder" class="collapsed show" role="tabpanel" aria-labelledby="collapse-folder">
                <p class="sub-folders">
                  <a data-bs-toggle="collapse" data-parent="#accordion" href="#collapse-css" aria-expanded="true" aria-controls="collapse-css" class="collapsed folder-wrap">
                    <i class="fas fa-folder"></i>
                    <span class="folder-text">Lampiran</span>
                  </a>
                </p>
                <!-- / sub-folders -->
                <div id="collapse-css" class="collapse" role="tabpanel" aria-labelledby="collapse-css">
                  <ul class="file-structure-list second-level">
                      
                    <?php foreach($dataAll['lampiran'] as $key => $val): ?>
                        <li class="files-list single-file">
                            <i class="bi bi-file-earmark-text text-grey"></i>
                            <span class="va-bottom"><a href="<?=$val['file']?>" target="_blank" rel="noopener noreferrer"><?=str_replace(' ', '-',$val['username'])?>-<?=str_replace(sprintf('%s/assets/uploads/', base_url()), '', $val['file'])?></a></span>
                        </li>
                    <?php endforeach; ?>
                  </ul>
                  <!-- / file-structure-sub -->
                </div>
                <p class="sub-folders">
                  <a data-bs-toggle="collapse" data-parent="#accordion" href="#collapse-soft" aria-expanded="true" aria-controls="collapse-soft" class="collapsed folder-wrap">
                    <i class="fas fa-folder"></i>
                    <span class="folder-text">Softcopy</span>
                  </a>
                </p>
                <!-- / sub-folders -->
                <div id="collapse-soft" class="collapse" role="tabpanel" aria-labelledby="collapse-css">
                  <ul class="file-structure-list second-level">
                      
                  <?php if(isset($dataAll['softcopy'])): ?>
                    <?php foreach($dataAll['softcopy'] as $key => $val): ?>
                        <li class="files-list single-file">
                            <i class="bi bi-file-earmark-text text-grey"></i>
                            <span class="va-bottom"><a href="<?=$val['file']?>" target="_blank" rel="noopener noreferrer"><?=str_replace(' ', '-',$val['username'])?>-<?=str_replace(sprintf('%s/assets/uploads/', base_url()), '', $val['file'])?></a></span>
                        </li>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  </ul>
                  <!-- / file-structure-sub -->
                </div>
              </div>
              <!-- / collapse -->
              <!-- / file-structure-sub -->
            </div>
            <!-- / file-structure-wrapper -->
          </div>
          <!-- doc-wrapper -->
          <!-- / promo-box -->
        </div>
        <!-- / promo-box -->
      </div>
      <!-- / column -->
      <?php if($owner && !is_null($deleted_at) || $owner && $editable || isset($dataAll['persetujuan'])) : ?>
      <div class="col-lg-4 col-xl-3 sidebar">
        <div class="widget m-y-0 sticky-top top-25">
          <h6 class="widget-title">Action</h6>
          <ul class="list-unstyled mb-0">
            <?php if(isset($dataAll['persetujuan'])): ?>
              <?php foreach($dataAll['persetujuan'] as $key => $da): ?>
                  <li class="mb-20">
                      <img src="https://www.jobstreet.co.id/en/cms/employer/wp-content/plugins/all-in-one-seo-pack/images/default-user-image.png" class="circle mr-2" alt="" style="height: 30px;width: 30px;">
                      <a href="" class="va-middle text-link"><?=$da['username']?> 
                      <?= (!$da['status']) ? '<span style="color: red">(<i class="fa fa-times"></i>)</span>' : '<span style="color: green">(<i class="fa fa-check"></i>)</span>' ?>
                      </a>
                  </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
          <?php if($availableForThisDocs):?>
            <div class="mb-15">&nbsp;</div>
            <a href="#" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target=".signature">
              <i class="fa fa-edit va-middle mr-5"></i>
              <span class="va-middle">Assign</span>
            </a>
          <?php endif; ?>
          <?php if(!$isBerlaku && $owner || $owner && is_null($deleted_at) && !$isDone): ?>
            <a href="<?=base_url("edit/docs/{$slug_doc}")?>" class="btn btn-success btn-block mb-5 mt-5">
              <i class="fa fa-edit va-middle mr-5"></i>
              <span class="va-middle">Revision</span>
            </a>
          <?php endif; ?>
          <?php if(is_null($deleted_at) && $owner && !$isDone): ?>
            <a href="#" class="btn btn-danger btn-block mb-5" data-bs-toggle="modal" data-bs-target=".destroy-option">
              <i class="fa fa-trash va-middle mr-5"></i>
              <span class="va-middle">Destroy</span>
            </a>
          <?php endif; ?>
          <?php if(!is_null($deleted_at) && $owner): ?>
            <a href="<?=base_url("rollback/docs/{$slug_doc}")?>" class="btn btn-warning btn-block mb-5">
              <i class="fa fa-undo va-middle mr-5"></i>
              <span class="va-middle">Rollback</span>
            </a>
            <a href="#" class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target=".destroy-sure">
              <i class="fa fa-trash va-middle mr-5"></i>
              <span class="va-middle">Destroy</span>
            </a>
          <?php endif; ?>
        </div>
        <!-- / widget -->
      </div>
      <?php endif; ?>
      <!-- / column -->
    </div>
    <!-- / row -->
  </div>
  <!-- / container -->
</div>
<!-- / main-container -->
<!-- / footer-wrapper -->
<!-- login-modal -->
<?php if($availableForThisDocs && !$isDone): ?>
<div class="modal fade signature" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header pt-40">
        <h6 class="modal-title text-center w-100">
          <span>Document signature</span>
        </h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- / modal-header -->
      <div class="modal-body p-y-40">
        <form class="needs-validation" id="login-form3" novalidate="novalidate" enctype="multipart/form-data" action="<?=sprintf('%s/assign/docs/%s', base_url(), $slug_doc)?>" method="POST">
          <?= csrf_field() ?>
          <div class="form-group">
              <label for="">Lampiran *</label>
            <input type="file" class="form-control" name="lampiran" required="" required>
          </div>
          <div class="form-group">
              <label for="">Softcopy</label>
            <input type="file" class="form-control" name="softCop">
          </div>
          <div class="row v-center mt-20">
            <div class="col-12 mb-25">
              <div class="checkbox checkbox-primary ml-10">
                <label class="hidden">
                  <input type="checkbox">
                </label>
                <input id="checkbox-login2" type="checkbox" required>
                <label for="checkbox-login2">
                  <span>Aggre all</span>
                </label>
              </div>
              <!-- / checkbox -->
            </div>
            <!-- / column -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block w-100">Assign <i class="bi bi-arrow-right fs-14 ml-5 va-middle"></i>
              </button>
            </div>
            <!-- / column -->
          </div>
          <!-- / row -->
        </form>
      </div>
      <!-- / modal-footer -->
    </div>
    <!-- / modal-content -->
  </div>
  <!-- / modal-dialog -->
</div>
<?php endif; ?>

<?php if($editable && !$isDone): ?>
<div class="modal fade destroy-option" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header pt-40">
        <h6 class="modal-title text-center w-100">
          <span>Are you sure about it?</span>
        </h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- / modal-header -->
      <div class="modal-body p-y-40">
            <!-- / column -->
        <form action="<?=base_url("destroy/docs/{$slug_doc}")?>" method="get">
          <div class="row v-center mt-20">
            <div class="col-12 mb-25">
              <div class="checkbox checkbox-primary ml-10">
                <label class="hidden">
                  <input type="checkbox">
                </label>
                <input id="checkbox-login3" type="checkbox" name="trash" checked>
                <label for="checkbox-login3" style="cursor: pointer;">
                  <span>Move to trash</span>
                </label>
              </div>
              <!-- / checkbox -->
            </div>
            <div class="row">
              <div class="col-6">
                <button  data-bs-dismiss="modal" aria-label="Close" class="btn btn-warning btn-block">Cancel<i class="bi bi-arrow-right fs-14 ml-5 va-middle"></i>
                </button>
              </div>
              <div class="col-6">
                <button type="submit" class="btn btn-danger btn-block">Yes<i class="bi bi-arrow-right fs-14 ml-5 va-middle"></i>
                </button>
              </div>
            </div>
          </form>
      </div>
      <!-- / modal-footer -->
    </div>
    <!-- / modal-content -->
  </div>
  <!-- / modal-dialog -->
</div>
</div>
<?php endif; ?>


<?php if(!is_null($deleted_at) && $owner && !$isDone): ?>
  <div class="modal fade destroy-sure" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header pt-40">
        <h6 class="modal-title text-center w-100">
          <span>Are you sure about it?</span>
        </h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- / modal-header -->
      <div class="modal-body p-y-40">
            <!-- / column -->
        <form action="<?=base_url("destroy/docs/{$slug_doc}")?>" method="get">
            <div class="row">
              <div class="col-6">
                <button  data-bs-dismiss="modal" aria-label="Close" class="btn btn-warning btn-block">Cancel<i class="bi bi-arrow-right fs-14 ml-5 va-middle"></i>
                </button>
              </div>
              <div class="col-6">
                <button type="submit" class="btn btn-danger btn-block">Yes<i class="bi bi-arrow-right fs-14 ml-5 va-middle"></i>
                </button>
              </div>
            </div>
          </form>
      </div>
      <!-- / modal-footer -->
    </div>
    <!-- / modal-content -->
  </div>
  <!-- / modal-dialog -->
</div>
</div>
<?php endif;?>
<!-- / modal -->
<!-- / login-modal -->