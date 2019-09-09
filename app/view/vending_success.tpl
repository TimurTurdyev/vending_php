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
    <style>
        .money_back__block {
            position: relative;
        }

        .coin p {
            font-family: georgia;
            font-style: italic;
            position: absolute;
            font-size: 28px;
            z-index: 700;
            top: 3px;
            left: 50%;
            transform: translateX(-50%);
            padding: 0;
            margin: 0;
        }

        .coin.bronze p {
            color: rgba(223, 182, 103, 1);
        }

        .coin {
            cursor: pointer;
            content: "";
            width: 52px;
            height: 52px;
            display: inline-block;
            position: relative;
            margin: 5px;
            top: 6px;
            border-radius: 50px;
            z-index: 500;
            box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, .1);
            position: absolute;
            top: -9px;
            left: 173px;
            transition: all .5s ease-in-out;
        }

        .coin span {
            position: relative;
            left: -25px;
            top: 10px;
            font-size: 20px;
        }

        .coin:after {
            content: "";
            width: 42px;
            height: 42px;
            display: block;
            top: 4px;
            left: 4px;
            position: absolute;
            border-radius: 50px;
            z-index: 600;
        }

        .coin:before {
            content: "";
            width: 50px;
            height: 50px;
            display: block;
            position: absolute;
            border-radius: 50px;
            z-index: 500;
        }

        .coin:hover {
            top: -19px;
            transition: all .5s ease-in-out;
            box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, .2);
        }

        .bronze {
            background: linear-gradient(45deg, rgba(223, 182, 103, 1) 0%, rgba(249, 243, 232, 1) 56%, rgba(231, 192, 116, 1) 96%);
        }

        .bronze:before {
            background: linear-gradient(135deg, #d19c35 0%, #f7e6c5 50%, #e8b558 100%);
            border: 1px solid #e6b86a;
        }

        .bronze:after {
            background: linear-gradient(45deg, rgba(223, 182, 103, 1) 0%, rgba(249, 243, 232, 1) 56%, rgba(231, 192, 116, 1) 96%);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom: 1px solid rgba(209, 156, 53, 0.3);
            border-right: 1px solid rgba(209, 156, 53, 0.5);
            box-shadow: inset 0px 0px 2px 2px rgba(153, 106, 26, .05);
        }

        .bronze:hover:after {
            background: linear-gradient(45deg, rgba(223, 182, 103, 1) 0%, rgba(249, 243, 232, 1) 41%, rgba(231, 192, 116, 1) 96%);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom: 1px solid rgba(209, 156, 53, 0.3);
            border-right: 1px solid rgba(209, 156, 53, 0.5);
            box-shadow: inset 0px 0px 2px 2px rgba(153, 106, 26, .05);
        }
    </style>
</head>

<body class="bg-dark">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ваш напиток</h4>
                <p class="card-text">
                    <?php foreach($products as $product) { ?>
                    <?php if($product_check == $product['id']) { ?>
                    <?php echo $product['name']; ?> x 1 шт.
                    <br>
                    Цена: <?php echo $product['price']; ?> руб.
                    <?php } ?>
                    <?php } ?>
                </p>
                <hr>
                <?php echo $message; ?>
                <hr>
                <h5 class="card-title">Ваша сдача</h5>
                <?php foreach($money_back as $key => $money) { ?>
                <?php if($money > 0) { ?>
                <div class="money_back__block">
                    Монета: <?php echo $key; ?> руб. <br>
                    Выдано: <?php echo $money; ?> шт.
                    <div class="coin bronze">
                        <span>=</span>
                        <p><?php echo ($key * $money); ?></p>
                    </div>
                </div>
                <hr>
                <?php } ?>
                <?php } ?>
                <h4 class="card-title text-center">Хорошего дня!</h4>
                <button class="btn btn-primary btn-block" onclick="location.href = ''">Назад</button>
            </div>
        </div>
    </div>
</body>

</html>