<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<div class="container-fluid main">

    <div class="row">
        <div class="col-6 p-3">
            <div class="logo">Sales Dashboard</div>
        </div>
        <div class="col-6 text-right p-3">
            <input id="dateRangePicker" type="text" name="daterange" value="<?= $date['since'] ?> - <?= $date['till'] ?>">
        </div>
    </div>

    <div id="charts" class="cards"></div>
</div>

<script src="build/index.bundle.js"></script>

</body>
</html>