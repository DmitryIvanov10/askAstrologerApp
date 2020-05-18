<?php
declare(strict_types=1);

namespace App\Factory;

use App\Exception\InfrastructureException;
use Google_Client;
use Google_Exception;
use Google_Service_Sheets;
use InvalidArgumentException;

class GoogleClientFactory
{
    /**
     * @throws InfrastructureException
     */
    public function __invoke(array $googleClientConfig): Google_Client
    {
        $client = new Google_Client();

        try {
            $client->setAuthConfig($googleClientConfig);
        } catch (InvalidArgumentException|Google_Exception $exception) {
            throw new InfrastructureException('Google client config credentials error', 0, $exception);
        }

        $client->setApplicationName('Ask Astrologer App');
        $client->setAccessType('offline');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);

        return $client;
    }
}
