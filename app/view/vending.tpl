<!doctype html>
<html lang="en">

<head>
    <title>Vending Machine</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body class="bg-dark">
    <div class="container mt-5">
        <form action="index.php" method="post" id="js_form">
            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">Выберите напиток</h5>
                    <?php foreach($products as $product) { ?>
                    <?php if($product_check == $product['id']) { ?>
                    <div class="form-check form-check-inline alert alert-primary">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="product_id"
                                value="<?php echo $product['id']; ?>" checked>
                            <?php echo $product['name']; ?> x <?php echo $product['amount']; ?>шт.
                            <br>
                            Цена: <?php echo $product['price']; ?> руб.
                        </label>
                    </div>
                    <?php } else { ?>
                    <div class="form-check form-check-inline alert alert-light">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="product_id"
                                value="<?php echo $product['id']; ?>">
                            <?php echo $product['name']; ?> x <?php echo $product['amount']; ?>шт.
                            <br>
                            Цена: <?php echo $product['price']; ?> руб.
                        </label>
                    </div>
                    <?php } ?>
                    <?php } ?>
                    <div class="alert alert-primary" role="alert">
                        <?php echo $message; ?>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h5 class="card-title">Ваш счет <span><?php echo $money_total; ?> руб.</span></h5>
                            <?php foreach($money as $key=>$amount) { 
                                $temp = 0;
                                ?>
                            <select name="money[<?php echo $key; ?>]" class="form-control mb-2">
                                <?php for($i=0; $i <= $amount; $i++) { ?>

                                <?php if($i < $money_check[$key] ) { 
                                    $temp = $i;
                                    ?>
                                <option value="<?php echo $i; ?>" disabled><?php echo $key; ?> руб. x <?php echo $i; ?>
                                    шт. =
                                    <?php echo ($key * $i); ?> руб.</option>
                                <?php } else if($i == $money_check[$key]) { 
                                    $temp = $i;
                                    ?>
                                <option value="<?php echo $i; ?>" selected><?php echo $key; ?> руб. x <?php echo $i; ?>
                                    шт. =
                                    <?php echo ($key * $i); ?> руб.</option>
                                <?php } else { ?>
                                <option value="<?php echo $i; ?>">Добавить: <?php echo $key; ?> руб. x <?php echo ($i - $temp); ?> шт. =
                                    <?php echo ($key * ($i - $temp)); ?> руб.</option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                            <?php } ?>
                            <input type="hidden" name="money_return" value="1" disabled>
                            <button type="button" class="btn btn-light btn-block btn-xs"
                                onclick="$(this).prev().attr('disabled', false); $('#js_form').submit();">Отменить
                                приготовление</button>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="card-title">Остаток монет на сдачу <span><?php echo $valet_total; ?> руб.</span>
                            </h5>
                            <ul class="list-group">
                                <?php foreach($valet as $key => $amount) { ?>
                                <li class="list-group-item"><?php echo $key; ?> руб. = <?php echo $amount; ?> шт.</li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                    <?php if($error) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo $error; ?>
                    </div>
                    <?php } ?>

                    <?php if($success) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                    </div>
                    <?php } ?>

                    <?php echo $step; ?>
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><?php echo $submit_title; ?></button>
                </div>
            </div>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script> -->
</body>

</html>