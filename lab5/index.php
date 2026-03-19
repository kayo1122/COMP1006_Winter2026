<?php
// folder where uploaded images get saved
$uploadDir   = __DIR__ . '/uploads/';
$uploadWeb   = 'uploads/';
$maxBytes    = 5 * 1024 * 1024; // 5 MB max
$allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedExt  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// variables to track what happened
$uploaded   = false;
$error      = '';
$savedPath = '';
$savedName = '';
$origName  = '';
$fileSize  = 0;
$mimeType  = '';

// only run this if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // check if a file was actually sent
    if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Please select an image file before uploading.';

    } elseif ($_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
        // php error codes for when something goes wrong during upload
        $php_err = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds the server upload_max_filesize limit.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds the form MAX_FILE_SIZE limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded — try again.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server is missing a temporary upload folder.',
            UPLOAD_ERR_CANT_WRITE => 'Server could not write the file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the upload.',
        ];
        $code  = $_FILES['profile_pic']['error'];
        $error = $phpErr[$code] ?? "Upload error (code $code).";

    } else {
        // grab the file info from $_FILES
        $tmp       = $_FILES['profile_pic']['tmp_name'];
        $origName = $_FILES['profile_pic']['name'];
        $fileSize = $_FILES['profile_pic']['size'];

        // make sure it's under 5mb
        if ($fileSize > $maxBytes) {
            $error = 'File is too large. Maximum allowed size is 5 MB.';

        } else {
            // check the actual file type not just what the browser says
            $finfo     = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($tmp);

            if (!in_array($mimeType, $allowedMime, true)) {
                $error = "Invalid file type ($mimeType). Only JPG, PNG, GIF, and WEBP are accepted.";

            } else {
                // also check the file extension
                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed_ext, true)) {
                    $error = "Disallowed extension (.$ext).";

                } else {
                    // give the file a unique name so nothing gets overwritten
                    $saved_name = 'profile_' . uniqid('', true) . '.' . $ext;
                    $dest       = $uploadDir . $savedName;

                    // move the file from the temp folder into uploads/
                    if (move_uploaded_file($tmp, $dest)) {
                        $uploaded   = true;
                        $savedPath = $uploadWeb . $savedName;
                    } else {
                        $error = 'Could not save the file. Check that uploads/ is writable (chmod 755).';
                    }
                }
            }
        }
    }
}

// convert bytes to a readable size
function fmt_bytes(int $b): string {
    if ($b >= 1048576) return round($b / 1048576, 2) . ' MB';
    if ($b >= 1024)    return round($b / 1024, 1) . ' KB';
    return "$b B";
}

// get all files currently in the uploads folder
function get_uploads(string $dir, string $web): array {
    $files = [];
    if (!is_dir($dir)) return $files;
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = $dir . $f;
        if (is_file($full)) {
            $files[] = [
                'name'     => $f,
                'web'      => $web . $f,
                'size'     => fmt_bytes(filesize($full)),
                'modified' => date('M j, Y  H:i', filemtime($full)),
            ];
        }
    }
    usort($files, fn($a, $b) => strcmp($b['modified'], $a['modified']));
    return $files;
}

$all_files = get_uploads($uploadDir, $uploadWeb);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Picture Upload</title>
</head>
<body>

<header class="masthead">
  <p class="masthead-label">Profile Management System</p>
  <h1>Picture Upload</h1>
  <p class="masthead-sub">Select an image &middot; Upload &middot; Confirm</p>
</header>

<div class="page">

  <!-- upload form -->
  <div>
    <div class="section-head">
      <div class="section-num">1</div>
      <p class="section-title">Select &amp; Upload</p>
    </div>

    <div class="panel">
      <!-- post sends the file data, enctype is needed for file uploads -->
      <form method="post" enctype="multipart/form-data" action="">

        <label class="field-label" for="profile_pic">Choose a profile picture</label>

        <!-- type file gives us the file picker, name is how php finds it in $_FILES -->
        <input
          type="file"
          id="profile_pic"
          name="profile_pic"
          accept="image/*"
          required
        />

        <button type="submit" class="btn">Upload Profile Picture</button>

      </form>
    </div>
  </div>

  <!-- shows the result after upload -->
  <div>
    <div class="section-head">
      <div class="section-num">2</div>
      <p class="section-title">Upload Result</p>
    </div>

    <div class="result-panel">

      <?php if ($uploaded): ?>

        <!-- success message -->
        <div class="notice ok">
          <span class="notice-icon">✅</span>
          <div class="notice-text">
            <strong>Upload successful!</strong>
            Your profile picture has been saved to the <code>uploads/</code> folder.
          </div>
        </div>

        <!-- file details -->
        <table class="meta-table">
          <tr>
            <td>Original name</td>
            <td><?= htmlspecialchars($origName) ?></td>
          </tr>
          <tr>
            <td>Saved as</td>
            <td><code><?= htmlspecialchars($savedName) ?></code></td>
          </tr>
          <tr>
            <td>File size</td>
            <td><?= fmt_bytes($fileSize) ?></td>
          </tr>
          <tr>
            <td>MIME type</td>
            <td><?= htmlspecialchars($mimeType) ?></td>
          </tr>
          <tr>
            <td>Server path</td>
            <td><code>uploads/<?= htmlspecialchars($savedName) ?></code></td>
          </tr>
        </table>

        <!-- display the uploaded image on the page -->
        <p class="img-label">Uploaded image</p>
        <div class="img-frame">
          <img
            src="<?= htmlspecialchars($savedPath) ?>"
            alt="Uploaded profile picture"
          />
        </div>

      <?php elseif ($error): ?>

        <!-- something went wrong -->
        <div class="notice err">
          <span class="notice-icon">X</span>
          <div class="notice-text">
            <strong>Upload failed</strong>
            <?= htmlspecialchars($error) ?>
          </div>
        </div>

      <?php else: ?>

        <!-- nothing submitted yet -->
        <div class="notice idle">
          <span class="notice-icon">📋</span>
          <div class="notice-text">
            <strong>Waiting for upload</strong>
            Select an image on the left and click Upload. The confirmation
            and your image will appear here.
          </div>
        </div>

      <?php endif; ?>

    </div>
  </div>

</div>


<!-- shows all files currently in the uploads folder -->
<div class="listing">
  <div class="listing-head">
    <h2>uploads/ Folder Contents</h2>
    <span class="badge"><?= count($allFiles) ?> file<?= count($allFiles) !== 1 ? 's' : '' ?></span>
  </div>

  <?php if (empty($allFiles)): ?>
    <p class="empty-msg">No files yet — the uploads/ folder is empty.</p>
  <?php else: ?>
    <div class="file-grid">
      <?php foreach ($allFiles as $f): ?>
        <div class="file-card">
          <div class="file-card-img">
            <img src="<?= htmlspecialchars($f['web']) ?>" alt="<?= htmlspecialchars($f['name']) ?>"/>
          </div>
          <div class="file-card-info">
            <p class="file-card-name">
              <?= htmlspecialchars($f['name']) ?>
              <?php if ($uploaded && $f['name'] === $savedName): ?>
                <span class="new-tag">New</span>
              <?php endif; ?>
            </p>
            <p class="file-card-size"><?= $f['size'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
