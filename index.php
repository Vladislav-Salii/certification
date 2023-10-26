<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$apiUrl = 'https://restcountries.com/v3.1/all';

try {
    $option = isset($_GET['option']) ? $_GET['option'] : '';
    $response = $client->get($apiUrl);
    $data = $response->getBody()->getContents();
    $countries = json_decode($data, true);

    echo '<html>';
    echo '<head><link rel="stylesheet" type="text/css" href="styles.css"><title>Список країн</title></head>';
    echo '<body>';

    echo '<h1>Виберіть опцію:</h1>';
    echo '<form method="get">';
    echo '<button type="submit" name="option" value="countries">Переглянути всі країни світу</button>';
    echo '<button type="submit" name="option" value="currencies">Переглянути всі валюти світу</button>';
    echo '<button type="submit" name="option" value="capitals">Столиці світу</button>';
    echo '</form>';

    echo '<table>';
    echo '<tr>';
    if ($option === 'countries') {
        echo '<th>Список країн</th>';
        echo '</tr>';
        foreach ($countries as $country) {
            echo '<tr><td>' . $country['name']['common'] . '</td></tr>';
        }
    } elseif ($option === 'currencies') {
        echo '<th>Список валют світу</th>';
        echo '</tr>';
        foreach ($countries as $country) {
            if (isset($country['currencies'])) {
                $currencies = $country['currencies'];
                if (is_array($currencies)) {
                    $currencyNames = array_map(function ($currency) {
                        return $currency['name'];
                    }, $currencies);
                    echo '<tr><td>' . implode(', ', $currencyNames) . '</td></tr>';
                } else {
                    echo '<tr><td>' . $currencies['name'] . '</td></tr>';
                }
            }
        }
    } elseif ($option === 'capitals') {
        echo '<th>Столиці світу</th>';
        echo '</tr>';
        foreach ($countries as $country) {
            if (isset($country['capital'])) {
                if (is_array($country['capital'])) {
                    echo '<tr><td>' . implode(', ', $country['capital']) . '</td></tr>';
                } else {
                    echo '<tr><td>' . $country['capital'] . '</td></tr>';
                }
            }
        }
    }else {
        echo 'Будь ласка, оберіть опцію з меню.';
    }
    echo '</table>';
    echo '</h1>';

    echo '</body>';
    echo '</html>';
} catch (Exception $e) {
    echo 'Помилка у виконанні запиту: ' . $e->getMessage();
}