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
use PhpCfdi\Rfc\Rfc;

require 'vendor/autoload.php';

$scraper = new Scraper(
    new Client()
);

$rfc = Rfc::parse('YOUR_RFC');

// se recomienda enviar un objeto de la clase PhpCfdi\Rfc\Rfc para el parámetro rfc aunque también se acepta un string.
$person = $scraper->data(rfc: $rfc, idCIF: 'ID_CIF');

// puedes acceder a los datos de la persona (moral o física) usando los métodos incluidos:
if($rfc->isFisica()) {
    echo $person->getNombre();
}
if($rfc->isMoral()) {
    echo $person->getRazonSocial();
}

// o puedes obtener el array de datos usando
echo print_r($person->toArray(), true);
```
