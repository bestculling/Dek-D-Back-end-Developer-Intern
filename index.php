<?php

    $status = false;

// ฟังก์ชันสำหรับตรวจสอบความถูกต้องของชื่อกระทู้
function validateTopic($topic) {
  // ตรวจสอบความยาว
  if (strlen($topic) < 4 || strlen($topic) > 140) {
    return false;
  }

  // ตรวจสอบว่ามี HTML หรือไม่
  if (preg_match('/<[^>]+>/', $topic)) {
    return false;
  }

  return true;
}

// ฟังก์ชันสำหรับตรวจสอบความถูกต้องของเนื้อหากระทู้
function validateContent($editordata) {
  $text = preg_replace('/<[^>]*>/', '', $editordata);
  // ตรวจสอบความยาว
  if (strlen($text) < 6 || strlen($text) > 2000) {
    return false;
  }
  return true;
}

// ประมวลผลข้อมูลที่ส่งมา
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $topic = trim($_POST['topic']);
  $editordata = trim($_POST['editordata']);

  // ตรวจสอบความถูกต้อง
  $errors = [];
  if (!validateTopic($topic)) {
    $errors[] = 'ชื่อกระทู้ต้องยาว 4-140 ตัวอักษร และไม่อนุญาตให้ใช้ HTML';;
  }
  if (!validateContent($editordata)) {
    $errors[] = 'เนื้อหากระทู้ต้องยาว 6-2000 ตัวอักษร';
  }

  // แสดงข้อผิดพลาด
  if ($errors) {
    echo '<ul>';
    foreach ($errors as $error) {
      echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
    }
    echo '</ul>';
  } else {
    // แสดงกระทู้
    $status = true;
  }
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ตั้งกระทู้ใหม่</title>
  <link rel="stylesheet" href="style.css">
  <!-- include libraries(jQuery, bootstrap) -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script type="text/javascript" src="cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</head>
<body>
    <div style="color: #F96519" class="d-flex justify-content-center mt-3 mb-5">
        <h1>ตั้งกระทู้ใหม่</h1>
    </div>
  <div style="width: 800px" class="mx-auto p-5 border border-1 rounded">
  <form method="post">
  <input class="form-control form-control-lg" type="text" id="topic" name="topic" placeholder="หัวข้อกระทู้ ต้องยาว 4-140 ตัวอักษร" size="50" required>
    <br>
    <div class="d-flex justify-content-between"><p>เนื้อหากระทู้</p><p style="color: #F96519">เนื้อหากระทู้ต้องยาว 6-2000 ตัวอักษร</p></div>
    <br>
  <textarea id="summernote" name="editordata" placeholder="เนื้อหากระทู้ต้องยาว 6-2000 ตัวอักษร"></textarea>
  <script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>
  <input style="background: #F96519" class="btn text-white" type="submit" value="ตั้งกระทู้เลย">
</form>
<?php
        if($status == true && !$errors) {
            echo "<h2 style='color: #F96519'>" . $topic . "</h2>";
    echo $editordata;
        }
    ?>
</div>

</body>
</html>
