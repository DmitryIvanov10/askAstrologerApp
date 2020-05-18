<?php
declare(strict_types=1);

namespace App\Service;

use App\Interfaces\SpreadsheetValuesInterface;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractGoogleSpreadsheetsService
{
    private string $googleSpreadsheetId;
    private Google_Service_Sheets $googleSpreadsheetsService;

    public function __construct(string $googleSpreadsheetId, Google_Service_Sheets $googleSpreadsheetsService)
    {
        $this->googleSpreadsheetId = $googleSpreadsheetId;
        $this->googleSpreadsheetsService = $googleSpreadsheetsService;
    }

    final protected function saveToSpreadsheet(SpreadsheetValuesInterface ...$spreadsheetValuesArray): void
    {
        $values = [];

        foreach ($spreadsheetValuesArray as $spreadsheetValues) {
            $values[] = $spreadsheetValues->getValues();
        }

        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW',
            'insertDataOption' => "INSERT_ROWS"
        ];

        $this->googleSpreadsheetsService->spreadsheets_values->append(
            $this->googleSpreadsheetId,
            $this->getOrderGoogleSpreadsheetName(),
            $body,
            $params
        );
    }

    abstract protected function getOrderGoogleSpreadsheetName(): string;
}
