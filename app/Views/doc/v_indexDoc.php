<div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-9 page-content">
                    <div class="promo-box">
                        <div class="row">
                            <div class="col-md-12 sub-column mb-50 tablet-top-30">
                                <h6 class="fw-medium mb-30">My Documents</h6>
                                <?php if(count($all) > 0): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach($all as $key => $value): ?>
                                            <li class="mb-20"><i class="bi bi-file-earmark-text fs-24 va-middle mr-10 text-primary opc-75"></i><a href="<?=sprintf('%s/see/docs/%s', base_url(), $value->slug_doc)?>" class="va-middle text-link fs-16"><?=sprintf('%s - %s', $value->nomer_doc, $value->judul_doc)?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php else:?>
                                        <p>No document found in here please make something new if you want...</p>
                                <?php endif; ?>
                            </div><!-- / column -->
                        </div><!-- / row -->
                    </div><!-- / promo-box -->
                </div><!-- / column -->

                <div class="col-lg-4 col-xl-3 sidebar">
                    <div class="widget m-y-0 sticky-top top-25">
                        <h6 class="widget-title">Add your docs</h6>
                        <p class="mb-15">If you want to add your docs</p>
                        <a href="<?=base_url('create-doc')?>" class="btn btn-primary btn-block"><i class="fa fa-plus va-middle mr-5"></i> <span class="va-middle">Add Docs</span></a>
                    </div><!-- / widget -->
                </div><!-- / column -->
            </div><!-- / row -->
        </div><!-- / container -->
    </div><!-- / main-container -->
    <div class="spacer">&nbsp;</div>