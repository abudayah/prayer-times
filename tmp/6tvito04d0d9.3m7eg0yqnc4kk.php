<div class="col">
  <div class="card poster-card">
    <div id="posterProgress" class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
      <div class="progress-bar" style="width: 0%"></div>
    </div>

    <div id="posterCarousel" class="carousel carousel-fade">

      <div class="carousel-indicators">
        <?php $counter=0; foreach (($posters?:[]) as $poster): $counter++; ?>
          <?php if ($counter == 1): ?>
            
              <button type="button" data-bs-target="#posterCarousel" data-bs-slide-to="<?= ($counter - 1) ?>" class="active" aria-current="true" aria-label="Slide <?= ($counter) ?>"></button>
            
            <?php else: ?>
              <button type="button" data-bs-target="#posterCarousel" data-bs-slide-to="<?= ($counter - 1) ?>" aria-label="Slide <?= ($counter) ?>"></button>
            
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <div class="carousel-inner">
        <?php $counter=0; foreach (($posters?:[]) as $poster): $counter++; ?>
          <?php if ($counter == 1): ?>
            
              <div class="carousel-item active">
                <img src="<?= ($basePath) ?><?= ($poster['file_path']) ?>" class="d-block" alt="<?= ($poster['name']) ?>">
              </div>
            
            <?php else: ?>
              <div class="carousel-item">
                <img src="<?= ($basePath) ?><?= ($poster['file_path']) ?>" class="d-block" alt="<?= ($poster['name']) ?>">
              </div>
            
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<script>
const posterInterval = <?= ($posterInterval) ?> * 1000;
const posterProgress = document.getElementById('posterProgress');
const progressBar = posterProgress.querySelector('.progress-bar');
const posterCarousel = new bootstrap.Carousel(document.getElementById('posterCarousel'));

function startProgressBar() {
  let width = 0;
  const interval = setInterval(() => {
    width += (100 / (posterInterval / 100));
    progressBar.style.width = `${width}%`;

    if (width >= 100) {
      clearInterval(interval);
      progressBar.style.width = '0%';
      width = 0;
      posterCarousel.next(); // Trigger carousel next slide
      startProgressBar();
    }
  }, 100);
}

startProgressBar();
</script>
<style>
#posterProgress{
    position: absolute;
    z-index: 2;
    top: 0;
    width: 100%;
}
#posterProgress, #posterProgress .progress-bar {
  height: 4px;
}
.progress-bar{
  background-color: #63927d;
}
</style>