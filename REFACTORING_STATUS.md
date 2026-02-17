# Refatorização Modular - Status



## **O que é cada módulo e para que serve**

- **Product**: Gestão de produtos e serviços. Contém modelo (`Product`), validações, lógica de negócio (`ProductService`) e rotas para CRUD, buscas, categorias e impostos. Responsável por regras de preço, estoque e variantes.

- **Customer**: Gestão de clientes. Inclui modelo (`Customer`), validações e serviços para criação/atualização/listagem, limites de crédito e relação com faturas.

- **Invoice**: Emissão e gestão de faturas. Gera sequências, itens, cálculo de impostos/total, emissão (issue), e histórico de pagamentos. Serviço encapsula regras de emissão e leitura/atualização de faturas.

- **Tax**: Configuração de impostos e motivos de isenção. Fornece modelos (`Tax`, `TaxExemptionReason`), validações e endpoints para manter taxas ativas/padrão por `company` (multi-tenant).

- **User**: Autenticação e gestão de utilizadores da plataforma, associação `company_user`, papéis e permissões.

- **System**: Modelos e serviços globais (ex: `Company`, `DocumentSequence`, configurações centrais) utilizados por outros módulos para isolamento multi-tenant e configurações gerais.

> Observação: cada módulo é responsável por sua própria API (rotas em `app/Modules/<Modulo>/routes.php`) — a `routes/api.php` apenas carrega os routes dos módulos.




## ✅ Módulos Refatorizados

### 1. **Módulo Product** (Completo)
```
app/Modules/Product/
├── Controllers/ProductController.php      ✅
├── Services/ProductService.php            ✅
├── Requests/
│   ├── StoreProductRequest.php            ✅
│   └── UpdateProductRequest.php           ✅
├── Models/Product.php                     ✅
└── routes.php                             ✅
```

**Funcionalidades:**
- Listar produtos com filtros (busca, categoria, tipo, ativo)
- Criar produto com validação
- Atualizar produto
- Deletar produto
- Carregamento otimizado (tax, category)

---

### 2. **Módulo Customer** (Completo)
```
app/Modules/Customer/
├── Controllers/CustomerController.php     ✅
├── Services/CustomerService.php           ✅
├── Requests/
│   ├── StoreCustomerRequest.php           ✅
│   └── UpdateCustomerRequest.php          ✅
├── Models/Customer.php                    ✅
└── routes.php                             ✅
```

**Funcionalidades:**
- Listar clientes com filtros (busca, ativo)
- Criar cliente com validação
- Atualizar cliente
- Deletar cliente
- Isolamento multi-tenant automático

---

### 3. **Módulo User** (Existente - pode ser migrado)
```
app/Modules/User/
├── Controllers/UserController.php         ✅
├── Services/
├── Requests/
├── Models/
└── routes.php                             ✅
```

---

## 📋 Estrutura de Rotas

**Carregamento Automático:**
```php
// routes/api.php
foreach (glob(app_path('Modules/*/routes.php')) as $routeFile) {
    require $routeFile;
}
```

**EndPoints Disponíveis:**
- `GET/POST /api/v1/products`
- `GET/PUT/DELETE /api/v1/products/{id}`
- `GET/POST /api/v1/customers`
- `GET/PUT/DELETE /api/v1/customers/{id}`
- `GET/PUT /api/v1/users/me`
- `GET /api/v1/users`

---

## 🔄 Mudanças Implementadas

### Modelos Movidos
- `App\Models\Tenant\Product` → `App\Modules\Product\Models\Product`
- `App\Models\Tenant\Customer` → `App\Modules\Customer\Models\Customer`

### Referências Atualizadas
| Arquivo | Mudança |
|---------|---------|
| `Company.php` | customers() → App\Modules\Customer\Models\Customer |
| `Company.php` | products() → App\Modules\Product\Models\Product |
| `Invoice.php` | belongsTo Customer atualizado para novo módulo |
| `InvoiceItem.php` | belongsTo Product atualizado para novo módulo |
| `Category.php` | hasMany Product atualizado para novo módulo |
| `routes/api.php` | Rotas V1 removidas, carregadas pelos módulos |

---

## 🎯 Padrão de Desenvolvimento

Cada módulo segue a estrutura:

```
Controller
    ↓ (usa)
Service (lógica de negócio)
    ↓ (usa)
Model (persistência)

Request (valida entrada)
    ↓ (usado pelo)
Controller
```

**Exemplo de Flow:**
```
POST /api/v1/products
    ↓
ProductController@store(StoreProductRequest)
    ↓
StoreProductRequest valida dados
    ↓
ProductService::create($validated)
    ↓
Product::create() (com isolamento tenant)
    ↓
ProductResource formata JSON
```

---

## 📦 Próximos Passos

### Fase 2: Refatorizar Invoice
```
app/Modules/Invoice/
├── Controllers/InvoiceController.php
├── Services/InvoiceService.php
├── Requests/
│   ├── StoreInvoiceRequest.php
│   ├── UpdateInvoiceRequest.php
│   └── IssueInvoiceRequest.php
├── Models/Invoice.php
└── routes.php
```

### Fase 3: Refatorizar Estruturas Relacionadas
- Tax, Category, PaymentTerm em módulos próprios
- DocumentSequence, AuditLog
- Payment, CashRegister

### Fase 4: Testes e Documentação
- Feature tests para cada módulo (Pest)
- API Documentation (OpenAPI/Swagger)
- Documentação de cada serviço

---

## 🔐 Multi-Tenant Isolation

Todos os modelos usam:
```php
use App\Models\Traits\BelongsToTenant;

class Product extends Model {
    use BelongsToTenant;
}
```

**Benefício:** Isolamento automático por `company_id`

---

## 📝 Validação e Erros

Todos os Form Requests possuem mensagens customizadas em **Português**:

```php
'tax_id.unique' => 'Este SKU já foi utilizado',
'name.required' => 'O nome do produto é obrigatório',
```

---

## 🚀 Como Executar

### Teste as rotas com curl:
```bash
# Listar produtos
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer TOKEN"

# Criar cliente
curl -X POST http://localhost:8000/api/v1/customers \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"João Silva","email":"joao@example.com"}'
```

### Rodar testes:
```bash
php artisan test
php artisan test tests/Feature/Product --filter=testListProducts
```

---

**Status:** ✅ Cores verde - 2 módulos refatorizados com sucesso!
