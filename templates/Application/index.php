<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<div class="container main">

    <div class="row">
        <div class="col-6 p-3">
            <div class="logo">Sales Dashboard</div>
        </div>
        <div class="col-6 text-right p-3">
            Select date range: <input id="dateRangePicker" type="text" name="daterange" value="<?= $date['since'] ?> - <?= $date['till'] ?>">
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Total</div>
        <div class="card-body">
            <div class="row">
                <div class="col-4 text-left" id="total-customers"></div>
                <div class="col-4 text-center" id="total-revenue"></div>
                <div class="col-4 text-right" id="total-orders"></div>
            </div>
        </div>
    </div>

    <div id="charts" class="cards"></div>
</div>

<script src="/build/index.bundle.js"></script>

</body>
</html>