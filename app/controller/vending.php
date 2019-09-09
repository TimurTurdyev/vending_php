<?php
class VendingController
{
    public function index()
    {
        $model = new VendingModel(array('money', 'products', 'valet'));
        $data = array();

        $data['money'] = $model->money;
        $data['products'] = $model->products;
        $data['valet'] = $model->valet;

        $data['money_total'] = 0;
        $test = 0;
        foreach ($data['money'] as $key => $value) {
            $test += $key * $value;
            $data['money_total'] += $key * $value;
        }

        $data['valet_total'] = 0;

        foreach ($data['valet'] as $key => $value) {
            $data['valet_total'] += $key * $value;
        }

        $data['money_return'] = null;

        if (isset($_POST['money_return'])) {
            $data['money_return'] = 1;
        }

        $total = 0;
        $data['product_check'] = 0;

        if (!$data['money_return'] && isset($_POST['product_id'])) {
            foreach ($data['products'] as $product) {
                if ($product['id'] == $_POST['product_id']) {
                    $data['product_check'] = $_POST['product_id'];
                    $total = (int) $product['price'];
                    break;
                }
            }
        }

        $deposit = 0;
        $data['money_check'] = array();

        if (!$data['money_return'] && isset($_POST['money'])) {

            foreach ($_POST['money'] as $key => $check) {
                if ($check > 0 && $data['money'][$key]) {
                    $data['money_check'][$key] = $check;

                    $deposit += $key * $check;
                    $data['valet'][$key] = $data['valet'][$key] + $check;

                }

            }

        }

        $data['money_total'] = $data['money_total'] - $deposit;
        $data['valet_total'] += $deposit;

        $data['step'] = '';
        $data['error'] = '';
        $data['success'] = '';

        if ($deposit - $total >= 0) {
            $data['message'] = 'Внесено: <strong>' . ($deposit) . ' руб.</strong><br>';
            $data['message'] .= 'Сдача: <strong>' . ($deposit - $total) . ' руб.</strong>';
            $data['submit_title'] = 'Продолжить';
        } else {
            $data['message'] = 'Внесено: <strong>' . ($deposit) . ' руб.</strong><br>';
            $data['message'] .= 'Доплатить: <strong>' . ($total - $deposit) . ' руб.</strong>';
            $data['submit_title'] = 'Запустить';
            $data['error'] = 'Обратите внимание! ' . $data['message'];
        }

        if ($total > 0) {
            $data['message'] = 'Цена выбранного напитка: <strong>' . ($total) . ' руб.</strong><br>' . $data['message'];
            if ($deposit - $total > 0) {
                $data['step'] = '<input type="hidden" name="success" value="1">';
                $data['success'] = 'Вы можете забрать напиток нажав <strong>"Приготовить напиток и выдать сдачу"</strong> или отказаться нажав на кнопку <strong>"Отменить приготовление"</strong>';
                $data['submit_title'] = 'Приготовить напиток и выдать сдачу';
            }
        } else {
            $data['error'] = 'Выберите напиток!';
        }

        // echo '<div style="background: #fff;"><pre>';
        // var_dump($this->how_many_coins(113));
        // echo '</pre></div>';

        if (isset($_POST['success']) && $data['step']) {
            
            $data['money_back'] = $this->how_many_coins($deposit - $total);
            $data['message'] .= '<br>Ваш счет: <strong>' . ($data['money_total']) . ' руб.</strong><br>';
            
            $template = new PHP();
            $template->set($data);

            echo $template->render('vending_success.tpl');
        } else {

            $template = new PHP();
            $template->set($data);

            echo $template->render('vending.tpl');
        }

    }

    public function how_many_coins($n)
    {
        $arr = array();
        $flag = true;

        while ($flag) {
            // echo $n;
            // echo '<br>';
            switch (true) {
                case $n >= 10:
                    $a = (int) floor($n / 10);
                    $arr['10'] = $a;
                    $n = $n - ($a * 10);
                    break;
                case $n > 5:
                    $arr['5'] = 1;
                    $n = $n - 5;
                    break;
                case $n == 5:
                    $arr['5'] = 1;
                    $n = 0;
                    break;
                case $n == 4:
                    $arr['2'] = 2;
                    $n = 0;
                    break;
                case $n == 3:
                    $arr['2'] = 1;
                    $arr['1'] = 1;
                    $n = 0;
                    break;
                case $n == 2:
                    $arr['2'] = 1;
                    $n = 0;
                    break;
                case $n == 1:
                    $arr['1'] = 1;
                    $n = 0;
                default:
                    $flag = false;

            }
        }

        return $arr;
    }
}
