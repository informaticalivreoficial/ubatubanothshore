<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('sale')->nullable();
            $table->boolean('location')->nullable();
            $table->boolean('highlight')->default('0');
            $table->string('category');
            $table->string('type');
            $table->integer('status')->default('0');

            /** pricing and values */
            $table->boolean('display_values')->nullable();
            $table->decimal('sale_value', 10, 2)->nullable();
            $table->decimal('rental_value', 10, 2)->nullable();
            $table->integer('location_period')->nullable();
            $table->decimal('iptu', 10, 2)->nullable();
            $table->decimal('condominium', 10, 2)->nullable();
            
            $table->string('reference')->nullable();
            
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('url_booking')->nullable();
            $table->string('url_arbnb')->nullable();
            $table->string('headline')->nullable();
            $table->string('experience')->nullable();
            $table->text('metatags')->nullable();
            $table->text('google_map')->nullable();

            /** description */
            $table->text('description')->nullable();            
            $table->text('additional_notes')->nullable();
            $table->integer('dormitories')->default('0');
            $table->integer('suites')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('garage')->nullable();
            $table->integer('covered_garage')->nullable();
            $table->string('construction_year')->nullable();
            $table->integer('total_area')->nullable();
            $table->integer('useful_area')->nullable();
            $table->string('measures')->nullable();            
            $table->string('caption_img_cover')->nullable();

            /** address */       
            $table->integer('latitude')->nullable();
            $table->integer('longitude')->nullable();                
            $table->boolean('display_address')->nullable();            
            $table->string('zipcode')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            /** structure */
            $table->boolean('ar_condicionado')->nullable();
            $table->boolean('areadelazer')->nullable();
            $table->boolean('aquecedor_solar')->nullable();
            $table->boolean('bar')->nullable();
            $table->boolean('banheirosocial')->nullable();
            $table->boolean('brinquedoteca')->nullable();
            $table->boolean('biblioteca')->nullable();
            $table->boolean('balcaoamericano')->nullable();
            $table->boolean('churrasqueira')->nullable();
            $table->boolean('condominiofechado')->nullable();
            $table->boolean('estacionamento')->nullable();
            $table->boolean('cozinha_americana')->nullable();
            $table->boolean('cozinha_planejada')->nullable();
            $table->boolean('dispensa')->nullable();
            $table->boolean('edicula')->nullable();
            $table->boolean('espaco_fitness')->nullable();
            $table->boolean('escritorio')->nullable();
            $table->boolean('banheira')->nullable();
            $table->boolean('geradoreletrico')->nullable();
            $table->boolean('interfone')->nullable();
            $table->boolean('jardim')->nullable();
            $table->boolean('lareira')->nullable();
            $table->boolean('lavabo')->nullable();
            $table->boolean('lavanderia')->nullable();
            $table->boolean('elevador')->nullable();
            $table->boolean('mobiliado')->nullable();
            $table->boolean('vista_para_mar')->nullable();
            $table->boolean('piscina')->nullable();
            $table->boolean('quadrapoliesportiva')->nullable();
            $table->boolean('sauna')->nullable();
            $table->boolean('salaodejogos')->nullable();
            $table->boolean('salaodefestas')->nullable();
            $table->boolean('sistemadealarme')->nullable();
            $table->boolean('saladetv')->nullable();
            $table->boolean('ventilador_teto')->nullable();
            $table->boolean('armarionautico')->nullable();
            $table->boolean('fornodepizza')->nullable();
            $table->boolean('portaria24hs')->nullable();
            $table->boolean('permiteanimais')->nullable();
            $table->boolean('pertodeescolas')->nullable();
            $table->boolean('quintal')->nullable();
            $table->boolean('zeladoria')->nullable();  
            $table->boolean('varandagourmet')->nullable();
            $table->boolean('internet')->nullable();
            $table->boolean('geladeira')->nullable();
            
            $table->boolean('display_marked_water')->nullable(); 
            $table->text('youtube_video')->nullable(); 
            
            $table->bigInteger('views')->default('0');

            $table->timestamps();
            $table->integer('publication_type')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
