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
            <input type="text" name="daterange" value="<?= $date['since'] ?> - <?= $date['till'] ?>">
        </div>
    </div>

    <div id="charts" class="cards">
        <div class="card mb-3">
            <div class="card-header">
                Total number of orders
            </div>
            <div class="card-body">
                <div class="loading-status">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="card-text pl-1">Loading chart data...</span>
                </div>
                <div class="loading-result d-none">123</div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                Total number of revenue
            </div>
            <div class="card-body">
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                Total number of customers
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
</div>

<script src="build/index.bundle.js"></script>

</body>
</html>