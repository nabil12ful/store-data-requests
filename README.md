# Store Data Requests for Laravel

>## Support laravel v8+

Store data to database from (Form Requsts Fields) by Model

## Installation

```php
composer require nabil12ful/store-data-requests
```
To publish
```php
php artisan vendor:publish --provider="Nabil\StoreDataRequestsServiceProfider"
```
## Usage

```
php artisan make:serivce 
```

```php
php artisan make:controller ProdectController -r
```

Change this info after controller generation & Congratolations :smile:
```php
protected $data = [
	'folder' => 'backend.',
	'var' => 'admin.',
	'Models' => 'App\Models\{Model}', // {Model Name}
	'folderBlade' => 'folder', // blade path
	'upload' => 'upload/folder', // upload path
];

protected $columns = [
	// table columns & fields name
	'image',
	'name',
	'email',
];

protected $mediaCols = [
    // columns name have a media files for delete file whene delete A record like [Image]
    'image'
];
```
### To use Vaildation 
Create request validation by
```php
php artisan make:request ProdectStoreRequest
```

```php
public function store(ProdectStoreRequest $request): RedirectResponse
{
	StoreDataRequests::model($this->data['Models'])->make($request, $this->columns)->store();
}
```

## Use our service in simple Controller
Firest import our plugin in your Controller file
```php
use Nabil\StoreDataRequests;
```
And write this code to create a new record:

```php
public function store(Request $request)
{
	StoreDataRequests::model('Prodect')->make($request, ['title','description'])->store();
}
```

Or write this code to update old record in Database model:
```php
public function update(Request $request, $id)
{
	StoreDataRequests::model('Prodect')->make($request, ['title','description'])->update($id);
}
```
## Upload files

use:

```php
StoreDataRequests::model('Prodect')->make($request, ['title','description'])->storeHasFile('path/to/upload');
```
or update has file:

```php
StoreDataRequests::model('Prodect')->make($request, ['title','description'])->updateHasFile($id, 'path/to/upload');
```

# Thanks
### Made with :heart: By Eng/Nabil Hamada & Eng/Sameh Mohamed
