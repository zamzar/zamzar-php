<section class="section">
    <div class="container">
        <nav class="navbar is-dark" style="box-shadow: 2px 2px 5px #000">
            <div class="navbar-start">
                <div class="navbar-item">
                    <a class="subtitle has-text-weight-bold has-text-white" href="../../index.php">Zamzar SDK Sampler</a>
                </div>
            </div>
            <div class="navbar-end">
                <?php if($_zamzar->hasLastResponse()) { ?>
                    <div class="navbar-item">
                        <span class="tag is-success"><?= $_zamzar->getProductionCreditsRemaining(); ?> Prod Credits</span>
                    </div>
                    <div class="navbar-item">
                        <span class="tag is-success"><?= $_zamzar->getTestCreditsRemaining(); ?> Test Credits</span>
                    </div>
                <?php } ?>
                <div class="navbar-item">
                    <?php if($_environment == 'production') { ?>
                        <span class="tag is-info">Using Production Environment</span>
                    <?php } else {?>
                        <span class="tag is-info">Using Sandbox Environment</span>
                    <?php } ?>
                </div>
                <div class="navbar-item">
                    <a href="<?= $_pf ?>/views/account/account.php"><i class="fas fa-user has-text-light"></i></a>
                </div>
            </div>
        </nav>
    </div>
</section>
