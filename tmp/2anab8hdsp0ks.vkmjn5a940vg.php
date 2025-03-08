<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= ($app_title) ?></title>
  <link rel="shortcut icon" href="https://theisbc.ca/sites/default/files/isbc-favicon.png" type="image/png" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap"
    rel="stylesheet">

  <!-- bootstrap -->  
  <link href="/prayer-times/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="/prayer-times/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

  <!-- font awosome -->
  <link rel="stylesheet" href="/prayer-times//vendor/fortawesome/font-awesome/css/all.min.css">
  <script src="/prayer-times/vendor/fortawesome/font-awesome/js/all.min.js"></script>

  <!-- own style sheets -->
  <link rel="stylesheet" href="/prayer-times/assets/css/style.css">

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    setInterval(() => {
      window.location.reload();
    }, 1000 * 60 * 10); // 10 minutes
  </script>
</head>

<body>

  <?php echo $this->render($content,NULL,get_defined_vars(),0); ?>
  
  <?php if ($footer=='yes'): ?>
    <?php echo $this->render('src/views/html/footer.html',NULL,get_defined_vars(),0); ?>
  <?php endif; ?>
  
</body>

</html>