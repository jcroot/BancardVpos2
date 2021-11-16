<?php

/**
 * @Entity @Table(name="transactions")
 */
class Transaction
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @ManyToOne(targetEntity="Order", inversedBy="transactions")
     * @JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;
    /**
     * @var int
     * @Column(type="integer")
     */
    protected $created_at;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $response;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $response_details;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $amount;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $currency;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $authorization_number;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $ticket_number;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $response_code;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $response_description;
    /**
     * @var string|null
     * @Column(type="string", nullable=true)
     */
    protected $extended_response_description;
    /**
     * @var string
     * @Column(type="string")
     */
    protected $security_information;

    /**
     * @param string $response
     * @param string $response_details
     * @param string $amount
     * @param string $currency
     * @param string $authorization_number
     * @param string $ticket_number
     * @param string $response_code
     * @param string $response_description
     * @param string|null $extended_response_description
     * @param string $security_information
     * @param Order $order
     */
    public function __construct(string $response,
                                string $response_details,
                                string $amount,
                                string $currency,
                                string $authorization_number,
                                string $ticket_number,
                                string $response_code,
                                string $response_description,
                                string $extended_response_description,
                                string $security_information,
                                Order $order)
    {
        $this->response = $response;
        $this->response_details = $response_details;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->authorization_number = $authorization_number;
        $this->ticket_number = $ticket_number;
        $this->response_code = $response_code;
        $this->response_description = $response_description;
        $this->extended_response_description = $extended_response_description;
        $this->security_information = $security_information;
        $this->created_at = time();
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getResponseDetails(): string
    {
        return $this->response_details;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getAuthorizationNumber(): string
    {
        return $this->authorization_number;
    }

    /**
     * @return string
     */
    public function getTicketNumber(): string
    {
        return $this->ticket_number;
    }

    /**
     * @return string
     */
    public function getResponseCode(): string
    {
        return $this->response_code;
    }

    /**
     * @return string
     */
    public function getResponseDescription(): string
    {
        return $this->response_description;
    }

    /**
     * @return string
     */
    public function getExtendedResponseDescription(): string
    {
        return $this->extended_response_description;
    }

    /**
     * @return string
     */
    public function getSecurityInformation(): string
    {
        return $this->security_information;
    }

    public function getOrders()
    {
        return $this->order;
    }
}