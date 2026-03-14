<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\{
    NotificationsList,
    Settings,
};
use App\Livewire\Dashboard\Users\{
    Time,
    Users,
    ViewUser,
    Form,
};
use App\Livewire\Dashboard\Permissions\Index as PermissionIndex;
use App\Livewire\Dashboard\Roles\Index as RoleIndex;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    HomeController,
    MercadoPagoWebhookController,
    PageController,
    PropertyController,
    PropertyRssController,
    ReservationPaymentController,
};
use App\Livewire\Dashboard\Menu\Index;
use App\Livewire\Dashboard\Posts\CatPosts;
use App\Livewire\Dashboard\Posts\PostForm;
use App\Livewire\Dashboard\Posts\Posts;
use App\Livewire\Dashboard\Properties\Properties;
use App\Livewire\Dashboard\Properties\PropertyForm;
use App\Livewire\Dashboard\Properties\ReservationCalendar;
use App\Livewire\Dashboard\Reports\PropertiesReport;
use App\Livewire\Dashboard\Reservations\BlockedDates;
use App\Livewire\Dashboard\Sitemap\SitemapGenerator;
use App\Livewire\Dashboard\Slides\SlideForm;
use App\Livewire\Dashboard\Slides\Slides;
use App\Livewire\Dashboard\Reservations\Index as Reservations;
use App\Livewire\Dashboard\Reservations\ReservationForm;
use App\Livewire\Web\ReservationForm as WebReservationForm;

Route::group(['as' => 'web.'], function () {
    
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/imoveis', [PropertyController::class, 'index'])->name('properties');
    Route::get('/imovel/{slug}', [PropertyController::class, 'show'])->name('property');
    Route::get('/pesquisar-imoveis', [PropertyController::class, 'search'])->name('property.search');
    Route::get('/checkout/{property}', [PropertyController::class, 'checkout'])->name('checkout');

    Route::get('/finalizar-reserva/{token}', WebReservationForm::class)->name('reservation.form');
    Route::get('/reserva/sucesso/{reservation}', [ReservationPaymentController::class, 'success'])
    ->name('reservation.success');

    Route::get('/reserva/cancelada/{reservation}', [ReservationPaymentController::class, 'cancel'])
    ->name('reservation.cancel');

    Route::get('/reserva/pendente/{reservation}', [ReservationPaymentController::class, 'pending'])
    ->name('reservation.pending');    
    
    Route::get('/atendimento', [PageController::class, 'contact'])->name('contact');
    Route::get('/politica-de-privacidade', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/termos-e-condicoes', [PageController::class, 'terms'])->name('terms');
    Route::get('/avaliacao/{token}', [PageController::class, 'review'])->name('review');
    Route::get('/pagina/{slug}', [PageController::class, 'page'])->name('page');
    Route::get('/rss/imoveis', [PropertyRssController::class, 'index'])->name('rss.properties');
});

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin'], function () {

    Route::get('/', Dashboard::class)->name('admin');
    Route::get('configuracoes', Settings::class)->name('settings');

    //******************************* Sitemap *********************************************/
    Route::get('sitemap-generator', SitemapGenerator::class)->name('sitemap.generator');
    Route::get('/relatorios/imoveis', PropertiesReport::class)->name('reports.properties');

    Route::get('notificacoes', NotificationsList::class)->name('notifications.index'); 

    
    // Route::put('listas/email/{id}', [NewsletterController::class, 'newsletterUpdate'])->name('listas.newsletter.update');
    // Route::get('listas/email/set-status', [NewsletterController::class, 'emailSetStatus'])->name('emails.emailSetStatus');
    // Route::get('listas/email/delete', [NewsletterController::class, 'emailDelete'])->name('emails.delete');
    // Route::delete('listas/email/deleteon', [NewsletterController::class, 'emailDeleteon'])->name('emails.deleteon');
    // Route::get('listas/email/{id}/edit', [NewsletterController::class, 'newsletterEdit'])->name('listas.newsletter.edit');
    // Route::get('listas/email/cadastrar', [NewsletterController::class, 'newsletterCreate'])->name('lista.newsletter.create');
    // Route::post('listas/email/store', [NewsletterController::class, 'newsletterStore'])->name('listas.newsletter.store');
    // Route::get('listas/emails/categoria/{categoria}', [NewsletterController::class, 'newsletters'])->name('lista.newsletters');

    //******************* Templates ************************************************/
    // Route::get('templates/set-status', [TemplateController::class, 'templateSetStatus'])->name('templates.templateSetStatus');
    // Route::get('templates/delete', [TemplateController::class, 'delete'])->name('templates.delete');
    // Route::delete('templates/deleteon', [TemplateController::class, 'deleteon'])->name('templates.deleteon');
    // Route::put('templates/{id}', [TemplateController::class, 'update'])->name('templates.update');
    // Route::get('templates/{id}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
    // Route::get('templates/create', [TemplateController::class, 'create'])->name('templates.create');
    // Route::post('templates/store', [TemplateController::class, 'store'])->name('templates.store');
    // Route::get('templates', [TemplateController::class, 'index'])->name('templates.index');

    /** Imóveis */
    //Route::get('imoveis/{property}/calendario', ReservationCalendar::class)->name('property.calendar');
    Route::get('imoveis/{property}/editar', PropertyForm::class)->name('property.edit');
    Route::get('imoveis/cadastrar', PropertyForm::class)->name('properties.create');
    Route::get('imoveis', Properties::class)->name('properties.index');
    
    Route::get('reservas/{reservation}/editar', ReservationForm::class)->name('reservations.edit');
    Route::get('reservas/cadastrar', ReservationForm::class)->name('reservations.create');
    Route::get('reservas', Reservations::class)->name('reservations.index');
    
    //*********************** Slides ********************************************/
    Route::get('slides/{slide}/editar', SlideForm::class)->name('slides.edit');
    Route::get('slides/cadastrar', SlideForm::class)->name('slides.create');
    Route::get('slides', Slides::class)->name('slides.index');

    Route::get('menus', Index::class)->name('menus.index');

    //*********************** Posts *********************************************/
    Route::get('posts/{post}/editar', PostForm::class)->name('posts.edit');
    Route::get('posts/cadastrar', PostForm::class)->name('posts.create');
    Route::get('posts', Posts::class)->name('posts.index');

    //*********************** Categorias de Posts ********************************/
    Route::get('posts/categorias', CatPosts::class)->name('posts.categories.index');

    //*********************** Usuários *******************************************/
    Route::get('/cargos', RoleIndex::class)->name('admin.roles');
    Route::get('/permissoes', PermissionIndex::class)->name('admin.permissions');

    Route::get('usuarios/clientes', Users::class)->name('users.index');
    Route::get('usuarios/time', Time::class)->name('users.time');
    Route::get('usuarios/cadastrar', Form::class)->name('users.create');
    Route::get('usuarios/{user}/editar', Form::class)->name('users.edit');
    Route::get('usuarios/{user}/visualizar', ViewUser::class)->name('users.view');     
});


// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});
