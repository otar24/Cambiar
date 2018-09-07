<div class="u_post_data">
    <div class="panel-wrap">
        <ul class="data_tabs u-tabs">
            <?php
            $i = 0; foreach ($tabs as $tab_id => $tab){
            ?>
            <li class="<?php echo $i === 0 ? 'active' : ''; ?>">
                <a href="#utheme-strategy-<?php echo $tab_id; ?>">
                    <span><?php echo $tab['title']; ?></span>
                </a>
            </li>
            <?php $i++; } ?>
        </ul>
        <?php
        $i = 0; foreach ($tabs as $tab_id => $tab){
            ?>
            <div id="utheme-strategy-<?php echo $tab_id; ?>" class="panel u_options_panel" style="<?php echo $i === 0 ? 'display: block;' : 'display: none;'; ?>">
                <?php include $tab['file']; ?>
            </div>
            <?php $i++; } ?>
    </div>
</div>