## PHPCFDI/CSF-SCRAPER

### Instalación

```php
composer require phpcfdi/csf-scraper
```

### Ejemplo de uso

```php
<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use PhpCfdi\CsfScraper\Scraper;

require 'vendor/autoload.php';

$scraper = new Scraper(
    new Client()
);

// opcionalmente puedes enviar un objeto de la clase PhpCfdi\Rfc\Rfc para el parámetro rfc
$person = $scraper->data(rfc: 'YOUR_RFC', idCIF: 'ID_CIF');

// puedes acceder a los datos de la persona (moral o física) usando los métodos incluidos:
echo $person->getCurp();

// o puedes obtener el array de datos usado
echo print_r($person->toArray(), true);
```
