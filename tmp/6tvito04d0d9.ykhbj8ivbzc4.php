<div class="container-fluid content text-center">
  <div class="row">
    <div class="col-2">
      <?php echo $this->render($LeftSide,NULL,get_defined_vars(),0); ?>
    </div>
    <div class="col-5">
      <?php echo $this->render($Posters,NULL,get_defined_vars(),0); ?>
    </div>
    <div class="col-5">
      <?php echo $this->render($Duaa,NULL,get_defined_vars(),0); ?>
      <br>
      <?php echo $this->render($PrayerTimes,NULL,get_defined_vars(),0); ?>
      <br>
      <?php echo $this->render($SocialMedia,NULL,get_defined_vars(),0); ?>
    </div>
  </div> 
</div>