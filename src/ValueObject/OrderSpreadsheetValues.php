<?php
declare(strict_types=1);

namespace App\ValueObject;

use App\Interfaces\SpreadsheetValuesInterface;
use DateTime;

/**
 * TODO cover with unit test
 */
class OrderSpreadsheetValues implements SpreadsheetValuesInterface
{
    private const DATE_TIME_FORMAT          = 'Y-m-d H:i:s';
    private const PRICE_CURRENCY_SIGN       = '$';
    private const UNSPECIFIED_CUSTOMER_NAME = 'UNKNOWN';

    private int $orderId;
    private int $statusId;
    private string $statusName;
    private int $astrologerId;
    private string $astrologerName;
    private string $astrologerEmail;
    private int $serviceId;
    private string $serviceName;
    private string $price;
    private string $customerEmail;
    private ?string $customerName;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        int $orderId,
        int $statusId,
        string $statusName,
        int $astrologerId,
        string $astrologerName,
        string $astrologerEmail,
        int $serviceId,
        string $serviceName,
        float $price,
        string $customerEmail,
        ?string $customerName,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->orderId = $orderId;
        $this->statusId = $statusId;
        $this->statusName = $statusName;
        $this->astrologerId = $astrologerId;
        $this->astrologerName = $astrologerName;
        $this->astrologerEmail = $astrologerEmail;
        $this->serviceId = $serviceId;
        $this->serviceName = $serviceName;
        $this->setPrice($price);
        $this->customerEmail = $customerEmail;
        $this->setCustomerName($customerName);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }

    private function setCreatedAt(DateTime $createAt): void
    {
        $this->createdAt = $createAt->format(self::DATE_TIME_FORMAT);
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt->format(self::DATE_TIME_FORMAT);
    }

    public function getValues(): array
    {
        return [
            $this->orderId,
            $this->statusId,
            $this->statusName,
            $this->astrologerId,
            $this->astrologerName,
            $this->astrologerEmail,
            $this->serviceId,
            $this->serviceName,
            $this->price,
            $this->customerEmail,
            $this->customerName,
            $this->createdAt,
            $this->updatedAt
        ];
    }

    private function setPrice(float $price): void
    {
        $this->price = sprintf('%s%s', number_format($price, 2), self::PRICE_CURRENCY_SIGN);
    }

    private function setCustomerName(?string $customerName): void
    {
        $this->customerName = $customerName ?? self::UNSPECIFIED_CUSTOMER_NAME;
    }
}
