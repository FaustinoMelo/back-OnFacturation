# Arquitetura Modular - Multi-Tenant

## Estrutura do Projeto

A aplicação foi refatorizada para uma arquitetura **modular baseada em features**, seguindo o padrão **SOLID** e **multi-tenant**.

### Árvore de Módulos

```
app/Modules/
├── Product/              # Módulo de Produtos
│   ├── Controllers/
│   │   └── ProductController.php
│   ├── Services/
│   │   └── ProductService.php
│   ├── Requests/
│   │   ├── StoreProductRequest.php
│   │   └── UpdateProductRequest.php
│   ├── Models/
│   │   └── Product.php
│   └── routes.php
│
├── User/                 # Módulo de Utilizadores
│   ├── Controllers/
│   │   └── UserController.php
│   ├── Services/
│   ├── Requests/
│   ├── Models/
│   └── routes.php
│
└── [... outros módulos]
```

## Padrões Implementados

### 1. **Separação de Responsabilidades**

```
Controller  ← Recebe requisição, delega ao Service, retorna resposta
    ↓
Service     ← Lógica de negócio centralizada, reutilizável
    ↓
Model       ← Persistência e relacionamentos
    ↓
Request     ← Validação de entrada
```

### 2. **Form Requests**

Todas as validações estão em `Requests/` classes:
- `StoreProductRequest.php` - Validação para criação
- `UpdateProductRequest.php` - Validação para atualização
- Erros customizados em português

### 3. **Services**

`ProductService.php` encapsula toda lógica:
- Filtros e busca
- CRUD operations
- Eager loading otimizado (evita N+1)
- Métodos privados para cada filtro

### 4. **Models Modulares**

Modelos em `app/Modules/Product/Models/Product.php`:
- Namespace: `App\Modules\Product\Models`
- Traits para multi-tenant: `BelongsToTenant`
- Soft deletes habilitado
- Casts tipados

### 5. **Routes Automáticas**

Em `routes/api.php`:
```php
foreach (glob(app_path('Modules/*/routes.php')) as $routeFile) {
    require $routeFile;
}
```

Cada módulo registra suas rotas automaticamente via `routes.php`.

## Workflow de Requisição

```
POST /api/v1/products
    ↓
Router carrega routes/api.php → Modules/Product/routes.php
    ↓
ProductController@store(StoreProductRequest $request)
    ↓
StoreProductRequest valida entrada com regras customizadas
    ↓
ProductService::create($validated) executa lógica
    ↓
Product::create() persiste dados com isolamento tenant
    ↓
ProductResource formata resposta JSON
```

## Multi-Tenant Isolation

Todos os modelos utilizam o trait `BelongsToTenant`:
- Associa automaticamente `company_id` ao modelo
- Filtra por tenant na query
- Protege contra acesso cross-tenant

```php
class Product extends Model {
    use BelongsToTenant; // Isolamento automático
}
```

## Criando um Novo Módulo

### Passo 1: Criar Structure
```bash
mkdir -p app/Modules/NomeModulo/{Controllers,Services,Requests,Models}
```

### Passo 2: Criar Model
```php
// app/Modules/NomeModulo/Models/NomeModelo.php
namespace App\Modules\NomeModulo\Models;
use App\Models\Traits\BelongsToTenant;

class NomeModelo extends Model {
    use BelongsToTenant;
    // ...
}
```

### Passo 3: Criar Service
```php
// app/Modules/NomeModulo/Services/NomeModuloService.php
class NomeModuloService {
    public function create(array $data): NomeModelo { }
    public function update(NomeModelo $model, array $data): NomeModelo { }
    public function delete(NomeModelo $model): bool { }
}
```

### Passo 4: Criar Form Requests
```php
// app/Modules/NomeModulo/Requests/Store...Request.php
class StoreNomeRequest extends FormRequest {
    public function rules(): array { }
    public function messages(): array { } // PT-PT
}
```

### Passo 5: Criar Controller
```php
// app/Modules/NomeModulo/Controllers/NomeController.php
class NomeController extends Controller {
    public function __construct(private NomeModuloService $service) {}
    // usar $this->service para lógica
}
```

### Passo 6: Registrar Routes
```php
// app/Modules/NomeModulo/routes.php
Route::middleware(['auth:api', 'tenant.isolation'])->prefix('v1')->group(function (): void {
    Route::apiResource('recursos', NomeController::class);
});
```

## Benefícios

✅ **Modularidade** - Cada feature é independente  
✅ **Testabilidade** - Services facilmente testáveis  
✅ **Escalabilidade** - Adicionar novos módulos sem afetar existentes  
✅ **Multi-Tenant** - Isolamento automático por company  
✅ **Validação Centralizada** - Form Requests customizadas  
✅ **Lógica Reutilizável** - Services compartilháveis  

## Próximos Passos

1. Refatorizar Customer módulo
2. Refatorizar Invoice módulo
3. Criar testes para cada módulo
4. Documentar APIs com OpenAPI
