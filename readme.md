# Laravel Identity Documents

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

> [!IMPORTANT]
> This is a fork from [365Werk/identitydocuments](https://github.com/365Werk/identitydocuments), and I am wishing to maintain it and keep it up-to-date, because the original repository wasn't updated for a long time and it doesn't support Laravel >= 10

Package that allows you to handle documents like passports and other documents that contain a Machine Readable Zone (MRZ).

This package allows you to process images of documents to find the MRZ, parse the MRZ, parse the Visual Inspection Zone (VIZ) and also to find and return a crop of the passport picture (using face detection).

## Installation

Using Composer

``` bash
$ composer require habibalkhabbaz/identity-documents
```

Publish config (optional)

``` bash
$ php artisan vendor:publish --provider="HabibAlkhabbaz\IdentityDocuments\IdentityDocumentsServiceProvider"
```

## Configuration
### Services

The first important thing to know about the package is that you can use any OCR and or Face Detection API that you want. This package is not doing any of those itself.

#### Google Vision Service

Included with the package is a `Google` service class that will be loaded for both OCR and Face Detection by default. If you wish to use the Google service, no further configuration is required besides providing your credentials. To do this, make a service account and download the JSON key file. Then convert the JSON to a PHP array so it can be used as a normal Laravel config file. Your config file would have to be called `google_key.php`, be placed in the config folder and look like this:

```php
return [
    "type" => "service_account",
    "project_id" => "",
    "private_key_id" => "",
    "private_key" => "",
    "client_email" => "",
    "client_id" => "",
    "auth_uri" => "",
    "token_uri" => "",
    "auth_provider_x509_cert_url" => "",
    "client_x509_cert_url" => "",
];
```
#### Creating Custom Services
If you want to use any other API for OCR and/or Face Detection, you can make your own service, or take a look at our list of available services not included in the main package (WIP).

Making a service is relatively easy, if you want to make a service that does the OCR, all you have to do is create a class that implements `HabibAlkhabbaz\IdentityDocuments\Interfaces\Ocr`. Similarly, there is also a `HabibAlkhabbaz\IdentityDocuments\Interfaces\FaceDetection` interface. To make creating custom services even easier you can use the following command:
```bash
$ php artisan id:service <name> <type>
```
Where `name` is the `ClassName` of the service you wish to create, and `type` is either `Ocr`, `FaceDetection` or `Both`. This will create a new (empty) service for you in your `App\Services` namespace implementing the `Ocr`, `FaceDetection` or both interfaces.

## Usage
### Basic usage
Create a new Identity Document with a maximum of 2 images (optional) in this example we'll use a POST request that includes 2 images on our example controller.
```php
use Illuminate\Http\Request;
use HabibAlkhabbaz\IdentityDocuments\IdentityDocument;

class ExampleController {
	public function id(Request $request){
		$document = new IdentityDocument($request->front, $request->back);
	}
}
```
> [!WARNING]
> In this example I use uploaded files, but you can use any files [supported by Intervention](http://image.intervention.io/api/make)

There are now a few things we can do with this newly created Identity Document. First of all finding and returning the MRZ:
```php
$mrz = $document->getMrz();
```

We can then also get a parsed version of the MRZ by using
```php
$parsed = $document->getParsedMrz();
```

As the MRZ only allows for A-Z and 0-9 characters, anyone with accents in their name would not get a correct first or last name from the MRZ. To (attempt to) find the correct first and last name on the VIZ part of the document, use:
```php
$viz = $document->getViz();
```
This will return an array containing both the found first and last names as well as a confidence score. The confidence score is a number between 0 and 1 and shows the similarity between the MRZ and VIZ version of the name. Please not that results can differ based on your system's `iconv()` implementation.

To get the passport picture from the document use:
```php
$face = $document->getFace()
```
This returns an `Intervention\Image\Image`

### Get all of the above
  If you wish to use all of these in a simplified way, you can also use the static `all()` method, which also expects up to two images as argument. For example:
  ```php
use Illuminate\Http\Request;
use HabibAlkhabbaz\IdentityDocuments\IdentityDocument;

class ExampleController {
	public function id(Request $request){
		$response = IdentityDocument::all($request->front, $request->back);
		return response()->json($response);
	}
}
```
The `all()` method returns an array that looks like this:
```php
[
	'type' => 'string', // TD1, TD2, TD3, MRVA, MRVB
	'mrz' => 'string', // Full MRZ
	'parsed' => [], // Array containing parsed MRZ
	'viz' => [], // Array containing parsed VIZ
	'face' => 'string', // Base64 image string
]
```
As you can see this includes all the above mentioned methods, plus the `$document->type` variable. The detected face will be returned as a base64 image string, with an image height of 200px.

### Merging images
There are a couple of methods that will configure how the Identity Document is handled. First of all there's the `mergeBackAndFrontImages()` method. This method can be used to reduce the amount of OCR API calls have to be made. Images will be stacked on top of each other when this method is used. Please note that this method would have to be used __before__ the `getMrz()` method. Example:
```php
use Illuminate\Http\Request;
use HabibAlkhabbaz\IdentityDocuments\IdentityDocument;

class ExampleController {
	public function id(Request $request){
		$document = new IdentityDocument($request->front, $request->back);
		$document->mergeBackAndFrontImages();
		$mrz = $document->getMrz();
	}
}
```
> [!WARNING]
> Please note that merging images might cause high memory usage, depending on the size of your images

If you wish to use the static `all()` method and merge the images, publish the package's config file and enable it in there. Note that changing the option in the config will __only__ apply to the `all()` method. Default config value:
```php
	'merge_images' => false, // bool
```

### Setting an OCR service
If you have made a custom OCR service or are using one different than the default Google service, you can use the `setOcrService()` method. For example let's say we've creating a new `TesseractService` using the methods described above, we can use it for OCR like this:
```php
use Illuminate\Http\Request;
use App\Services\TesseractService;
use HabibAlkhabbaz\IdentityDocuments\IdentityDocument;

class ExampleController {
	public function id(Request $request){
		$document = new IdentityDocument($request->front, $request->back);
		$document->setOcrService(TesseractService::class);
		$mrz = $document->getMrz();
	}
}
```
If you wish to use the `all()` method, publish the package's config and set the correct service class there.

### Setting a Face Detection Service
This can be done in a similar way as the OCR service, using the `setFaceDetectionService()` method. For example:
```php
use Illuminate\Http\Request;
use App\Services\AmazonFdService;
use HabibAlkhabbaz\IdentityDocuments\IdentityDocument;

class ExampleController {
	public function id(Request $request){
		$document = new IdentityDocument($request->front, $request->back);
		$document->setFaceDetectionService(AmazonFdService::class);
		$mrz = $document->getFace();
	}
}
```
If you wish to use the `all()` method, publish the package's config and set the correct service class there.

### Other methods
`addBackImage()` sets the back image of the `IdentityDocument`.
`addFrontImage()` sets the front image of the `IdentityDocument`.
`setMrz()` sets the `IdentityDcoument` MRZ, for if you just wish to use the parsing functionality.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Credits

- [Hergen Dillema](https://github.com/HergenD)
- [Habib Alkhabbaz](https://github.com/habibalkhabbaz)
- [All Contributors][link-contributors]

## License

Please see the [license file](LICENSE) for more information.



[ico-version]: https://img.shields.io/packagist/v/habibalkhabbaz/identity-documents.svg?style=flat-square

[ico-downloads]: https://img.shields.io/packagist/dt/habibalkhabbaz/identity-documents.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/habibalkhabbaz/identity-documents

[link-downloads]: https://packagist.org/packages/habibalkhabbaz/identity-documents

[link-contributors]: ../../contributors
