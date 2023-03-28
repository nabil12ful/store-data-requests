# Store Data Requests for Laravel

>## Support laravel v8+

Store data to database from (Form Requsts Fields) by Model

## Installation

```php
composer require nabil12ful/store-data-requests
```
To publish
```php
php artisan vendor:publish --provider="Nabil\StoreDataRequestsServiceProvider"
```
## Usage


```php
php artisan make:controller UserController -r
```

### Or

```php
php artisan make:controller UserController -m User
```

Change this info after controller generation & Congratolations :smile:

```php
protected $model = \App\Models\User::class;

protected $folderBlade = 'backend.user'; // View folder name OR path

protected $uploadPath = 'upload/user';

protected $columns = [
	// table columns & fields name
	'name',
	'email',
];

protected $mediaColumns = [
    // columns name have a media files like [Image, Pdf, Doc, etc...]
    'image'
];
```

### Views Folder 
> ```
> +--- views
> |    +--- backend
> |    |    +--- user
> |    |    |    +--- index.blade.php
> |    |    |    +--- create.blade.php
> |    |    |    +--- edit.blade.php
> |    |    |    +--- show.blade.php
> |    |    +--- product
> |    |    |    +--- index.blade.php
> |    |    |    +--- create.blade.php
> |    |    |    +--- edit.blade.php
> |    |    |    +--- show.blade.php
> |    |    +--- index.blade.php
> ```

### To use Vaildation 
Create request validation by
```php
php artisan make:request UserStoreRequest
```

```php
public function store(UserStoreRequest $request): RedirectResponse
{
	StoreDataRequests::model($this->model)->make($request, $this->columns)->store($this->uploadPath);
}
```

#### Or use
First change columns array like:

```php
protected $columns = [
	// table columns & fields name with rules
	'name' => 'required|string|min:5',
	'email' => 'required|email',
];

protected $mediaColumns = [
	// table columns & fields name has files with rules
	'image' => 'required|image',
];
```
And use `storeValidated`, `updateValidated` methods

```php
public function store(Request $request)
{
	$result = StoreDataRequests::model($this->model)->make($request, $this->columns)->storeValidated('upload/users');

    if(isset($result->id))
    {
        toastr()->success('The data has been stored successfully', 'Success');
        return redirect()->back();
    }else{
        return back()->withInput()->withErrors($result);
    }
}
```

On update

```php
public function update($id, Request $request)
{
	$result = StoreDataRequests::model($this->model)->make($request, $this->columns, $this->mediaColumns)->updateHasFilesValidate($id, $this->uploadPath);

    if(!$result)
    {
        toastr()->success('The data has been updated successfully', 'Success');
        return redirect()->back();
    }else{
        return back()->withInput()->withErrors($result);
    }
}
```

## Delete records

Enter path Param If you want to delete files from uploads path with delete a record:
    

```php
StoreDataRequests::model($this->model)->delete(decrypt($id), $this->uploadPath);
```


## Use our service in simple Controller
First import our plugin in your Controller file
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
## Upload files with insert data

use:

```php
StoreDataRequests::model('Prodect')->make($request, ['title','description'], ['image'])->store('path/to/upload');
```
or update has file:

```php
StoreDataRequests::model('Prodect')->make($request, ['title','description'], ['image'])->update($id, 'path/to/upload');
```

### Thanks for Eng/Sameh Mohamed
## Made with :heart: By Eng/Nabil Hamada
