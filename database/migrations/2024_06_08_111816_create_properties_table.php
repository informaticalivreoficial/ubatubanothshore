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
            $table->boolean('highlight')->default('0');
            $table->string('category');
            $table->string('type');
            $table->integer('status')->default('0');

            /** pricing and values */
            $table->boolean('display_values')->nullable();
            $table->decimal('rental_value', 10, 2)->nullable();
            $table->decimal('value_aditional', 10, 2)->nullable();
            $table->integer('min_nights')->default(1);
            $table->integer('cleaning_fee')->nullable();
            $table->integer('aditional_person')->nullable();
            
            $table->string('reference')->nullable();
            
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('headline')->nullable();
            $table->string('experience')->nullable();
            $table->text('metatags')->nullable();
            $table->text('google_map')->nullable();

            /** description */
            $table->text('description')->nullable();            
            $table->text('additional_notes')->nullable();
            $table->text('politica_cancelamento')->nullable();
            $table->integer('dormitories')->default('0');
            $table->integer('capacity')->default('0');
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
            $table->boolean('acesso_praia')->nullable();
            $table->boolean('adequado_criancas')->nullable();
            $table->boolean('adequado_idosos')->nullable();
            $table->boolean('agua_quente')->nullable();
            $table->boolean('aquecedor')->nullable();
            $table->boolean('ar_condicionado')->nullable();
            $table->boolean('areadelazer')->nullable();
            $table->boolean('banheira')->nullable();
            $table->boolean('banheiro_privativo')->nullable();
            $table->boolean('cafeteira')->nullable();
            $table->boolean('cama_casal')->nullable();
            $table->boolean('cameras')->nullable();
            $table->boolean('churrasqueira')->nullable();
            $table->boolean('condominiofechado')->nullable();
            $table->boolean('cozinha')->nullable();
            $table->boolean('elevador')->nullable();
            $table->boolean('espaco_fitness')->nullable();
            $table->boolean('estacionamento')->nullable();
            $table->boolean('fechadura_eletronica')->nullable();
            $table->boolean('ferro_passar')->nullable();
            $table->boolean('fornopizza')->nullable();
            $table->boolean('frigobar')->nullable();
            $table->boolean('garagem')->nullable();
            $table->boolean('geladeira')->nullable();
            $table->boolean('interfone')->nullable();
            $table->boolean('jardim')->nullable();
            $table->boolean('lareira')->nullable();
            $table->boolean('lavabo')->nullable();
            $table->boolean('maquina_lavar')->nullable();
            $table->boolean('mesa_refeicao')->nullable();
            $table->boolean('mesa_trabalho')->nullable();
            $table->boolean('microondas')->nullable();
            $table->boolean('mobiliado')->nullable();
            $table->boolean('permiteanimais')->nullable();
            $table->boolean('piscina')->nullable();
            $table->boolean('portaria24hs')->nullable();
            $table->boolean('pratos_talheres')->nullable();
            $table->boolean('produtos_limpeza')->nullable();
            $table->boolean('roupa_cama')->nullable();
            $table->boolean('sauna')->nullable();
            $table->boolean('salaodejogos')->nullable();
            $table->boolean('secador_cabelo')->nullable();
            $table->boolean('secadora')->nullable();
            $table->boolean('tv')->nullable();
            $table->boolean('tv_netflix')->nullable();
            $table->boolean('ventilador_teto')->nullable();
            $table->boolean('vista_para_mar')->nullable();
            $table->boolean('wifi')->nullable();
            
            $table->boolean('display_marked_water')->nullable(); 
            $table->text('youtube_video')->nullable(); 
            
            $table->bigInteger('views')->default('0');
            $table->date('expired_at')->nullable();

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
