<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-service-account.json'));
        $this->client->addScope(Sheets::SPREADSHEETS);

        $this->service = new Sheets($this->client);
        $this->spreadsheetId = \App\Models\Setting::getValue('google_sheet_url'); // Берём ID документа из настроек
    }

    public function updateSheet($data)
    {
        $range = "Sheet1!A2"; // Начинаем со 2-й строки (1-я под заголовки)
        $body = new Sheets\ValueRange(['values' => $data]);
        $params = ['valueInputOption' => 'RAW'];

        $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
    }
}
