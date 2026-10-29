# Histórico e contexto do Ubatuba North Shore

Documento vivo do projeto: visão geral, linha do tempo, arquitetura, regras de
negócio, decisões, riscos e diário de mudanças. Serve de contexto para pessoas e
agentes. Atualize a cada mudança relevante.

Última atualização: 2026-10-28.

## 1. Visão geral

Plataforma de locação de imóveis por temporada em Ubatuba/SP.

- Site público: catálogo, busca, página do imóvel, checkout, blog, avaliações.
- Painel administrativo em `/admin`: imóveis, temporadas, calendário, reservas,
  usuários, permissões, posts, slides, menus, relatórios e configurações.
- API em `/api/v1`: calendário, disponibilidade, cálculo e criação de reserva.
- Pagamentos via Mercado Pago (cartão e PIX) com webhook assíncrono.
- Notificações por e-mail e banco para novas reservas e pagamentos confirmados.

## 2. Stack

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.3, Laravel 10 |
| UI reativa | Livewire 3, Alpine.js 3 |
| Site | TailwindCSS 3, Flatpickr, IMask, SweetAlert2, Toastify |
| Painel | AdminLTE (Bootstrap) via `jeroennoten/laravel-adminlte` |
| Build | Vite 7 |
| Banco | MySQL |
| Auth/ACL | Sanctum, Spatie Permission |
| Imagens | Intervention Image 3 (GD, webp, marca d'água) |
| Pagamento | Mercado Pago SDK 3.8, Simple QrCode (PIX) |
| SEO | Spatie Sitemap, RSS próprio, posts/blog |
| Qualidade | PHPUnit 10, Laravel Pint, factories/seeders |

## 3. Linha do tempo (inferida do código e migrations)

| Período | Marco |
|---|---|
| Base | Esqueleto Laravel 10 com autenticação, usuários, configurações, posts/blog, slides, menus e templates. |
| 2024-06 | Criação de `properties` (imóvel, valores, endereço, comodidades, SEO) e `property_gbs` (galeria de imagens). |
| 2025-10 | Comentários (`comments`) no blog. |
| 2026-02 | Núcleo de temporada: `property_seasons`, `property_blocked_dates`, `property_reservations`; painel de imóveis, temporadas, calendário e reservas. |
| 2026-03 | Notificações em banco, avaliações (`property_reviews`), APIs de posts e reservas, ajustes de navegação e slug de posts. |
| 2026-10 | Criação do agente especializado e deste histórico. |

## 4. Arquitetura e mapa de pastas

```
app/
├── Console/Commands/       # expire, clean, sitemap
├── Http/
│   ├── Controllers/Api/    # PropertyReservationController, PostController
│   ├── Controllers/Web/    # Home, Property, Page, RSS, Webhook MP, pagamento
│   ├── Livewire/           # Auth, Components, Dashboard, Navigation, Web
│   └── Requests/Admin/     # StoreUpdatePropertyRequest, UserRequest
├── Models/                 # Property, PropertySeason, PropertyReservation, ...
├── Notifications/          # NewReservationNotification, ReservationConfirmedNotification
├── Support/                # Cropper, ImageService, Message, Seo
├── Traits/                 # WithToastr
└── Helpers/                # Renato.php, WhatsApp.php (autoload composer)
resources/
├── views/livewire/dashboard/   # telas do painel (AdminLTE)
├── views/livewire/web/         # componentes do site (Tailwind)
├── views/web/                  # páginas públicas
└── views/emails/               # templates transacionais
routes/
├── web.php                 # site + /admin (auth + verified)
└── api.php                 # /api/v1 + webhook
```

Observação: `AdminLTE` é usado no painel; o site usa Tailwind. Não misturar as
duas abordagens na mesma tela.

## 5. Modelo de domínio

### Property (`properties`)

- Identificação: `id` (unsigned int), `title`, `slug`, `reference`, `status`,
  `highlight`, `category`, `type`, `publication_type`.
- Preço: `rental_value` (individual), `rental_value_couple` (casal),
  `min_nights`, `cleaning_fee`, `iptu_fee`, `aditional_person`,
  `value_aditional`, `display_values`.
- Capacidade/estrutura: `capacity`, `dormitories`, `suites`, `bathrooms`,
  `rooms`, `garage`, `covered_garage`, áreas e ano.
- Endereço: `zipcode`, `street`, `number`, `complement`, `neighborhood`,
  `state`, `city`, `latitude`, `longitude`, `display_address`.
- Comodidades: ~50 flags booleanas (`wifi`, `piscina`, `ar_condicionado`,
  `churrasqueira`, `vista_para_mar`, etc.).
- SEO/experiência: `headline`, `metatags`, `google_map`, `youtube_video`,
  `caption_img_cover`, `experience`, `expired_at`, `views`.
- Relações: `seasons`, `blockedDates`, `reservations`, `images`, `reviews`.
- Comportamentos: slug automático, normalização de status/valores, exclusão de
  imagens físicas no delete, `isAvailable`, `calculatePrice`, `priceForGuests`,
  `cover/nocover`, `getStarsAttribute`.

### PropertySeason (`property_seasons`)

Faixa `start_date`–`end_date` com `price_per_day`. Preço da temporada sobrepõe a
diária individual e a diária de casal. Regra adicional: quando o período
selecionado cruza uma temporada, a reserva precisa incluir todas as noites dela;
pode ser maior (antes ou depois), e as noites excedentes usam a tarifa normal.
A validação é centralizada em `Property::seasonPeriodConflict()`.

### PropertyBlockedDate (`property_blocked_dates`)

Bloqueio manual por data e motivo; único por `property_id` + `date`.

### PropertyReservation (`property_reservations`)

- Vínculo: `property_id` obrigatório; `user_id` obrigatório no schema; hóspede
  pode vir de `guest_*`.
- Datas: `check_in`, `check_out`, `nights`.
- Valores: `daily_total`, `cleaning_fee`, `iptu_fee`, `total_value`.
- Origem/status: `origin` (`admin`/`site`) e `status` (`pending`, `confirmed`,
  `cancelled`, `finished`, `waiting_payment`, `paid`, `expired`, `refunded`).
- Pagamento: `payment_id`, `payment_status`, `paid_at`, `expired_at`.
- Extras: `guests`, `guests_info` (JSON), `notes`, `review_token`.

### PropertyReview (`property_reviews`)

Avaliação 1–5 vinculada a imóvel e reserva, com `approved` e `comment`.

### PropertyGb (`property_gbs`)

Imagem do imóvel. FK na coluna `property` (não `property_id`), `path`, `cover`,
`order_img`, `watermark`. Arquivos em `storage/app/public/properties/{id}`.

### User / Config

- `User`: flags `superadmin`, `admin`, `editor`, `client`; `canEditProperties()`
  libera admin, superadmin e editor.
- `Config`: linha única com dados do site, contatos, SEO e marca d'água.

## 6. Fluxos principais

### Cadastro e gestão de imóvel (admin)

1. `/admin/imoveis/cadastrar` renderiza `PropertyForm` (Livewire).
2. Formulário em abas (dados, endereço, comodidades, imagens, SEO).
3. Validação por `StoreUpdatePropertyRequest`; valores monetários são
   normalizados; flags booleanas convertidas.
4. Slug gerado no `saving` do model; status publicado/rascunho conforme botão.
5. Imagens sobem para `properties/{id}`, convertidas para webp e registradas em
   `property_gbs`; capa única; marca d'água opcional com base em `Config`.
6. Listagem em `Properties` permite busca, ordenação, destaque, status, exclusão
   e aplicação de marca d'água em lote.

### Temporadas e bloqueios

- `Seasons` gerencia `PropertySeason` no painel.
- `Calendar` exibe reservas e bloqueios; datas bloqueadas manualmente em
  `PropertyBlockedDate`.

### Disponibilidade e preço (site)

1. `BookingForm` carrega reservas não canceladas e bloqueios e desabilita datas.
2. Calcula noites, valida `min_nights` e `expired_at` do imóvel.
3. `PropertyPricing` aplica individual (1 hóspede), casal (2 hóspedes),
   temporada por noite, extras a partir do 3º hóspede, `cleaning_fee` e
   `iptu_fee`.
4. `CheckoutPage` monta o pagamento; webhook confirma a reserva.

### API

- `GET /api/v1/properties/{property}/calendar`
- `POST /api/v1/properties/{property}/check-availability`
- `POST /api/v1/properties/{property}/calculate`
- `POST /api/v1/properties/{property}/reservations` (pré-reserva com noites,
  diárias, extras, limpeza, IPTU e total calculados)

### Pagamento e expiração

- Webhook `POST /api/webhook/mercadopago` busca o pagamento e usa
  `external_reference` como id da reserva.
- `approved` → `confirmed` + notificação; `rejected` → `cancelled`;
  `refunded`/`charged_back` → `refunded`.
- Comando `reservations:expire` roda a cada minuto e expira
  `waiting_payment` vencidas.
- `notifications:clean-old`, `app:clear-logs` e `sitemap:generate` também são
  agendados.

### Avaliações

Reserva gera `review_token`; rota pública `/avaliacao/{token}` permite avaliar;
painel aprova/reprova. Avaliações aprovadas aparecem no site.

## 7. Regras de negócio

1. **Diária**: se a data está em uma temporada, vale `PropertySeason.price_per_day`;
   caso contrário, `rental_value` para 1 hóspede e `rental_value_couple` para
   2 ou mais (fallback para `rental_value` quando o valor de casal for nulo).
2. **Noites**: `check_in` até `check_out` menos um dia; check-out não conta como
   noite.
3. **Hóspedes extras**:
   `max(0, guests - 2) × value_aditional × nights`.
   `capacity + aditional_person` continua limitando o total de hóspedes no site.
4. **Total**: diárias + hóspedes extras + `cleaning_fee` + `iptu_fee`.
5. **Mínimo de noites**: `min_nights` precisa ser respeitado em todos os fluxos.
6. **Disponibilidade**: sem sobreposição com reservas de status diferente de
   `cancelled` e sem `property_blocked_dates` no período.
7. **Temporada incluída**: reserva que cruza uma temporada precisa incluir todas
   as noites dela, mas pode ser maior antes ou depois; as noites fora da
   temporada usam a tarifa normal.
8. **Expiração do anúncio**: `properties.expired_at` limita o check-out no site.
9. **Status**: `pending`, `confirmed`, `cancelled`, `finished`,
   `waiting_payment`, `paid`, `expired`, `refunded`.
10. **Datas**: formulários usam `d/m/Y`; persistência em `Y-m-d`.

## 8. Decisões e padrões

- `PropertyPricing` (`app/Support`) é a fonte única de cálculo de diária,
  temporada, extras, limpeza, IPTU e total; models e Livewire delegam a ele.
- Livewire para toda interação do painel e do site; controllers enxutos.
- AdminLTE no painel e Tailwind no site, sem mistura.
- Feedback com eventos `swal:success`, `swal:warning`, `swal:confirm` e
  `WithToastr`; confirmação de exclusão via `swal:confirm`.
- Imagens sempre em webp com Intervention; marca d'água configurável.
- CEP preenchido via ViaCEP no `updatedZipcode`.
- Configuração singleton (`Config`) para dados do site.
- Automações via scheduler no `Kernel` (expiração, limpeza, sitemap).
- Notificações de admin/superadmin para eventos de reserva.
- Factories e seeders disponíveis para o domínio de imóveis.
- Conteúdo jurídico é formatado por `LegalContent`, que gera títulos, âncoras e
  sumário sem alterar o texto armazenado em `Config`.

## 9. Dívidas técnicas e riscos conhecidos

1. **Disponibilidade inclusiva**: `Property::isAvailable()` usa `whereBetween`
   nas duas pontas e pode bloquear a noite de check-out; testar reservas
   encostadas.
2. **Schema x fluxos**: `property_reservations.user_id` é obrigatório no banco,
   mas o endpoint público ainda pode criar reserva sem usuário autenticado.
3. **Webhook sem assinatura**: `MERCADOPAGO_WEBHOOK_SECRET` é documentado, mas o
   controller não valida a assinatura.
4. **Coordenadas inteiras**: `latitude`/`longitude` como `integer` perdem
   precisão; avaliar `decimal(10,7)`.
5. **Temporada no backend**: `Property::seasonPeriodConflict()` já valida site,
   checkout e API; falta cobrir o formulário de reserva do painel, se ele passar
   a permitir escolher temporadas.
6. **Testes escassos**: os testes de preço cobrem a nova regra, mas ainda faltam
   testes de disponibilidade, reserva e webhook.
7. **Config local ignorada**: `.gitignore` ignora `opencode.json`, `.opencode/`
   e `AGENTS.md`; o agente e suas instruções são locais.
8. **Conteúdo dos Termos**: o texto armazenado em `Config.terms_condicions` tem
   a seção 5 duplicada e não possui a seção 6. A página apenas renderiza o
   conteúdo; o texto jurídico precisa ser revisado pela área responsável.

## 10. Diário de mudanças

Formato sugerido:

```
## AAAA-MM-DD - Título curto

- Tipo: feature | fix | refactor | docs | chore
- Arquivos: ...
- Decisão: ...
- Impacto/validação: ...
```

### 2026-10-28 - Temporada como período mínimo, não exato

- Tipo: fix/feature
- Arquivos: `app/Models/Property.php`, `app/Models/PropertySeason.php`,
  `app/Support/PropertyPricing.php`, `app/Livewire/Web/BookingForm.php`,
  `app/Livewire/Web/CheckoutPage.php`,
  `app/Http/Controllers/Api/PropertyReservationController.php`,
  `resources/views/livewire/web/booking-form.blade.php`,
  `resources/views/livewire/web/checkout-page.blade.php`,
  `tests/Feature/BookingFormTest.php`, `docs/historico.md`
- Decisão: a temporada passou a exigir que a reserva inclua todas as noites
  dela, e não mais que o período seja exatamente igual. O cliente pode reservar
  antes e/ou depois; as noites fora da temporada continuam na tarifa normal. A
  regra é centralizada em `Property::seasonPeriodConflict()` e
  `PropertySeason::isCoveredBy()`/`coverageMessage()`, usada pelo formulário de
  reserva, pelo checkout e pela API (`calculate` e `store`). O cálculo passou a
  retornar `season_nights` e `base_nights` para exibir a quebra de noites no
  site e no checkout.
- Impacto/validação: `BookingFormTest` ganhou casos de período exato, período
  maior, período parcial e reserva encostada antes da temporada; 24 testes
  passando (`BookingFormTest`, `PropertyPricingTest`,
  `PropertyAvailabilityTest`), `view:cache` e Pint executados.

### 2026-10-25 - Agente especializado e histórico do projeto

- Tipo: docs/chore
- Arquivos: `.opencode/agent/ubatuba-northshore.md`, `AGENTS.md`,
  `opencode.json`, `docs/historico.md`
- Decisão: criar um agente primário focado em imóveis, reservas, disponibilidade
  e pagamentos, carregando `AGENTS.md` e este histórico como instruções.
- Impacto: contexto compartilhado para futuras sessões; nenhuma alteração de
  código de aplicação.

### 2026-10-25 - Preço individual/casal e taxa de IPTU

- Tipo: feature/refactor
- Arquivos: `app/Support/PropertyPricing.php`, `app/Models/Property.php`,
  `app/Models/PropertyReservation.php`, `app/Livewire/**`, `app/Http/**`,
  `database/migrations/2026_10_25_*`, `database/factories/PropertyFactory.php`,
  views do painel/site, `tests/Feature/PropertyPricingTest.php`,
  `docs/historico.md`
- Decisão: `rental_value` passou a representar a locação individual;
  `rental_value_couple` foi adicionado para 2 hóspedes. Hóspedes a partir do 3º
  continuam pagando `value_aditional` por noite. `iptu_fee` foi adicionado como
  taxa única da reserva. `PropertyPricing` centraliza o cálculo e alimenta
  admin, site e API.
- Validação: migrações aplicadas; `sail test --filter=PropertyPricingTest`
  passa (7 testes, 24 asserções); Pint executado nos arquivos alterados. O teste
  `ExampleTest` da home segue falhando por ausência de `Config` no banco de
  testes, problema anterior a esta mudança.
- Correções de apoio: migration de `property_reviews` ficou idempotente e com FK
  compatível com `properties.id`; factory de imóvel deixou de usar colunas
  inexistentes (`internet`, `quintal`).

### 2026-10-25 - Calendário e revisão da página do imóvel

- Tipo: fix/refactor/UI
- Arquivos: `app/Models/Property.php`, `app/Livewire/Web/BookingForm.php`,
  `app/Livewire/Web/CheckoutPage.php`, `resources/js/front.js`,
  `resources/views/livewire/web/booking-form.blade.php`,
  `resources/views/livewire/web/checkout-page.blade.php`,
  `resources/views/web/property.blade.php`, `tests/Feature/BookingFormTest.php`,
  `docs/historico.md`
- Decisão: o componente Alpine `dateRangePicker` passou a ser definido uma única
  vez em `front.js`, recebendo datas desabilitadas, data máxima, método Livewire
  e quantidade de meses por `data-*`. O calendário usa `Y-m-d` internamente e
  exibe `d/m/Y`; isso removeu as implementações duplicadas e divergentes entre
  imóvel e checkout. `Property` ganhou `bookingDeadline()`,
  `isBookingClosed()` e `maxBookingDate()` para centralizar `expired_at`.
  Imóvel expirado agora mostra "Reservas encerradas" em vez de um calendário sem
  datas selecionáveis; imóvel sem expiração não é mais tratado como vencido.
  A página do imóvel recebeu breadcrumb, galeria responsiva, cartões de
  capacidade e um card de reserva com preço inicial, controle de hóspedes,
  resumo e estado de erro mais claros.
- Validação: `sail test --filter='BookingFormTest|PropertyPricingTest'` passa
  (11 testes, 35 asserções); `npm run build` e `view:cache` executados; página
  validada em desktop/mobile via Chrome headless. O `ExampleTest` da home segue
  com 500 por ausência de `Config` no banco de testes, problema anterior.

### 2026-10-25 - Clareza no preço de casal e hóspede extra

- Tipo: fix/UI/docs
- Arquivos: `resources/views/livewire/web/booking-form.blade.php`,
  `resources/views/livewire/web/checkout-page.blade.php`,
  `resources/views/livewire/web/reservation-form.blade.php`,
  `resources/views/livewire/dashboard/properties/property-form.blade.php`,
  `resources/views/livewire/dashboard/properties/properties.blade.php`,
  `tests/Feature/PropertyPricingTest.php`, `docs/historico.md`
- Decisão: o valor de `rental_value_couple` é a diária total para os dois
  primeiros hóspedes, não um valor por pessoa. A partir do terceiro hóspede,
  cada hóspede adicional paga `value_aditional` por noite. Os rótulos do site e
  do painel foram ajustados para deixar essa interpretação explícita.
- Validação: casos de 2, 3 e 4 hóspedes conferidos no checkout do imóvel 34:
  - 1 hóspede: diárias R$ 500, total R$ 650;
  - 2 hóspedes: diárias R$ 900, total R$ 1.050;
  - 3 hóspedes: diárias R$ 900 + R$ 300 de extra, total R$ 1.350;
  - 4 hóspedes: diárias R$ 900 + R$ 600 de extra, total R$ 1.650.
  `PropertyPricingTest` agora cobre explicitamente os dois cenários (13 testes
  no total com `BookingFormTest`).

### 2026-10-25 - Revisão do checkout e ordem mobile

- Tipo: fix/UX/refactor
- Arquivos: `app/Models/Property.php`, `app/Livewire/Web/CheckoutPage.php`,
  `resources/js/front.js`,
  `resources/views/livewire/web/checkout-page.blade.php`,
  `tests/Feature/PropertyAvailabilityTest.php`, `docs/historico.md`
- Decisão: no mobile, o resumo da reserva passa a aparecer antes do formulário
  de dados; no desktop a ordem continua formulário à esquerda e resumo à
  direita. O formulário de dados ficou responsivo em uma coluna no mobile.
  A disponibilidade passou a usar intervalos semiabertos
  (`check_in < outro_check_out` e `check_out > outro_check_in`), permitindo
  check-out e check-in encostados. Bloqueios só afetam noites ocupadas, não o
  dia de check-out. O checkout valida datas, hóspedes, mínimo de noites,
  expiração, bloqueios e limite de hóspedes no servidor, e o seletor de datas
  inicia preenchido com o período atual.
- Validação: `PropertyAvailabilityTest` (6 testes), `PropertyPricingTest`
  (9 testes) e `BookingFormTest` (4 testes) passam, total de 19 testes e 49
  asserções; `npm run build` e `view:cache` executados; checkout validado em
  desktop e mobile via Chrome headless.

### 2026-10-25 - Redesign da página de atendimento

- Tipo: UI/refactor
- Arquivos: `resources/views/web/contact.blade.php`,
  `resources/views/livewire/web/contact-form.blade.php`,
  `app/Livewire/Web/ContactForm.php`, `docs/historico.md`
- Decisão: a página `/atendimento` ganhou hero institucional, breadcrumb,
  cartões de canais com ícones SVG (WhatsApp, telefone, e-mail, escritório e
  redes sociais) e formulário reorganizado com labels, estados de erro,
  indicação de campos obrigatórios, aviso de privacidade e estado de sucesso
  com opção de enviar outra mensagem. O link de WhatsApp passou a ser gerado
  no servidor com DDI 55, eliminando o script `shareWhatsApp` da página.
  A ordem mobile mantém o formulário antes dos canais de atendimento.
- Validação: `view:cache`, `npm run build` e Pint executados; os 19 testes de
  reserva/preço/disponibilidade continuam passando; página conferida em
  desktop e mobile via Chrome headless.

### 2026-10-25 - Páginas legais e footer

- Tipo: UI/refactor
- Arquivos: `app/Support/LegalContent.php`,
  `app/Http/Controllers/Web/PageController.php`,
  `resources/views/web/components/legal-page.blade.php`,
  `resources/views/web/privacy.blade.php`,
  `resources/views/web/terms-conditions.blade.php`,
  `resources/views/web/components/footer.blade.php`,
  `resources/css/app.css`, `resources/views/web/layouts/app.blade.php`,
  `tests/Unit/LegalContentTest.php`, `docs/historico.md`
- Decisão: Política de Privacidade e Termos passaram a compartilhar um layout
  editorial com hero, sumário lateral (recolhível no mobile), âncoras por seção,
  botão de impressão, atalhos de atendimento e tipografia própria para o HTML
  jurídico. O
  `LegalContent` converte seções numeradas em títulos, remove parágrafos vazios
  e corrige links sem protocolo, sem editar o texto salvo no banco. O footer
  foi reorganizado em colunas de marca, navegação, institucional e contato,
  com WhatsApp, endereço, e-mail, redes sociais e créditos.
- Validação: `LegalContentTest` (3 testes, 8 asserções), `view:cache`,
  `npm run build`, Pint e conferência em desktop/mobile via Chrome headless.
  Observação: o texto salvo dos Termos contém a seção 5 duplicada e não tem a
  seção 6; o conteúdo jurídico não foi alterado nesta refatoração.

### 2026-10-25 - Refatoração do blog e leitura de artigos

- Tipo: UI/refactor
- Arquivos: `app/Http/Controllers/Web/HomeController.php`,
  `app/Http/Controllers/Api/PostController.php`, `app/Models/Post.php`,
  `resources/views/web/blog/index.blade.php`,
  `resources/views/web/blog/article.blade.php`,
  `resources/views/web/components/blog-card.blade.php`, `resources/css/app.css`,
  `tests/Unit/PostTest.php`, `docs/historico.md`
- Decisão: a listagem ganhou hero editorial, filtros por categoria, destaque,
  grade de cards reutilizáveis e estado vazio. A leitura do artigo passou a ter
  hero com metadados, capa, conteúdo tipografado, tags, compartilhamento,
  galeria, relacionados e sidebar com categorias, mais visitados e CTA.
  `Post` ganhou `url` (respeitando artigo/notícia) e `summary()`; o controller
  centralizou categorias, tags, relacionados e validação de tipo/slug. A API de
  criação de posts também passou a usar `Post::url`.
- Validação: `PostTest` (3 testes, 4 asserções), os 22 testes de
  reserva/preço/disponibilidade e conteúdo legal, `view:cache`, `npm run build`
  e Pint executados; listagem, artigo e categoria conferidos em desktop/mobile
  via Chrome headless.

### 2026-10-25 - Busca da home, calendário em português e filtros da busca

- Tipo: fix/refactor/UX
- Arquivos: `resources/views/web/home.blade.php`,
  `resources/views/livewire/web/property-search.blade.php`,
  `app/Livewire/Web/PropertySearch.php`, `resources/js/front.js`,
  `docs/historico.md`
- Decisão: o formulário do hero da home foi reorganizado em um card claro com
  cabeçalho, campos de check-in/check-out com ícones, seletor de hóspedes e CTA
  de busca. Os campos de data usam Flatpickr com `locale` português, exibem
  `d/m/Y` e enviam `Y-m-d` ao servidor; o check-out acompanha a data mínima do
  check-in e é limpo quando fica inválido. A mesma inicialização foi extraída
  para `searchCalendar` em `front.js` e reutilizada na página de resultados, que
  agora lê os filtros da query string e mantém os valores visíveis.
- Validação: calendário e meses/dias conferidos em português no Chrome headless;
  submissão da home gerou `?check_in=2026-11-10&check_out=2026-11-14&guests=1`;
  busca com datas retornou 200; `view:cache`, `npm run build`, Pint e os testes
  relevantes passaram.

## 11. Como manter

- Registre mudanças de regra de negócio, schema, integração e fluxo de reserva.
- Ao alterar preço/disponibilidade, atualize as seções 7 e 9 e os testes.
- Ao criar/remover comandos agendados, atualize a seção 6.
- Ao mudar dependências relevantes, atualize a seção 2.
- Mantenha a data de "Última atualização" no topo.
