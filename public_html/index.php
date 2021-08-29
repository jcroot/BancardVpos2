<?php
include('header.php');

$entityManager = getEntityManager();
$entries = $entityManager->getRepository('Product')->findAll();
?>
    <form method="post" action="checkout.php" id="frm">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 py-2">
                        Checkout
                    </div>
                    <div class="col-6 text-end">
                        <a href="product.php" class="btn btn-primary">Add Product</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-2 col-form-label text-end">Type</label>
                    <div class="col-4">
                        <select class="form-select" name="type" autofocus>
                            <option value="single_buy">Single Buy</option>
                            <option value="zimple">Buy With Zimple</option>
                            <option value="token">Buy With Token</option>
                        </select>
                    </div>
                </div>

                <div class="py-2">
                    <div class="row">
                        <div class="col-10 offset-1">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($entries) > 0): ?>
                                    <?php $total = 0; ?>
                                    <?php foreach ($entries as $entry): ?>
                                        <?php $total += $entry->getPrice(); ?>
                                        <tr>
                                            <td><?php echo $entry->getId() ?></td>
                                            <td class="w-50"><?php echo $entry->getTitle() ?></td>
                                            <td>1</td>
                                            <td class="text-end"><?php echo $entry->getPrice() ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"></td>
                                    </tr>
                                <?php endif ?>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Total</td>
                                    <td class="text-end"><?php echo $total ?><input type="hidden" value="<?php echo $total ?>" name="amount"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6">
                        <a href="orders.php" class="btn btn-link">Orders</a>
                    </div>
                    <div class="col-6 text-end">
                        <button type="submit" class="btn btn-primary">Pay Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
include('footer.php');
?>