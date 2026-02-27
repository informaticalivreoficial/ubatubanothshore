<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\{
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
    PropertyRssController,
    Webcontroller
};

use App\Livewire\Dashboard\Posts\CatPosts;
use App\Livewire\Dashboard\Posts\PostForm;
use App\Livewire\Dashboard\Posts\Posts;
use App\Livewire\Dashboard\Properties\Properties;
use App\Livewire\Dashboard\Properties\PropertyForm;
use App\Livewire\Dashboard\Properties\ReservationCalendar;
use App\Livewire\Dashboard\Reports\PropertiesReport;
use App\Livewire\Dashboard\Sitemap\SitemapGenerator;
use App\Livewire\Dashboard\Slides\SlideForm;
use App\Livewire\Dashboard\Slides\Slides;


Route::group(['namespace' => 'Web', 'as' => 'web.'], function () {
    //Institucional
    Route::get('/', [WebController::class, 'home'])->name('home');
    Route::get('/politica-de-privacidade', [WebController::class, 'privacy'])->name('privacy');
    
    Route::get('/rss/imoveis', [PropertyRssController::class, 'index'])->name('rss.properties');

//     /** FEED */
//     Route::get('feed', [FeedController::class, 'feed'])->name('feed');
    
//     Route::get('/sitemap', [WebController::class, 'sitemap'])->name('sitemap');

//     /** Página de Experiências - Específica de uma categoria */
//     Route::get('/experiencias/{slug}', [FilterController::class, 'experienceCategory'])->name('experienceCategory');

     //Properties
     Route::get('pesquisar-imoveis', [WebController::class, 'pesquisaImoveis'])->name('pesquisar-imoveis');
     Route::get('imoveis/{slug}', [WebController::class, 'Property'])->name('property');
     Route::get('imoveis/categoria/{type}', [WebController::class, 'propertyList'])->name('propertylist');
     Route::get('imoveis/bairro/{neighborhood}', [WebController::class, 'propertyNeighborhood'])->name('properties.neighborhood');
     Route::get('lancamentos', [WebController::class, 'PropertyHighliths'])->name('highliths');
     Route::get('imoveis', [WebController::class, 'Properties'])->name('properties');

     //Client
     Route::get('/atendimento', [WebController::class, 'contact'])->name('contact');
     Route::get('/simulador-de-credito-imobiliario', [WebController::class, 'creditSimulator'])->name('simulator');
     

     //Blog
     Route::get('/blog/artigo/{slug}', [WebController::class, 'artigo'])->name('blog.artigo');
     Route::get('/blog/noticia/{slug}', [WebController::class, 'noticia'])->name('blog.noticia');
     Route::get('/blog/categoria/{slug}', [WebController::class, 'blogCategory'])->name('blog.category');
     Route::get('/blog', [WebController::class, 'blog'])->name('blog.index');

     //Page
     Route::get('/pagina/{slug}', [WebController::class, 'page'])->name('page');
});

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin'], function () {

    Route::get('/', Dashboard::class)->name('admin');
    Route::get('configuracoes', Settings::class)->name('settings');

    //******************************* Sitemap *********************************************/
    Route::get('sitemap-generator', SitemapGenerator::class)->name('sitemap.generator');

    Route::get('/relatorios/imoveis', PropertiesReport::class)->name('reports.properties');

    
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
    Route::get('imoveis/{property}/calendario', ReservationCalendar::class)->name('property.calendar');
    Route::get('imoveis/{property}/editar', PropertyForm::class)->name('property.edit');
    Route::get('imoveis/cadastrar', PropertyForm::class)->name('properties.create');
    Route::get('imoveis', Properties::class)->name('properties.index');

    //*********************** Slides ********************************************/
    Route::get('slides/{slide}/editar', SlideForm::class)->name('slides.edit');
    Route::get('slides/cadastrar', SlideForm::class)->name('slides.create');
    Route::get('slides', Slides::class)->name('slides.index');

    //*********************** Posts *********************************************/
    Route::get('posts/{post}/editar', PostForm::class)->name('posts.edit');
    Route::get('posts/cadastrar', PostForm::class)->name('posts.create');
    Route::get('posts', Posts::class)->name('posts.index');

    //*********************** Categorias de Posts ********************************/
    Route::get('posts/categorias', CatPosts::class)->name('posts.categories.index');
    //Route::get('posts/categorias/cadastrar/{parent?}', CatPostForm::class)->name('posts.categories.create');
    //Route::get('posts/categorias/{category}/editar', CatPostForm::class)->name('posts.categories.edit');

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
