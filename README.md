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
$data = $scraper->data(rfc: 'YOUR_RFC', idCIF: 'ID_CIF');
echo print_r($data, true);
```
