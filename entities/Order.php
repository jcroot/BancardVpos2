<?php

/**
 *  @Entity @Table(name="orders")
 */
class Order
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @var int
     * @Column (type="integer")
     */
    protected $created_at;
    /**
     * @var int
     * @Column (type="integer")
     */
    protected $updated_at;
    /**
     * @var string
     * @Column (type="string")
     */
    protected $fullname;
    /**
     * @var string
     * @Column (type="string")
     */
    protected $email;
    /**
     * @var string
     * @Column (type="string")
     */
    protected $phone;
    /**
     * @var int
     * @Column (type="decimal")
     */
    protected $total;

    /** @OneToMany(targetEntity="OrderItem", mappedBy="order") */
    protected $items;

    /**
     * @var string
     * @Column (type="string")
     */
    protected $process_id;
    /**
     * @var string
     * @Column (type="string")
     */
    protected $type;

    /**
     * @param string $fullname
     * @param int $total
     */
    public function __construct($fullname, $email, $phone, $total, $type)
    {
        $this->created_at = mktime();
        $this->updated_at = mktime();
        $this->fullname = $fullname;
        $this->email = $email;
        $this->phone = $phone;
        $this->total = $total;
        $this->process_id = "";
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return string
     */
    public function getProcessId(): string
    {
        return $this->process_id;
    }

    /**
     * @param string $process_id
     */
    public function setProcessId(string $process_id): void
    {
        $this->process_id = $process_id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}