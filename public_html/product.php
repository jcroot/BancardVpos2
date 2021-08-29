<?php
include ('header.php');

$entityManager = getEntityManager();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $title = (isset($_POST['title'])) ? $_POST['title'] : '';
    $description = (isset($_POST['description'])) ? $_POST['description'] : '';
    $price = (isset($_POST['price'])) ? $_POST['price'] : 0;

    if (!empty($title) && !empty($description) && !empty($price)){
        try {
            if ($id > 0){
                $product = $entityManager->getRepository(Product::class)->find(['id' => $id]);
                $product->setTitle($title);
                $product->setDescription($description);
                $product->setPrice($price);
            }else{
                $product = new Product($title, $description, $price);
                $entityManager->persist($product);
            }
            $entityManager->flush();
            $product = null;
        } catch (\Doctrine\ORM\ORMException $e) {
            echo $e->getMessage();
        }
    }
}else{
    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $product = $entityManager->getRepository(Product::class)->find(['id' => $id]);

        if (isset($_GET['action']) && $_GET['action'] === 'remove'){
            try {
                $entityManager->remove($product);
                $entityManager->flush();
                header('Location: product.php');
            } catch (\Doctrine\ORM\ORMException $e) {
                echo $e->getMessage();
            }
        }
    }
}

$entries = $entityManager->getRepository('Product')->findAll();
?>

<div class="card">
    <div class="card-header py-3">
        Products
    </div>
    <div class="card-body">
        <form autocomplete="off" method="post" action="product.php" name="frm">
            <input type="hidden" id="id" name="id" value="<?php echo ((isset($product)) ? $product->getId() : 0) ?>">
            <div class="mb-3 row">
                <label for="title" class="form-label col-2 text-end">Title</label>
                <div class="col-6">
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo ((isset($product)) ? $product->getTitle() : '') ?>" autofocus required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="description" class="form-label col-2 text-end">Description</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="description" id="description" value="<?php echo ((isset($product)) ? $product->getDescription() : '') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="amount" class="form-label col-2 text-end">Amount</label>
                <div class="col-4">
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo ((isset($product)) ? $product->getPrice() : '') ?>" required>
                </div>
            </div>

            <div class="modal-footer">
                <?php
                    $prefix_label = "Add";
                ?>
                <?php if (isset($product)):?>
                    <?php $prefix_label = "Update"; ?>
                    <a href="product.php?id=<?php echo $product->getId() ?>&action=remove" class="btn btn-danger">Remove</a>
                    <a href="product.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
                <button class="btn btn-primary"><?php echo $prefix_label ?> Product</button>
            </div>
        </form>

        <div class="mt-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Amount</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($entries)>0): ?>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo $entry->getId() ?></td>
                            <td><?php echo $entry->getTitle() ?></td>
                            <td><?php echo $entry->getDescription() ?></td>
                            <td><?php echo $entry->getPrice() ?></td>
                            <td class="text-end">
                                <a href="product.php?id=<?php echo $entry->getId() ?>" class="btn btn-success btn-sm">Edit Product</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No rows</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
    <div class="card-footer">
        <a href="index.php" class="btn btn-link">Back</a>
    </div>
</div>

<?php
include ('footer.php');
?>
