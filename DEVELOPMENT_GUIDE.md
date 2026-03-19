# Guia de Desenvolvimento Modular

## 📋 Checklist para Novo Módulo

### Quando Criar um Novo Módulo
✅ Feature independente com múltiplas operações CRUD  
✅ Será reutilizado em múltiplos lugares  
✅ Tem validações complexas próprias  
✅ Requer isolamento de responsabilidade  

### Estrutura Obrigatória
```bash
# Criar estrutura
mkdir -p app/Modules/NomeModulo/{Controllers,Services,Requests,Models}

# Criar 5 arquivos base
touch app/Modules/NomeModulo/Models/NomeModelo.php
touch app/Modules/NomeModulo/Services/NomeModuloService.php
touch app/Modules/NomeModulo/Requests/StoreNomeRequest.php
touch app/Modules/NomeModulo/Requests/UpdateNomeRequest.php
touch app/Modules/NomeModulo/Controllers/NomeController.php
touch app/Modules/NomeModulo/routes.php
```

---

## 🔧 Template para Model

```php
<?php

namespace App\Modules\NomeModulo\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NomeModelo extends Model
{
    use BelongsToTenant;  // ← SEMPRE incluir para multi-tenant
    use SoftDeletes;       // ← Se for deletável

    protected $fillable = [
        'company_id',      // ← SEMPRE para tenant
        // ... outros campos
    ];

    protected function casts(): array
    {
        return [
            // Type casting
        ];
    }

    public function relacao(): BelongsTo
    {
        return $this->belongsTo(OutroModelo::class);
    }
}
```

---

## 🔧 Template para Service

```php
<?php

namespace App\Modules\NomeModulo\Services;

use App\Modules\NomeModulo\Models\NomeModelo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class NomeModuloService
{
    public function __construct(private NomeModelo $model) {}

    // ← Métodos de CRUD
    public function getFiltered(array $filters): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->when($filters['search'] ?? null, $this->applySearchFilter(...))
            ->paginate($filters['per_page'] ?? 20);
    }

    public function create(array $data): NomeModelo
    {
        return $this->model->create($data);
    }

    public function getById(NomeModelo $model): NomeModelo
    {
        return $model;
    }

    public function update(NomeModelo $model, array $data): NomeModelo
    {
        $model->update($data);
        return $model;
    }

    public function delete(NomeModelo $model): bool
    {
        return $model->delete();
    }

    // ← Métodos privados para filtros
    private function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
```

---

## 🔧 Template para Form Request

```php
<?php

namespace App\Modules\NomeModulo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ← Implementar lógica de permissão se necessário
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            // ... outras regras
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'email.email' => 'O email deve ser válido',
            // ... mensagens em PT-PT
        ];
    }
}
```

---

## 🔧 Template para Controller

```php
<?php

namespace App\Modules\NomeModulo\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NomeResource;
use App\Modules\NomeModulo\Models\NomeModelo;
use App\Modules\NomeModulo\Requests\StoreNomeRequest;
use App\Modules\NomeModulo\Requests\UpdateNomeRequest;
use App\Modules\NomeModulo\Services\NomeModuloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NomeController extends Controller
{
    public function __construct(private NomeModuloService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'per_page']);
        $items = $this->service->getFiltered($filters);

        return NomeResource::collection($items);
    }

    public function store(StoreNomeRequest $request): NomeResource
    {
        $item = $this->service->create($request->validated());

        return NomeResource::make($item);
    }

    public function show(NomeModelo $item): NomeResource
    {
        $loaded = $this->service->getById($item);

        return NomeResource::make($loaded);
    }

    public function update(UpdateNomeRequest $request, NomeModelo $item): NomeResource
    {
        $updated = $this->service->update($item, $request->validated());

        return NomeResource::make($updated);
    }

    public function destroy(NomeModelo $item): JsonResponse
    {
        $this->service->delete($item);

        return response()->json(['message' => 'Eliminado com sucesso']);
    }
}
```

---

## 🔧 Template para Routes

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\NomeModulo\Controllers\NomeController;

Route::middleware(['auth:api', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('recursos', NomeController::class);
    // Route::post('recursos/{recurso}/custom', [NomeController::class, 'custom']);
});
```

---

## 📋 Checklist de Implementação

- [ ] Estrutura de diretórios criada
- [ ] Model com `use BelongsToTenant`
- [ ] Service com métodos CRUD
- [ ] Form Requests com validações (pt)
- [ ] Controller injetando Service
- [ ] routes.php registrando apiResource
- [ ] Atualizar referências em models relacionados
- [ ] Atualizar Company.php se houver hasMany
- [ ] Remover routes antigas de api.php
- [ ] Testar com Pest ou API client
- [ ] Documentação no ARCHITECTURE.md

---

## 🧪 Teste Rápido

```php
// Testar no Tinker
use App\Modules\NomeModulo\Models\NomeModelo;

NomeModelo::create(['name' => 'Test', 'company_id' => 1]);
NomeModelo::all();
```

---

## 🚀 Antes de Fazer Commit

```bash
# 1. Rodar Pint para formatar código
vendor/bin/pint --dirty

# 2. Rodar testes
php artisan test

# 3. Verificar erros
php artisan cache:clear
```

---

## ⚠️ Erros Comuns

### ❌ Não fazer:
```php
// ❌ Model sem BelongsToTenant
class Produto extends Model { }

// ❌ Validação no Controller
public function store(Request $request) {
    $validated = $request->validate([...]);
}

// ❌ Lógica de negócio no Controller
public function update(Request $request, $id) {
    $product->update([...]);
    Cache::forget(...);
}

// ❌ Importar do Models\Tenant depois de refatorizar
use App\Models\Tenant\Product;
```

### ✅ Fazer:
```php
// ✅ Model com trait
class Produto extends Model {
    use BelongsToTenant;
}

// ✅ Validação em Form Request
public function rules(): array {
    return [...]
}

// ✅ Lógica em Service
class ProdutoService {
    public function update(Produto $p, array $data) { }
}

// ✅ Importar do módulo novo
use App\Modules\Product\Models\Product;
```

---

## 📚 Referências

- [ARCHITECTURE.md](ARCHITECTURE.md) - Visão geral da arquitetura
- [REFACTORING_STATUS.md](REFACTORING_STATUS.md) - Status dos módulos

---

**Dúvidas?** Consultar arquivos já implementados em:
- `app/Modules/Product/`
- `app/Modules/Customer/`

---

## **O que é cada módulo e para que serve**

- **Product**: Gestão de produtos e serviços. Contém modelo (`Product`), validações, lógica de negócio (`ProductService`) e rotas para CRUD, buscas, categorias e impostos. Responsável por regras de preço, estoque e variantes.

- **Customer**: Gestão de clientes. Inclui modelo (`Customer`), validações e serviços para criação/atualização/listagem, limites de crédito e relação com faturas.

- **Invoice**: Emissão e gestão de faturas. Gera sequências, itens, cálculo de impostos/total, emissão (issue), e histórico de pagamentos. Serviço encapsula regras de emissão e leitura/atualização de faturas.

- **Tax**: Configuração de impostos e motivos de isenção. Fornece modelos (`Tax`, `TaxExemptionReason`), validações e endpoints para manter taxas ativas/padrão por `company` (multi-tenant).

- **User**: Autenticação e gestão de utilizadores da plataforma, associação `company_user`, papéis e permissões.

- **System**: Modelos e serviços globais (ex: `Company`, `DocumentSequence`, configurações centrais) utilizados por outros módulos para isolamento multi-tenant e configurações gerais.

> Observação: cada módulo é responsável por sua própria API (rotas em `app/Modules/<Modulo>/routes.php`) — a `routes/api.php` apenas carrega os routes dos módulos.
