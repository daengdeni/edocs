<div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-xl-12 page-content">
                    <div class="promo-box">
                        <div class="row">
                            <div class="col-md-12 sub-column mb-50 tablet-top-30">
                                <h6 class="fw-medium mb-30">Documents</h6>
                                <?php if(count($notif) > 0): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach($notif as $key => $value): ?>
                                            <li class="mb-20"><i class="bi bi-file-earmark-text fs-24 va-middle mr-10 text-primary opc-75"></i><a href="<?=sprintf('%s/see/docs/%s', base_url(), $value->slug_doc)?>" class="va-middle text-link fs-16"><?=sprintf('%s - %s', $value->nomer_doc, $value->judul_doc)?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php else:?>
                                        <p>No document found in here</p>
                                <?php endif; ?>
                            </div><!-- / column -->
                        </div><!-- / row -->
                    </div><!-- / promo-box -->
                </div><!-- / column -->
            </div><!-- / row -->
        </div><!-- / container -->
    </div><!-- / main-container -->
    <div class="spacer">&nbsp;</div>