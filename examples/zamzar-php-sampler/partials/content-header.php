<section>

    <div class="container">
        
        <div class="columns is-12">

            <div class="column is-one-quarter">
        
                <aside class="menu ">
                    <p class="menu-label">
                        <i class="far fa-file-alt has-text-link"></i> Files
                    </p>
                    <ul class="menu-list">
                        <li><a href="<?= $_pf ?>/views/files/allfiles.php">All Files</a></li>
                        <li><a href="<?= $_pf ?>/views/files/upload.php">Upload a File</a></li>
                    </ul>
                    <p class="menu-label">
                        <i class="far fa-file-pdf has-text-link"></i> Formats
                    </p>
                    <ul class="menu-list">
                        <li><a href="<?= $_pf ?>/views/formats/allformats.php">All Formats</a></li>
                    </ul>
                    <p class="menu-label">
                        <i class="fas fa-file-import has-text-link"></i> Imports
                    </p>
                    <ul class="menu-list">
                        <li><a href="<?= $_pf ?>/views/imports/allimports.php">All Imports</a></li>
                        <li><a href="<?= $_pf ?>/views/imports/start.php">Start an Import</a></li>
                    </ul>
                    <p class="menu-label">
                    <i class="far fa-paper-plane has-text-link"></i> Jobs
                    </p>
                    <ul class="menu-list">
                        <li><a href="<?= $_pf ?>/views/jobs/alljobs.php">All Jobs</a></li>
                        <li><a href="<?= $_pf ?>/views/jobs/submitlocal.php">Submit a Job</a></li>
                    </ul>
                </aside>

            </div>

            <div class="column is-three-quarters">
                <div class="content">

                    <?php require 'snippet-button.php'; ?>
    