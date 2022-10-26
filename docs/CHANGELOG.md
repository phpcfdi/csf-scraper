# CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Cambios no liberados en una versión

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de
versión, aunque sí su incorporación en la rama principal de trabajo, generalmente se tratan de cambios en el desarrollo.

## Listado de cambios

### Versión 0.1.4 2022-10-25

- Se hacen más estrictas las correcciones de los regímenes usando expresiones regulares.

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
