# model-log
Create history log for laravel model

# Package description

---
This package can be used for creating log form model. Follow these steps to get started:

1. You should run migration
2. Have fun creating your package.
---
<!--/delete-->

## Installation
You should add repository to your composer.json file

:vendor_slug/:package_slug

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

## Usage

You should run migration
```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('model_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model_name')->nullable();
            $table->integer('model_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('message')->nullable();
            $table->string('ip')->nullable();
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_logs');
    }
};
```


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

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## License

The MIT License (MIT).