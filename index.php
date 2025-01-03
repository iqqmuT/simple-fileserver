<?php
function allow_file($filename) {
  if ($filename[0] == '.') {
    return false;
  }
  if ($filename == 'index.php') {
    return false;
  }
  return true;
} 

function try_upload_file() {
  if (isset($_POST['submit'])) {
    $target_file = './' . basename($_FILES['file']['name']);
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
      $errno = $_FILES['file']['error'];
      $error = 'Unknown error.';
      if ($errno === 1) {
        $error = 'File exceeds upload_max_filesize directive of php.ini (' . ini_get('upload_max_filesize') . ')';
      } else if ($errno === 2) {
        $error = 'File exceeds MAX_FILE_SIZE form attribute';
      } else if ($errno === 3) {
        $error = 'Only partially uploaded.';
      }
      die('Sorry, there was an error uploading your file: ' . $error);
    }
  }
}

function try_download_file() {
  if (isset($_GET['download'])) {
    $file = $_GET['download'];
    if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($file) . '"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      readfile($file);
      exit;
    } else {
      http_response_code(404);
      die('Wanted file does not exist.');
    }
  }
}

function scan_files() {
  $files = scandir('.');
  return array_filter($files, 'allow_file');
}

try_download_file();
try_upload_file();
$files = scan_files();

?><!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href=".engine/bootstrap-4.1.3.min.css">

    <title>Files</title>
    <style>
      body { margin-top: 2rem; margin-bottom: 2rem; }
      .card-upload { margin-bottom: 2rem; }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="card card-upload">
        <div class="card-body">
          <div class="card-title"><h4>Upload</h4></div>
          <form method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-6">
                <input type="file" class="form-control-file" id="file-input" name="file" required>
              </div>
              <div class="col-6 text-right">
                <input type="submit" value="Upload" name="submit" class="btn btn-primary">
              </div>
            </div> <!-- .row -->
          </form>
        </div> <!-- .card-body -->
      </div> <!-- .card -->

      <div class="row">
        <div class="col">
          <div class="list-group">
            <?php foreach ($files as $file): ?>
              <a href="?download=<?php echo urlencode($file) ?>" class="list-group-item list-group-item-action">
                <?php echo $file ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div> <!-- .row -->
    </div> <!-- .container -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src=".engine/jquery-3.7.1.slim.min.js"></script>
    <script src=".engine/popper.min.js"></script>
    <script src=".engine/bootstrap-4.1.3.min.js"></script>
  </body>
</html>
