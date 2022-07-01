# phpcfdi/csf-scraper

[![Source Code][badge-source]][source]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Discord][badge-discord]][discord]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Total Downloads][badge-downloads]][downloads]

> Obtiene los datos fiscales actuales de una persona moral o física dado su RFC y CIFID.

:us: The documentation of this project is in spanish as this is the natural language for intended audience.

:mexico: La documentación del proyecto está en español porque ese es el lenguaje principal de los usuarios.

## Instalación

Usa [composer](https://getcomposer.org/)

```php
composer require phpcfdi/csf-scraper
```

Esta librería requiere del uso de un cliente que implemente `GuzzleHttp\ClientInterface;` puedes obtener más información de esta librería en [guzzle](https://docs.guzzlephp.org/).

### Ejemplo de uso

```php
<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use PhpCfdi\CsfScraper\Scraper;
use PhpCfdi\Rfc\Rfc;

require 'vendor/autoload.php';

$scraper = Scraper::create();

$rfc = Rfc::parse('YOUR_RFC');

$person = $scraper->obtainFromRfcAndCif(rfc: $rfc, idCIF: 'ID_CIF');

// También puedes obtener los datos de la persona directamente del archivo local PDF
// (Ten en cuenta que esta funcionalidad requiere de tener instalado popper-utils en tu servidor).
$person = $scraper->obtainFromPdfPath('LOCAl_PDF_FILE_PATH');

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

Ejemplo de salida de `json_encode($person)` para persona moral:

```json
{
  "rfc": "DIM8701081LA",
  "id_cif": "12345678",
  "razon_social": "Mi razón social",
  "regimen_de_capital": "SA DE CV",
  "fecha_constitucion": {
    "date": "2019-02-21 22:50:46.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "fecha_inicio_operaciones": {
    "date": "2019-02-21 22:50:46.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "situacion_contribuyente": "ACTIVO",
  "fecha_ultimo_cambio_situacion": {
    "date": "2019-02-21 22:50:46.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "entidad_federativa": "CIUDAD DE MEXICO",
  "municipio_delegacion": "CUAUHTEMOC",
  "colonia": "CUAUHTEMOC",
  "tipo_vialidad": "Tipo vialidad",
  "nombre_vialidad": "PASEO DE LA REFORMA",
  "numero_exterior": "143",
  "numero_interior": "Piso 69",
  "codigo_postal": "72055",
  "correo_electronico": "example@example.com",
  "al": "CIUDAD DE MEXICO 2",
  "regimenes": [
    {
      "regimen": "Régimen General de Ley Personas Morales",
      "regimen_id": "601",
      "fecha_alta": {
        "date": "2019-02-21 22:50:46.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    }
  ],
  "extra_data": []
}
```

Ejemplo de salida de `json_encode($person)` para persona física:

```json
{
  "rfc": "COSC8001137NA",
  "id_cif": "1904014102123",
  "curp": "CURP",
  "nombre": "JUAN",
  "apellido_paterno": "PEREZ",
  "apellido_materno": "RODRIGUEZ",
  "fecha_nacimiento": {
    "date": "1973-05-01 22:53:25.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "fecha_inicio_operaciones": {
    "date": "2004-11-03 22:53:25.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "situacion_contribuyente": "ACTIVO",
  "fecha_ultimo_cambio_situacion": {
    "date": "2004-11-03 22:53:25.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "entidad_federativa": "CIUDAD DE MEXICO",
  "municipio_delegacion": "IZTAPALAPA",
  "colonia": "MI COLONIA",
  "tipo_vialidad": "CALLE",
  "nombre_vialidad": "BENITO JUAREZ",
  "numero_exterior": "183",
  "numero_interior": "",
  "codigo_postal": "72000",
  "correo_electronico": "",
  "al": "CIUDAD DE MEXICO 3",
  "regimenes": [
    {
      "regimen": "Régimen de Incorporación Fiscal",
      "regimen_id": "621",
      "fecha_alta": {
        "date": "2014-01-01 22:53:25.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      }
    }
  ],
  "extra_data": []
}
```

## Soporte

Puedes obtener soporte abriendo un ticket en Github.

Adicionalmente, esta librería pertenece a la comunidad [PhpCfdi](https://www.phpcfdi.com), así que puedes usar los
mismos canales de comunicación para obtener ayuda de algún miembro de la comunidad.

## Compatibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](https://www.php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](docs/SEMVER.md) por lo que puedes usar esta librería
sin temor a romper tu aplicación.

## Contribuciones

Las contribuciones con bienvenidas. Por favor lee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el archivo [CHANGELOG][].

## Copyright and License

The `phpcfdi/csf-scraper` library is copyright © [PhpCfdi](https://www.phpcfdi.com/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/phpcfdi/csf-scraper/blob/main/CONTRIBUTING.md
[changelog]: https://github.com/phpcfdi/csf-scraper/blob/main/docs/CHANGELOG.md
[todo]: https://github.com/phpcfdi/csf-scraper/blob/main/docs/TODO.md

[source]: https://github.com/phpcfdi/csf-scraper
[php-version]: https://packagist.org/packages/phpcfdi/csf-scraper
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/phpcfdi/csf-scraper/releases
[license]: https://github.com/phpcfdi/csf-scraper/blob/main/LICENSE
[build]: https://github.com/phpcfdi/csf-scraper/actions/workflows/build.yml?query=branch:main
[reliability]:https://sonarcloud.io/component_measures?id=phpcfdi_csf-scraper&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=phpcfdi_csf-scraper&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=phpcfdi_csf-scraper&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=phpcfdi_csf-scraper&resolved=false
[downloads]: https://packagist.org/packages/phpcfdi/csf-scraper

[badge-source]: https://img.shields.io/badge/source-phpcfdi/csf--scraper-blue.svg?logo=github
[badge-php-version]: https://img.shields.io/packagist/php-v/phpcfdi/csf-scraper?logo=php
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord
[badge-release]: https://img.shields.io/github/release/phpcfdi/csf-scraper.svg?logo=git
[badge-license]: https://img.shields.io/github/license/phpcfdi/csf-scraper.svg?logo=open-source-initiative
[badge-build]: https://img.shields.io/github/workflow/status/phpcfdi/csf-scraper/build/main?logo=github-actions
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_csf-scraper&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_csf-scraper&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/phpcfdi_csf-scraper/main?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/phpcfdi_csf-scraper/main?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/csf-scraper.svg?logo=packagist
