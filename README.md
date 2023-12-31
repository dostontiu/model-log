# model-log
Create history log for laravel model. This package can be used for creating log form model.

<!--/delete-->

## Installation
You should add repository to your composer.json file

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/dostontiu/model-log"
    }
]
```

You can install the package via composer:

```bash
composer require dostontiu/model-log
```

## Configuration

1. You should publish migration files
```bash
php artisan vendor:publish --provider="Dostontiu\ModelLog\ModelLogServiceProvider"
```
2. Run migration
```bash
php artisan migrate
```
## Usage

You should crete model log service, and it extends ModelLogService. It contains toArray() method.
```php
namespace App\Services\ModelLog;

use Dostontiu\ModelLog\Services\ModelLogService;

class UserLog extends ModelLogService
{
    public function toArray($model)
    {
        return [
            'role_id' => $model->role_id,
            'role_name' => $model->role->name ?? '',
            'organization_id' => $model->organization_id,
            'organization_name' => $model->organization->name ?? "",
            'full_name' => $model->full_name,
            'is_active' => $model->is_active ? 'Active' : 'Not active',
        ];
    }
}
```

Your controller should be this.

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ModelLog\UserLog;

class UserController extends ModelLogService
{
    public function update(Request $request, $id)
    {
        $old_model = $model = User::find($id);
        
        $model->update([
            'role_id' => $request->role_id,
            'full_name' => $request->full_name,
        ]);    
      
        new UserLog($old_model, $model);
        
        return redirect()->back()->withSuccess("Updated successfully!");
    }
}
```

## License

The MIT License (MIT).