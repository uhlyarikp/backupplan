<?php
include_once('header.php');
?>
<title><?=$this->lbl_websitename?></title>

</head>
<body>
<?php
include_once('navigation.php');
?>
<div class="jumbotron text-center bg-custom1 text-white">
  <h1 class="main-title"><?=$this->lbl_websitename?></h1>
  <p class="main-sub-title"><?=$this->lbl_headsubtitle?></p>
</div>

<div class="container">

      <div class="card-deck mb-3 text-center">

        <div class="card mb-4 box-shadow">
          <div class="card-header bg-custom1 text-white">
            <h4 class="my-0 font-weight-normal"><?=$this->lbl_login?></h4>
          </div>
          <div class="card-body d-flex flex-column">
          </div>
        </div>


      </div>

</div>
<?php
include_once('footer.php');
?>
</body>
</html>
