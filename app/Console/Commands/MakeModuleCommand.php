<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name : Name of the module}';
    protected $description = 'Create a full module (Model, Controller, Service, Request)';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        $modulePath = app_path("Modules/{$name}");

        $folders = [
            'Models',
            'Controllers',
            'Services',
            'Requests',
        ];

        // Create module folders
        foreach ($folders as $folder) {
            $path = "{$modulePath}/{$folder}";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        // ---- Create Model ----
        $modelPath = "{$modulePath}/Models/{$name}.php";
        file_put_contents(
            $modelPath,
"<?php

namespace App\Modules\\{$name}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class {$name} extends Model
{
    public \$incrementing = false;
    protected \$keyType = 'string';

    protected static function booted()
    {
        static::creating(function (\$model) {
            if (!\$model->id) {
                \$model->id = Str::uuid()->toString();
            }
        });
    }
}
"
);

        // ---- Create Controller ----
        $controllerPath = "{$modulePath}/Controllers/{$name}Controller.php";
        file_put_contents(
            $controllerPath,
"<?php

namespace App\Modules\\{$name}\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\\{$name}\Services\\{$name}Service;
use Illuminate\Http\Request;
use App\Modules\\{$name}\Requests\\{$name}Request;

class {$name}Controller extends Controller
{
    public function __construct({$name}Service \$service)
    {
        \$this->Service = \$service;
    }

    public function index()
    {
        try {
           \$data = \$this->service->getAll();
            return response()->json($\$data);
        } catch (\Exception \$e) {
            return response()->json(['error' => \$e->getMessage()], 500);
        }
    }

    public function show(\$id)
    {
        try {
           \$data = \$this->service->getById(\$id);
            if (!\$data) {
                return response()->json(['error' => 'Not Found'], 404);
            }
            return response()->json(\$item);
        } catch (\Exception \$e) {
            return response()->json(['error' => \$e->getMessage()], 500);
        }
    }
    
    public function store({$name}Request \$request)
    {
        try {
            \$data = \$this->service->create(\$request->all());
            return response()->json(\$data, 201);
        } catch (\Exception \$e) {
            return response()->json(['error' => \$e->getMessage()], 500);
        }
    }

    public function update(Request \$request, \$id)
    {
        try {
             
           \$data = \$this->service->update(\$id, \$request->all());
            if (!\$data) {
                return response()->json(['error' => 'Not Found'], 404);
            }
            return response()->json(\$data);
        } catch (\Exception \$e) {
            return response()->json(['error' => \$e->getMessage()], 500);
        }
    }

    public function destroy(\$id)
    {
        try {
            \$deleted = \$this->service->delete(\$id);
            if (!\$deleted) {
                return response()->json(['error' => 'Not Found'], 404);
            }
            return response()->json(null, 204);
        } catch (\Exception \$e) {
            return response()->json(['error' => \$e->getMessage()], 500);
        }
    }

}
            "
        );

        // ---- Create Service ----
        $servicePath = "{$modulePath}/Services/{$name}Service.php";
        file_put_contents(
$servicePath,
"<?php

namespace App\Modules\\{$name}\Services;

class {$name}Service
{
}
"
        );

        // ---- Create Request ----
        $requestPath = "{$modulePath}/Requests/{$name}Request.php";
        file_put_contents(
            $requestPath,
"<?php

namespace App\Modules\\{$name}\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {$name}Request extends FormRequest
{
public function authorize(): bool
{
return true;
}

public function rules(): array
{
return [];
}
}
"
);
        // ---- Create routes.php ----
        $routesPath = "{$modulePath}/routes.php";
        $lowerName = strtolower($name);

        file_put_contents(
            $routesPath,
"<?php

use Illuminate\Support\Facades\Route;
use App\Modules\\{$name}\Controllers\\{$name}Controller;

Route::prefix('{$lowerName}')->group(function () {
Route::get('/', [{$name}Controller::class, 'index']);
});
"
);


        $this->info("Module '{$name}' created successfully.");
        return Command::SUCCESS;
    }
}
