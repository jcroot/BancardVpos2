<?php
include('header.php');

use Bancard\Operations\Buy\SingleBuy;

require_once(__DIR__ . '/../library/Bancard/autoload.php');
?>
<div class="card">
    <div class="card-header py-3">Checkout</div>
    <div class="card-body">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $amount = number_format($_POST['amount'], 2, '.', '');
            $type = $_POST['type'];

            $entityManager = getEntityManager();

            if ($type === 'single_buy' || $type === 'zimple') {
                $order = new Order("Customer 1", "customer@email.com", "0981123456", $_POST['amount'], $type);
                try {
                    $entityManager->persist($order);
                    $entityManager->flush();

                    // here you need to change for your own return_url and cancel_url
                    $serverName =  $_SERVER['SERVER_NAME'];
                    $port = $_SERVER['SERVER_PORT'];

                    $data = [
                        'shop_process_id' => $order->getId(),
                        'amount' => $amount,
                        'currency' => 'PYG',
                        'description' => 'Order #'. $order->getId(),
                        'additional_data' => '',
                        'return_url' => 'http://'.$serverName.':'.$port.'/orders.php?id='. $order->getId(),
                        'cancel_url' => 'http://'.$serverName.':'.$port.'/cancel.php'
                    ];

                    if ($type === 'zimple'){
                        $data['additional_data'] = '0981123456';
                        $data['zimple'] = 'S';
                    }

                    try {
                        $response = SingleBuy::init($data, true)->launch('post')->getResponse();

                        if ($response['code'] == 200) {

                            $process_id = $response['process_id'];

                            $order->setProcessId($process_id);
                            $entityManager->flush();
                            ?>
                            <div style="height: 350px; width: 500px; margin: auto" id="iframe-container"></div>

                            <script type="text/javascript">
                                window.onload = function () {
                                    <?php if($type === 'single_buy'): ?>
                                        Bancard.Checkout.createForm('iframe-container', '<?php echo $process_id ?>');
                                    <?php else:?>
                                        Bancard.Zimple.createForm('iframe-container', '<?php echo $process_id ?>');
                                    <?php endif;?>
                                };
                            </script>
                            <?php
                        } else {
                            foreach ($response['messages'] as $message) {
                                echo $message['key'] . ": " . $message['dsc'];
                            }
                        }
                    } catch (Exception $ex) {
                        $message = $ex->getMessage();
                    }
                } catch (\Doctrine\ORM\ORMException $e) {
                    echo $e->getMessage();
                }
            }
        }else{
            if (isset($_GET['process_id'])){
                ?>
            <div style="height: 350px; width: 500px; margin: auto" id="iframe-container"></div>

            <script type="text/javascript">
                window.onload = function () {
                    <?php if (isset($_GET['type']) && $_GET['type'] === 'single_buy'):?>
                        Bancard.Checkout.createForm('iframe-container', '<?php echo $_GET['process_id'] ?>');
                    <?php else:?>
                        Bancard.Zimple.createForm('iframe-container', '<?php echo $_GET['process_id'] ?>');
                    <?php endif;?>
                };
            </script>
        <?php
            }
        }
        ?>
    </div>
    <div class="card-footer">
        <a href="index.php" class="btn btn-link">Back</a>
    </div>
</div>

<?php
include('footer.php');
?>
