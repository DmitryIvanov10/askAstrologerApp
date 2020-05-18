<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\InfrastructureException;
use App\ValueObject\OrderSpreadsheetValues;
use Google_Exception;
use Google_Service_Sheets;

/**
 * @codeCoverageIgnore
 */
class OrderGoogleSpreadsheetsService extends AbstractGoogleSpreadsheetsService
{
    private string $orderGoogleSpreadsheetName;

    public function __construct(
        string $googleSpreadsheetId,
        Google_Service_Sheets $googleSpreadsheetsService,
        string $orderGoogleSpreadsheetName
    ) {
        parent::__construct($googleSpreadsheetId, $googleSpreadsheetsService);
        $this->orderGoogleSpreadsheetName = $orderGoogleSpreadsheetName;
    }

    /**
     * @throws InfrastructureException
     */
    public function saveOrderToSpreadsheet(OrderSpreadsheetValues $orderSpreadsheetValues): void
    {
        try {
            $this->saveToSpreadsheet($orderSpreadsheetValues);
        } catch (Google_Exception $exception) {
            throw new InfrastructureException('Couldn\'t save order data to the spreadsheet', 0, $exception);
        }
     }

    /**
     * @inheritDoc
     */
    protected function getOrderGoogleSpreadsheetName(): string
    {
        return $this->orderGoogleSpreadsheetName;
    }
}
