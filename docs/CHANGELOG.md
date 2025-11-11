# CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Cambios no liberados en una versión

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de
versión, aunque sí su incorporación en la rama principal de trabajo, generalmente se tratan de cambios en el desarrollo.

## Listado de cambios

### Versión 0.2.0 2025-11-10

- Se asegura la compatibilidad con PHP 8.3 y PHP 8.4.
- Se elimina la compatibilidad con PHP 8.0 y PHP 8.1.
- Se actualiza el año de la licencia a 2025.

Cambios al entorno de desarrollo:

- Se corrige la integración con SonarQube Cloud.
- Se actualiza a PHPUnit 11.5.
- Se actualizan las configuraciones de estándar de código para `phpcs` y `php-cs-fixer`.
- Se agrega `composer-normalize` a las herramientas de desarrollo.
- En los flujos de trabajo:
  - Se ejecutan los trabajos en PHP 8.4.
  - Se agrega PHP 8.3 y PHP 8.4 a la matriz de pruebas.
  - Se agrega el trabajo `composer-normalize`.
  - Se ejecutan las acciones en la versión 4.
  - Se usa la variable `php-version` en singular en la matriz de pruebas.
  - Se usa la variable `operating-system` en singular en la matriz de pruebas.
  - Se usa una única configuración para Linux o Windows al configurar PHP.
- Se actualizan las herramientas de desarrollo.

#### Mantenimiento 2023-06-17

- Se agrega un último test para alcanzar el 100% de cobertura de código.
- Se corrige en este archivo, que "los flujos de trabajo corren en PHP 8.2", decía "PHP 8.0".

### Versión 0.1.7 2024-01-10

- Se arregla el problema para CSF que traen localidad en lugar de colonia. Agradecimientos a @luffynando, @blacktrue y @eclipxe13 por sus aportes.
- Se actualiza la licencia. Feliz año 2024.
- Se arreglan algunos typos en el README.md
- Se actualizan las herramientas de desarrollo.
- Se consigue el 100% de cobertura de código.

### Versión 0.1.6 2023-06-17

- Se agrega el método `Scraper::getClient()` para obtener el cliente con el que fue construido el objeto.
- Se corrige el nombre del método `PdfToTextConvertException::getOutput`, antes `getGetOutput`.
- Se actualiza el año de licencia.
- Se corrige la liga del proyecto en el archivo `CONTRIBUTING.md`.
- Se actualiza la insignia de construcción en el archivo `README.md`.
- Se actualiza el archivo de configuración de SonarCloud para excluir correctamente los archivos en `tests/_files`.
- Para los flujos de trabajo:
  - Se permite ejecutarlos a petición.
  - Los trabajos se ejecutan en PHP 8.2.
  - No se instala `composer` cuando no es necesario.
  - Se sustituye la directiva `::set-output` con `$GITHUB_OUTPUT`.
- Se actualizan las herramientas de desarrollo.
- En las pruebas:
  - Se crean mejores casos para el manejo de excepciones provenientes de la lectura de un archivo PDF.
  - Se consigue el 100% de cobertura de código.

### Versión 0.1.5 2022-10-28

#### Regímenes que terminan en punto `.`

Se elimina de regímenes el punto final, que viene en algunos regímenes como
*"Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas."*.

#### Propiedades mágicas en `Persona`

El método *setter* mágico permite establecer cualquier tipo de datos en `$data`.
El método *getter* mágico solo permitía devolver cadenas de caracteres.
Se cambió este comportamiento para devolver cualquier tipo de datos.

El método *setter* mágico daba prioridad al método compuesto `set<Propiedad>()` (si existía).
El método *getter* solo devolvía datos del almacén local `$data`.
Se cambió este comportamiento para también darle prioridad al método `get<Propiedad>()` (si existe).

El método *isset* mágico cambia la comprobación a verificar que el resultado del método mágico *getter* no devuelva nulo.
De esta forma se incluye la comprobación para propiedades del objeto.

#### Otros cambios de mantenimiento

- En los flujos de integración continua:
  - Se agrega la versión de PHP 8.2 a la matriz del trabajo para pruebas.
  - Se corrige la instalación de `poppler-utils`, se quedaba colgado en `nektos/act`.
- Se modifican y simplifican varias anotaciones de *PHPDoc*.
- Se corrige el nombre del método `setfechaUltimoCambioSituacion` a `setFechaUltimoCambioSituacion`.
- Se actualiza el archivo de configuración de `php-cs-fixer`:
  - Cambiar la regla `no_trailing_comma_in_singleline_array` a `no_trailing_comma_in_singleline`.
  - Se actualizan las reglas a PHP 8.0.
  - Se activan las reglas `class_attributes_separation`, `trailing_comma_in_multiline` y `ordered_imports`.

### Versión 0.1.4 2022-10-25

- Se hacen más estrictas las correcciones de los regímenes usando expresiones regulares.
- Se agregan pruebas para el método `Regimen::setFechaAlta`.

### Versión 0.1.3 2022-10-25

- El ejemplo del `README.md` mostraba que se obtenían los datos usando el método `$scraper->data()`, sin embargo este método ya no existe más y el método usado es: `$scraper->obtainFromRfcAndCif()`.
- Se agrega a la documentación cómo obtener los datos usando la ruta local del archivo PDF a través del método `$scraper->obtainFromPdfPath()`.
- Se elimina de regímenes la palabra PM, que viene en algunos regímenes como "Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras PM".

### Versión 0.1.2 2022-06-28

#### Método fábrica de `Scraper`

Se agrega el método para fabricar estáticamente un objeto `Scraper` con la configuración de `curl` adecuada.
Lamentablemente, el sitio del SAT utiliza un esquema de seguridad anticuado que requiere configuración especial.

#### Refactorización de excepciones

Se agrega la excepción `CifDownloadException` que se genera cuando no se pudo descargar la página web de datos fiscales.

Se agrega `CsfScraperException` como una interfaz vacía para identificar las excepciones generadas por esta librería.

Se elimina `ShellExecException` y se sustituye por `PdfToTextConvertException`.

Se agregan las anotaciones `@throws` a los métodos para identificar que generan excepciones.

#### Refactorizaciones

Pequeñas limpiezas de código y a partes específicas:

- Se refactoriza el código de la clase interna `CsfExtractor` para mejorar su intención.
- Se refactoriza el código de la clase `PdfToText` para que use `ShellExec` al buscar por el ejecutable `pdftotext`.

#### Uso de Synfony/Process

Se usa `Symfony/Process` en lugar de la clase interna `ShellExec`.

### Versión 0.1.1 2022-06-27

Se agregan los datos de `RFC` y `IDCIF` a la clase base `Persona`.
No eran accesibles si se obtenían los datos directamente de un archivo PDF.

### Versión 0.1.0 2022-06-21

Primera versión pública disponible.
