<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="PlanoD">
  <meta name="author" content="Lucão">
  <link href="assets/img/logo/planod-bolota.png" rel="icon">

  <title><?php echo $template['title'] ?></title>

  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/ruang-admin.min.css" rel="stylesheet">
  <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css" rel="stylesheet">
  <style>
    .custom-file-label,
    .custom-file-input {
      cursor: pointer;
    }

    .custom-file-label::after {
      cursor: pointer;
      color: #fff;
      background-color: #6777ef;
      border-color: #6777ef;
      box-shadow: 0 .125rem .25rem 0 rgba(58, 59, 69, .2) !important;
      border-radius: .25rem;
      display: inline-block;
      font-weight: 400;
      text-align: center;
      vertical-align: middle;
      user-select: none;
      border: 1px solid transparent;
      padding: .375rem .75rem;
      font-size: 1rem;
    }

    .disabled::after {
      filter: grayscale(1);
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <?php include BASE_PATH . 'assets/inc/page_sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include BASE_PATH . 'assets/inc/page_header.php'; ?>