<div class="card">
    <div class="card-body">
        <div class="row align-items-end">
            <div class=" col-4">
                <img class="img-fluid" src="https://theisbc.ca/sites/default/files/isbc-logo.png" alt="Home">
            </div>
            <div class="col">
                <h1 class="text-end">Prayers Times</h1>
            </div>
        </div>
    </div>
    <div id="table-wraper" style="padding-bottom: 3px;">
        <?php foreach (($ds?:[]) as $key=>$datum): ?>
            <table id="prayers_table" style="margin-bottom: 0px;" class="table table-borderless align-middle">
                <?php if ($key==0): ?>
                    
                        <thead class="fs-5 fw-semibod text-center">
                    
                    <?php else: ?>
                        <thead class="fw-semibod text-center">
                    
                <?php endif; ?>
                <tr class="table-light border-top border-bottom">
                    <th colspan="7" class="text-center">
                        <h4 class="d-inline-flex"><?= ($datum['DateG']) ?>&nbsp;-&nbsp;<?= ($datum['DateH']) ?></h4>
                        <?php if ($key==0): ?>
                            
                                <div id="digital-clock" class='time d-inline-flex'>
                                    
                                    <a id='dig-clock' href='#' class='btn btn-lg btn-secondary' role='button'>
                                        🕗&nbsp;
                                        <span id='hour'>00</span>:
                                        <span id='min'>00</span>
                                        <span id='sec' class="visually-hidden">00</span>
                                        <span id='period'>AM</span></a>
                                </div>
                            
                        <?php endif; ?>
                    </th>
                </tr>
                <tr class='prayers-label'>
                    <th></th>
                    <?php foreach (($datum['prayers_eng_names']?:[]) as $i=>$v): ?>
                        <?php if ($key==0 && $datum['active_prayer_id']==$i): ?>
                            
                                <th class="table-light border-start border-end">
                                    <?= ($v) ?>&nbsp;<span class="amiri-regular"><?= ($datum['prayers_ara_names'][$i]) ?></span>
                                </th>
                            
                            <?php else: ?>
                                <th><?= ($v) ?>&nbsp;<span class="amiri-regular"><?= ($datum['prayers_ara_names'][$i]) ?></span>
                                </th>
                            
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                    <tr class="fs-5 text-center azan-time-row">
                        <th class='azan-label'>Azan&nbsp;<span class='amiri-regular'>أذان</span></th>
                        <?php foreach (($datum['AzanColNames']?:[]) as $i=>$AzanColName): ?>
                            <?php if ($key==0 && $datum['active_prayer_id']==$i): ?>
                                
                                    <td class="table-light border-start border-end"><?= ($datum['calls'][$AzanColName])."
" ?>
                                    </td>
                                
                                <?php else: ?>
                                    <td><?= ($datum['calls'][$AzanColName]) ?></td>
                                
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="iqama-time-row fs-5 text-center">
                        <th>Iqama&nbsp;<span class='amiri-regular'>إقامة</span></th>
                        <?php foreach (($datum['IqamaColNames']?:[]) as $i=>$IqamaColName): ?>
                            <?php if ($key==0 && $datum['active_prayer_id']==$i): ?>
                                
                                    <?php if ($i!=1): ?>
                                        
                                            <td class="table-light border-start border-end"><?= ($datum['calls'][$IqamaColName])."
" ?>
                                            </td>
                                        
                                        <?php else: ?>
                                            <td class="table-light border-start border-end"></td>
                                        
                                    <?php endif; ?>
                                
                                <?php else: ?>
                                    <?php if ($i!=1): ?>
                                        
                                            <td><?= ($datum['calls'][$IqamaColName]) ?></td>
                                        
                                        <?php else: ?>
                                            <td></td>
                                        
                                    <?php endif; ?>
                                
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="border-top" style="vertical-align: middle; clear: both;">
                            <h5 class='current-prayer-msg'>
                                <div style="display:inline-flex;">
                                    <?php if ($key==0): ?><?= ($datum['active_prayer_alert']) ?><?php endif; ?>
                                    <?php if ($key==1): ?><?= ($ds[0]['friday_prayer_alert']) ?><?php endif; ?>
                                </div>
                                <?php if ($key==0): ?>
                                    <div id="timer-wrap" style="display:inline-flex;">
                                        <div class="timer" data-minutes-left="<?= ($datum['active_prayer_timer']) ?>">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </h5>
                        </td>
                    </tr>
                </tfoot>
            </table>
        <?php endforeach; ?>
    </div>
</div>
<script src="/prayer-times/assets/js/jquery-simple-timer.js"></script>
<script src="/prayer-times/assets/js/script.js"></script>