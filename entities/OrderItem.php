<?php

/**
 * @Entity @Table(name="order_items")
 */
class OrderItem
{
    /**
     * @var int
     * @Column(type="integer")
     */
    protected $created_at;

    /**
     * @var Order
     * @Id @ManyToOne(targetEntity="Order")
     */
    protected $order;

    /**
     * @var Product
     * @Id @ManyToOne(targetEntity="Product")
     */
    protected $product;

    /**
     * @var int
     * @Column(type="decimal")
     */
    protected $amount;

    public function __construct(Order $order, Product $product, $amount = 1)
    {
        $this->order = $order;
        $this->product = $product;
        $this->amount = $product->getPrice();
    }
}