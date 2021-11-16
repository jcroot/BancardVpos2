<?php
include ('header.php');

$entityManager = getEntityManager();
$entries = $entityManager->getRepository(Order::class)->findAll();

function isPending(Order $order): bool
{
    $entries = $order->getTransactions();
    return ($entries->getIterator()->count() == 0);
}
?>

<div class="card">
    <div class="card-header py-3">
        Orders
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>ProcessID</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($entries) > 0): ?>
                    <?php foreach ($entries as $entry):?>
                        <?php if (isPending($entry)): ?>
                            <tr>
                                <td><?php echo $entry->getId() ?></td>
                                <td class="w-25"><?php echo $entry->getFullname() ?></td>
                                <td><?php echo $entry->getEmail() ?></td>
                                <td><?php echo $entry->getTotal() ?></td>
                                <td><a href="checkout.php?process_id=<?php echo $entry->getProcessId() ?>&type=<?php echo $entry->getType() ?>"><?php echo $entry->getProcessId() ?></a></td>
                                <td><?php echo $entry->getType() ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td><?php echo $entry->getId() ?></td>
                                <td class="w-25"><?php echo $entry->getFullname() ?></td>
                                <td><?php echo $entry->getEmail() ?></td>
                                <td><?php echo $entry->getTotal() ?></td>
                                <td><?php echo $entry->getProcessId() ?></td>
                                <td><?php echo $entry->getType() ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td colspan="6" class="text-center">No rows</td>
                    </tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="index.php" class="btn btn-link">Back</a>
    </div>
</div>


<?php
include ('footer.php');
?>
