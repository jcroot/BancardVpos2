<?php

/**
 * @Entity @Table(name="products")
 */
class Product
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @var int
     * @Column(type="integer")
     */
    protected $created_at;
    /**
     * @var int
     * @Column(type="integer")
     */
    protected $updated_at;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $title;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $description;
    /**
     * @var int
     * @Column(type="decimal")
     */
    protected $price;

    /**
     * @param string $title
     * @param string $description
     * @param int $price
     */
    public function __construct(string $title, string $description, int $price)
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->created_at = time();
        $this->updated_at = time();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

}