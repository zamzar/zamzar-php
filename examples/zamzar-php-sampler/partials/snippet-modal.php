<div id="snippet" class="modal">
<div class="modal-background"></div>
<div class="modal-card" style="min-width: 800px">
<header class="modal-card-head has-background-dark has-text-white">
<p class="modal-card-title has-text-white">SDK Snippet</p>
<button class="delete" aria-label="close" onclick="document.getElementById('snippet').classList.toggle('is-active');")></button>
</header>
<section class="modal-card-body">
<pre style="border-radius: 0.55em; font-weight: bold; margin-top: -2.5em;">
<code style="font-weight: bold; color: rgb(60,60,60); font-family: Inconsolata, Arial; line-height: 1.8em">

<?php 

switch(basename($_SERVER['PHP_SELF'])) {
    case 'allfiles.php':
?>

    // Retrieve a list of files
    $files = $zamzar->files->all(['limit' => 5]);

    // Iterate and output
    foreach($files->data as $file) {
        echo $file->getId();
        echo $file->getName();
        echo $file->getSize();
        echo $file->getFormat();
        echo $file->getCreatedAt();
    }

<?php 
    break;
    case 'file.php':
?>

    // Retrieve a specific file
    $file = $zamzar->files->get(123456);

    // Output properties
    echo $file->getId();
    echo $file->getName();
    echo $file->getSize();
    echo $file->getFormat();
    echo $file->getCreatedAt();

    // Download the file
    $file->download([
        'download_path' => 'path/to/folder'    
    ]);

    // Delete the file
    $file->delete();

<?php 
    break;
    case 'upload.php':
?>

    // Upload a file using the files object
    $file = $zamzar->files->upload([
        'name' => 'path/to/local/filename.ext'
    ]);

    echo $file->getId();

<?php 
    break;
    case 'allformats.php':
?>

    // Retrieve formats in alphabetical order
    $formats = $zamzar->formats->all(
        ['limit' => 5]
    );

    // Iterate
    foreach($formats->data as $format) {
        // output the target formats for each format
        echo $format->getName() . ' ---> ' . $format->getTargetsToCsv();
    }

<?php 
    break;
    case 'format.php':
?>

    // Retrieve a specific format
    $format = $zamzar->formats->get('jpg');

    // simple comma separated list of valid target formats
    echo $format->getTargetsToCsv();

    // or inspect each target format
    foreach($format->getTargets() as $targetFormat) {
        echo $targetFormat->getName();
        echo $targetFormat->getCreditCost();
    }

<?php 
    break;
    case 'allimports.php':
?>

    // Retrieve a list of imports
    $imports = $zamzar->imports->all(
        ['limit' => 10]
    );

    // Iterate
    foreach($imports->data as $import) {
        echo $import->getId();
        echo $import->getId();
        echo $import->getUrl();
        echo $import->getStatus();
        echo $import->getcreatedAt();
        if($import->hasFile()) {
            echo $import->getFile()->getName();
        }
    }

<?php 
    break;
    case 'import.php':
?>

    // Retrieve a specific import
    $import = $zamzar->imports->get(123456);

    // Output properties
    echo $import->getId();
    echo $import->getUrl();
    echo $import->getStatus();
    echo $import->getcreatedAt();

    // If an import has completed successfully, it will have a file object
    if($import->hasFile()) {
        echo $import->getFile()->getId();
        echo $import->getFile()->getName();
        //etc
    }

<?php 
    break;
    case 'start.php':
?>

    // Start an Import. Optionally provide a filename to override the url file name
    $import = $zamzar->imports->start([
        'url' => 'https://www.zamzar.com/images/zamzar-logo.png',
        'filename' => 'zamzar-logo-new-name.png'
    ]);

    // Get the Import Id
    echo $import->getId();

    // Refresh the Object
    $import->refresh();
    
    // Check if complete
    $import->hasCompleted();

    // Check the Status
    if($import->isStatusSuccessful) {
        //dosomething
    }

<?php 
    break;
    case 'alljobs.php':
?>

    // Retrieve a list of jobs
    $jobs = $zamzar->jobs->all(['limit' => 5]);

    // Iterate and output
    foreach($jobs->data as $job) {
        echo $job->getStatus();
        echo $job->getSandbox();
        echo $job->getCreatedAt();
        echo $job->getFinishedAt();
        echo $job->getSourceFile()->getId();
    }

<?php 
    break;
    case 'job.php':
?>

    // Retrieve a specific job
    $job = $zamzar->jobs->get(123456);

    // Output
    echo $job->getId();
    echo $job->getStatus();
    echo $job->getCreatedAt();
    echo $job->getFinishedAt();
    echo $job->getTargetFormat();
    echo $job->getCreditCost();

    // If a job failed it will have a failure code and message, cast to a string
    if($job->hasFailure()) { 
        echo (string) $job->getFailure();
    }

    // If a job relates to an import, it will have an import object
    if($job->hasImport()) { 
        echo $job->getImport()->getUrl();
    }

    // Check if the source file is present
    if($job->hasSourceFile()) {
        echo $job->getSourceFile()->getName();
    }

    // Check if target(converted) files are ready
    if($job->isStatusSuccessful()) {
        if($job->hasTargetFiles()) {
            foreach($job->getTargetFiles() as $file) {
                echo $file->getName();
                //download converted file
                $file->download([
                    'download_path' => 'path/to/folder'    
                ]);
                // delete the file
                $file-delete();
            }
        }
    }

    // If the converted files were requested to be exported to a remote server
    if($job->getExportUrl() != '') {
        echo $job->getExportUrl();
    }

    if($job->hasExports) {
        foreach($job->getExports() as $export) 
            echo $export->getId();
            echo ' / ' . $export->getUrl();
            echo ' / ' . $export->getStatus();
            echo ' / ' . (string) $export->getFailure();
    }


<?php 
    break;
    case 'submitlocal.php':
?>


    // start a job for a local file
    $job = $zamzar->jobs->submit([
        'source_file' => 'path/to/local/file',
        'target_format' => 'xxx'
    ]);

    // start a job for a remote url
    $job = $zamzar->jobs->submit([
        'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
        'target_format' => 'pdf'
    ]);

    // start a job for a file which already exists on Zamzar's servers
    $job = $zamzar->jobs->submit([
        'source_file' => '123456',
        'target_format' => 'pdf'
    ]);

    // start a job for a S3 file (requires Connected Services to be configured in the developer dashboard)
    $job = $zamzar->jobs->submit([
        'source_file' => 's3://CREDENTIAL_NAME@my-bucket-name/logo.png',
        'target_format' => 'pdf'
    ]);

    echo $job->getId();

    // Cancel
    $job->cancel();

    // Wait
    $job->waitForCompletion();

    // Download Converted files
    $job->downloadTargetFiles();

    // Delete all filesize
    $job->deleteAllFiles();

    // See the Code Samples for a more detailed breakdown


<?php 
    break;
    case 'account.php':
?>

    // get the account 
    $account = $zamzar->account->get();

    // view the direct properties of the account
    echo $account->getProductionCreditsRemaining();
    echo $account->getTestCreditsRemaining();

    // get the plan object which is a child of account and retrieved in account->get()
    $plan = $account->getPlan();
    echo $plan->getPlanName();
    echo $plan->getPricePerMonth();
    echo $plan->getConversionsPerMonth();
    echo $plan->getMaximumFileSize();

<!-- close the switch statement -->
<?php
}
?>

</code>
</pre>
</section>
<footer class="modal-card-foot">
<a href="https://github.com/zamzar/zamzar-php/" target="_blank">View the SDK Code Samples for more information</a>
</footer>
</div>
</div>