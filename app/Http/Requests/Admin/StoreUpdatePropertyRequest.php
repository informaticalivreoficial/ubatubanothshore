<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
    public function rules(): array
    {
        return [     
            // Basic Info  
            //'highlight'     => 'nullable|boolean',            
            'title'         => 'required',
            'category'      => 'required|string',
            'expired_at'    => 'nullable|date_format:d/m/Y|after_or_equal:start', 
            'type'          => 'required|string',
            'status'        => 'nullable|integer',

            // Pricing and Values
            'display_values'     => 'nullable|boolean', 
            'rental_value'       => 'nullable|numeric',
            'cleaning_fee'       => 'nullable|numeric',
            'value_aditional'    => 'nullable|numeric',
            
            // Basic Info
            'reference'         => 'nullable|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'headline'          => 'nullable|string|max:255',
            'experience'        => 'nullable|string|max:255',
            'metatags'          => 'nullable|array',
            'google_map'        => 'nullable|string',

            // Description
            'description'       => 'nullable|string',
            'additional_notes'  => 'nullable|string',
            'politica_cancelamento'  => 'nullable|string',
            'min_nights'        => 'nullable|integer',
            'capacity'        => 'nullable|integer',
            'aditional_person'              => 'nullable|integer',

            'dormitories'       => 'required|integer',
            'suites'            => 'nullable|integer',
            'bathrooms'         => 'nullable|integer',
            'rooms'             => 'nullable|integer',
            'garage'            => 'nullable|integer',
            'covered_garage'    => 'nullable|integer',
            'construction_year' => 'nullable|string|max:255',
            'total_area'        => 'nullable|integer',
            'useful_area'       => 'nullable|integer',
            'measures'          => 'nullable|string|max:255',
            'caption_img_cover' => 'nullable|string|max:255', 

            // Address
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'display_address'   => 'nullable|boolean',
            'zipcode'           => 'nullable|string|max:255',
            'street'            => 'nullable|string|max:255',
            'number'            => 'nullable|string|max:255',
            'complement'        => 'nullable|string|max:255',
            'neighborhood'      => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            
            //Acessorios
            'acesso_praia'        => 'nullable|boolean',
            'adequado_criancas'   => 'nullable|boolean',
            'adequado_idosos'     => 'nullable|boolean',
            'agua_quente'         => 'nullable|boolean',
            'aquecedor'           => 'nullable|boolean',
            'ar_condicionado'     => 'nullable|boolean',
            'areadelazer'         => 'nullable|boolean',
            'banheira'            => 'nullable|boolean',
            'banheiro_privativo'  => 'nullable|boolean',
            'cafeteira'           => 'nullable|boolean',
            'cama_casal'          => 'nullable|boolean',
            'cameras'             => 'nullable|boolean',
            'churrasqueira'       => 'nullable|boolean',
            'condominiofechado'   => 'nullable|boolean',
            'cozinha'             => 'nullable|boolean',
            'elevador'            => 'nullable|boolean',
            'espaco_fitness'      => 'nullable|boolean',
            'estacionamento'      => 'nullable|boolean',
            'fechadura_eletronica'=> 'nullable|boolean',
            'ferro_passar'        => 'nullable|boolean',
            'fornopizza'          => 'nullable|boolean',
            'frigobar'            => 'nullable|boolean',
            'garagem'             => 'nullable|boolean',
            'geladeira'           => 'nullable|boolean',
            'interfone'           => 'nullable|boolean',
            'jardim'              => 'nullable|boolean',
            'lareira'             => 'nullable|boolean',
            'lavabo'              => 'nullable|boolean',
            'maquina_lavar'       => 'nullable|boolean',
            'mesa_refeicao'       => 'nullable|boolean',
            'mesa_trabalho'       => 'nullable|boolean',
            'microondas'          => 'nullable|boolean',
            'mobiliado'           => 'nullable|boolean',
            'permiteanimais'      => 'nullable|boolean',
            'piscina'             => 'nullable|boolean',
            'portaria24hs'        => 'nullable|boolean',
            'pratos_talheres'     => 'nullable|boolean',
            'produtos_limpeza'    => 'nullable|boolean',
            'roupa_cama'          => 'nullable|boolean',
            'sauna'               => 'nullable|boolean',
            'salaodejogos'        => 'nullable|boolean',
            'secador_cabelo'      => 'nullable|boolean',
            'secadora'            => 'nullable|boolean',
            'tv'                  => 'nullable|boolean',
            'tv_netflix'          => 'nullable|boolean',
            'ventilador_teto'     => 'nullable|boolean',
            'vista_para_mar'      => 'nullable|boolean',
            'wifi'                => 'nullable|boolean',

            'display_marked_water'  => 'nullable|boolean',
            'youtube_video'         => 'nullable|string',                            
            'publication_type'      => 'nullable|integer',                            
        ];
    }
    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $sale     = $this->input('sale', false);
    //         $location = $this->input('location', false);

    //         if (!$sale && !$location) {
    //             $validator->errors()->add('sale', 'Selecione pelo menos uma finalidade (Venda ou Locação).');
    //         }
    //     });
    // }
}
