// file content from zamzar will be stored in this folder
var downloadFolder = "/files/downloads/";

// loader (adds event listeners)
function loader()
{

  // Select input type file and store it in a variable
  const input = document.getElementById('fileupload');
  
  // Event handler executed when a file is selected
  const onSelectFile = () => {

    // get the filename and extension
    filename = input.files[0].name;
    extension = filename.substr(filename.lastIndexOf('.') + 1);
    document.getElementById("filename").innerText = filename;

    // if there is a targetformats control on this pave, then get the target conversion formats for the selected file
    const targetFormats = document.getElementById('targetformats');
    if(targetFormats !== null) {

      // get the valid conversion formats and populate the dropdown
      fetch('/actions/getconversionformats.php?sourceformat=' + extension)
      .then(response => response.json())
      .then(json => {
        var html = '<select>';
        for (var i = 0; i < json.length; i++){
          html += '<option>' + json[i].name + '</option>';
        }
        html += '</select>';
        document.getElementById('targetformats').innerHTML = html;
      });

    }
  }

  // Add a listener on input to execute the above event handler
  if(input !== null) {
    input.addEventListener('change', onSelectFile, false);
  }
  
  // Add event listener for checkbox all
  const check = document.getElementById('check_all');
  const onCheckAll = () => toggleCheckboxes();
  if(check !== null) {
    check.addEventListener('change', onCheckAll, false);
  }

}

// toggle checkboxes
function toggleCheckboxes() 
{
  checked = document.getElementById("check_all").checked;
  var checkboxes = document.getElementsByTagName('input');
  for (let i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].type == 'checkbox') {
      checkboxes[i].checked = checked;
    }
  }
}

// delete multiple files
function deleteFiles() 
{
  var checkboxes = document.getElementsByTagName('input');
  var totalDeleted = 0;
  for (let i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].type == 'checkbox') {
      if(checkboxes[i].checked == true) {
        fileid = checkboxes[i].dataset.fileid;
        if(fileid > 0) {
          totalDeleted += 1;
          deleteFile(fileid);
        }
        checkboxes[i].checked = false;
      }
    }
  }
  displayProgress('Deleting ' + totalDeleted + ' file(s)');
}

// convert a local file
function convertFileLocal()
{

  // get params
  targetFormats = document.getElementById("targetformats");
  const targetFormat = targetFormats.options[targetFormats.selectedIndex].text;
  const sourceFormat = document.getElementById("sourceformat").value;
  const exportUrl = document.getElementById("exporturl").value;
  const waitForJob = document.getElementById("optionwait").checked;
  const downloadFiles = document.getElementById("optiondownload").checked;
  const deleteFiles = document.getElementById("optiondelete").checked;

  // display progress
  displayProgress('Initiating Job(s)');

  // set url
  const url = "/actions/submitlocal.php?targetformat=" + targetFormat + '&sourceformat=' + sourceFormat + '&exporturl=' + exportUrl + '&waitforjob=' + waitForJob; 

  // get the files and apply params per file
  const files = document.querySelector('[type=file]').files;
  for (let i = 0; i < files.length; i++) {

    const formData = new FormData();
    let file = files[i];
    formData.append('files[]', file);

    // change the class of the button before we submit
    document.getElementById("submit").classList.add("is-loading");

    // get the valid conversion formats and populate the dropdown
    fetch(url, {
      method: 'POST',
      body: formData,
    }).then(response => response.json())
    .then(json => {
    
      // iterate through jobs
      for (var i = 0; i < json.length; i++){
        
        // add a notification
        const job = json[i];
        displayProgress('Job ' + job.id + '; Status=' + job.status.toUpperCase());

        // download files ?
        if(downloadFiles) {
          if(job.status = "successful") {
    
            // iterate through target files and download and optionally delete
            for (var i = 0; i < job.target_files.length; i++){
              const fileid = job.target_files[i].id;
              const filename = job.target_files[i].name;
              getFileContent(fileid);
              if(deleteFiles) {
                deleteFile(fileid);
              }
            }
    
            // delete source file
            const sourceFileId = job.source_file.id;
            deleteFile(sourceFileId);

          }
        }
      }
      document.getElementById("submit").classList.remove("is-loading");
    });
  }
}

// upload a file
function uploadFile()
{
  const files = document.querySelector('[type=file]').files;
  const formData = new FormData();

  for (let i = 0; i < files.length; i++) {
    let file = files[i];
    formData.append('files[]', file);
  }

  fetch("/actions/uploadfile.php", {
    method: 'POST',
    body: formData,
  }).then(response => response.json())
    .then(data => addFileLinkNotification(data.id, 'File uploaded successfully'));

}

// cancel a job
function cancelJob(jobid) 
{
  fetch('/actions/canceljob.php?jobid=' + jobid)
    .then(response => response.json())
    .then(data => addNotification(data.status, "Cancellation request issued", "Unable to cancel job"));
}

// import a file 
function importFile() 
{
  var url = document.getElementById('importurl').value;
  var filename = document.getElementById('imporfilename').value;
  fetch('/actions/importfile.php?url=' + url + '&filename=' + filename)
    .then(response => response.json())
    .then(data => addImportLinkNotification(data.id, "Import Started successfully"));
}

// delete file from Zamzar 
function deleteFile(fileid) 
{
  fetch('/actions/deletefile.php?fileid=' + fileid)
    .then(response => response.json())
    .then(data => {
      displayProgress('File ' + fileid + ' deleted');
    })
}

// download from Zamzar then download to browser
function getFileContent(fileid) 
{
  fetch('/actions/getfilecontent.php?fileid=' + fileid)
    .then(response => response.json())
    .then(data => window.downloadFile(downloadFolder + data.filename));
}

// creates a new link to click or default to window.open
window.downloadFile = function(sUrl) {
  if (window.downloadFile.isChrome || window.downloadFile.isSafari) {
      var link = document.createElement('a');
      link.href = sUrl;
      if (link.download !== undefined){
          var fileName = sUrl.substring(sUrl.lastIndexOf('/') + 1, sUrl.length);
          link.download = fileName;
      }
      if (document.createEvent) {
          var e = document.createEvent('MouseEvents');
          e.initEvent('click' ,true ,true);
          link.dispatchEvent(e);
          return true;
      }
  }
  window.open(sUrl + query);
}
window.downloadFile.isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
window.downloadFile.isSafari = navigator.userAgent.toLowerCase().indexOf('safari') > -1;

// Add job link notification
function addJobLinkNotification(jobid, msg) {
  if(jobid > 0) {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-info'>" + msg + " <a href='/views/jobs/job.php?id=" + jobid + "'>" + jobid + "</a></div>";
  } else {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-danger'>Issue with job</div>";
  }
}

// Add import link notification
function addImportLinkNotification(importid, msg) {
  if(importid > 0) {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-info'>" + msg + " <a href='/views/imports/import.php?id=" + importid + "'>" + importid + "</a></div>";
  } else {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-danger'>Import failed to start</div>";
  }
}

// Add file link notification
function addFileLinkNotification(fileid, msg) {
  if(fileid > 0) {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-info'>" + msg + " <a href='/views/files/file.php?id=" + fileid + "'>" + fileid + "</a></div>";
  } else {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-danger'>File upload failed</div>";
  }
}

// add a general notification to the notification div
function addNotification(status, successNotification, failureNotification)
{
  if(status == 'ok') {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-info'>" + successNotification + "</div>";
  } else {
    document.getElementById("notification").innerHTML += "<div class='notification is-light is-danger'>" + failureNotification + "</div>";
  }
}

// progress modal
function displayProgress(status)
{
  
  // bind doc
  var doc = document.getElementById.bind(document);
  
  // show the modal if it isn't displayed
  if (!doc('progress-modal').classList.contains('is-active')) {
    doc('progress-modal').classList.toggle('is-active');
  }

  // add the status to the body
  doc('progress-body').insertAdjacentHTML('beforeend', '<br/><i class="fas fa-caret-right"></i> ' + status);

}
