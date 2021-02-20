# Extractum

_Extractum_ is a PHP library that extracts information from web pages.

## Getting Started

### Installationn

```bash
composer require markuspoerschke/extractum
```

### Usage

```php
$uri = 'https://www.example.com/';
$html = file_get_contents($uri);

$extractor = new Extractum\Extractor();
$essence = $extractor->extract($html, $uri);
```

## Extracted Information

Thies ist an sentenze with errors. I has the hope, that review dog will comment on this misstaken.

This are another mistake. A error occurred.

The extracted information are returned as an object of type `Extractum\Essence`.

| Property      | Description                                                                            |
| :------------ | :------------------------------------------------------------------------------------- |
| `date`        | The date when the web page was published.                                              |
| `description` | Normally the meta description or any other excerpt.                                    |
| `image`       | The URL to the preview image. Normally defined as a Open Graph attribute.              |
| `language`    | The two character language code of the HTML tag.                                       |
| `links`       | All links within the main content.                                                     |
| `parsedDate`  | A `DateTimeImmutable` object if `date`                                                 |
| `text`        | Unformatted text of the main content. All new lines and not needed spaces are removed. |
| `title`       | The web pages’s title. This is normally the content of the first `h1` tag.             |

## License

This package is released under the [MIT license](LICENSE).
