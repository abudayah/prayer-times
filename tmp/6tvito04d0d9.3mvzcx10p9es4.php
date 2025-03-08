<div class="col">
  <div class="card poster-card">
    <div id="rt-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">

      <div class="carousel-indicators">
        <?php $counter=0; foreach (($posters?:[]) as $poster): $counter++; ?>
          <?php if ($counter == 1): ?>
            
              <button type="button" data-bs-target="#rt-carousel" data-bs-slide-to="<?= ($counter - 1) ?>" class="active" aria-current="true" aria-label="Slide <?= ($counter) ?>"></button>
            
            <?php else: ?>
              <button type="button" data-bs-target="#rt-carousel" data-bs-slide-to="<?= ($counter - 1) ?>" aria-label="Slide <?= ($counter) ?>"></button>
            
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <div class="carousel-inner">
        <?php $counter=0; foreach (($posters?:[]) as $poster): $counter++; ?>
          <?php if ($counter == 1): ?>
            
              <div class="carousel-item active" data-bs-interval="20000">
                <img src="<?= ($basePath) ?><?= ($poster['file_path']) ?>" class="d-block" alt="<?= ($poster['name']) ?>">
              </div>
            
            <?php else: ?>
              <div class="carousel-item" data-bs-interval="20000">
                <img src="<?= ($basePath) ?><?= ($poster['file_path']) ?>" class="d-block" alt="<?= ($poster['name']) ?>">
              </div>
            
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>
