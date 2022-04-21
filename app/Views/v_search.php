<!doctype html>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?=$s ?? 'EDOC'?> - Title</title>
    <link rel="stylesheet" href="<?=base_url('css/result.css')?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@13.0.0/dist/material-components-web.min.css">
</head>

<body>
  <!-- header-->
  <header>
    <center class="none-on-desktop"><h1>EDOC</h1></center>
    <div>
      <div class='search-box'>
        <form>
          <input type="search" name="q" value="<?=$s?>" class="search-prompt" autofocus autocomplete="off">
        </form>
      </div>
      <div class="nav-right none-on-465px">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRMG6DsqfFZ-Mlo3ULMOs6CqBqBuSLUT5_OZv82wlZVs_LnHFGgZg" alt="Googleapps" height="16px" width="16px">
        <a href="<?=base_url('logout')?>"><button>Logout</button></a>
      </div>
    </div>
  
  <!-- top nav-->
  <!-- <nav>
      <a href="#home">All</a>
  </nav> -->

    <hr>
    <!-- top nav end-->
  </header>
  <!-- head end-->

  <main>
    <!-- search results -->
    <p class="results-returned">About <?=count($data['doc'])?> results (0.46 seconds)</p>

    <ul>
      <?php foreach($data['doc'] as $key => $value): ?>
      <li style="list-style: none;">
        <h2 class="result-link"><a href="<?=base_url(sprintf('see/docs/%s', $value['slug_doc']))?>"><?=$value['judul_doc']?></a></h2>
        <a href="<?=base_url(sprintf('see/docs/%s', $value['slug_doc']))?>" class="green-link"><?=base_url(sprintf('see/docs/%s', $value['slug_doc']))?></a>
        <span class="down-arrow"></span>
        <p><?=substr($value['summary_doc'], 0, 150)?>...</p>
      </li>
    </ul>
    <?php endforeach; ?>
    <!-- end of search results -->

  </main>
  
  <?php if($data['total_page'] > 1): ?>
    <div class="pagination-container pagination-container--align-center" style="margin-top: 83px;">
    <ul class="pagination">
      <!-- <li>
        <a class="mdc-icon-button mdc-icon-button--disabled">
          <i class="material-icons">keyboard_arrow_left</i>
        </a>
      </li> -->
      <?php for ($i=1; $i <= $data['total_page']; $i++): ?>
      <li aria-current="page" style="cursor: pointer;" onclick="window.location.href='<?=sprintf('%s?q=%s&p=%s', base_url(), $s, urlencode(base64_encode($i)))?>'">
        <a class="mdc-icon-button mdc-icon-button--active" href="<?=sprintf('%s?q=%s&p=%s', base_url(), $s, urlencode(base64_encode($i)))?>">
          <span class="mdc-icon-button__icon"><?=$i++?></span>
        </a>
      </li>
      <?php endfor; ?>
      <!-- <li>
        <a class="mdc-icon-button" href="#">
          <i class="material-icons">keyboard_arrow_right</i>
        </a>
      </li> -->
    </ul>
  </div>
  <?php endif ?>
</body>

</html>