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
            'sale'     => 'boolean',
            'location' => 'boolean',
            //'highlight'     => 'nullable|boolean',            
            'title'         => 'required',
            'category'      => 'required|string',
            'expired_at'    => 'nullable|date_format:d/m/Y|after_or_equal:start', 
            'type'          => 'required|string',
            'status'        => 'nullable|integer',

            // Pricing and Values
            'display_values' => 'nullable|boolean', 
            'sale_value'     => 'nullable|numeric',
            'rental_value'   => 'nullable|numeric',
            'location_period'=> 'nullable|integer',
            'iptu'           => 'nullable|numeric',
            'condominium'    => 'nullable|numeric',

            // Basic Info
            'reference'         => 'nullable|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'url_booking'       => 'nullable|string|max:255',
            'url_arbnb'         => 'nullable|string|max:255',
            'headline'          => 'nullable|string|max:255',
            'experience'        => 'nullable|string|max:255',
            'metatags'          => 'nullable|array',
            'google_map'        => 'nullable|string',

            // Description
            'description'       => 'nullable|string',
            'additional_notes'  => 'nullable|string',
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
            'ar_condicionado'   => 'nullable|boolean',
            'areadelazer'       => 'nullable|boolean',
            'aquecedor_solar'   => 'nullable|boolean',
            'bar'               => 'nullable|boolean',
            'banheirosocial'    => 'nullable|boolean',
            'brinquedoteca'     => 'nullable|boolean',
            'biblioteca'        => 'nullable|boolean',
            'balcaoamericano'   => 'nullable|boolean',
            'churrasqueira'     => 'nullable|boolean',
            'condominiofechado' => 'nullable|boolean',
            'estacionamento'    => 'nullable|boolean',
            'cozinha_americana' => 'nullable|boolean',
            'cozinha_planejada' => 'nullable|boolean',
            'dispensa'          => 'nullable|boolean',
            'edicula'           => 'nullable|boolean',
            'espaco_fitness'    => 'nullable|boolean',
            'escritorio'        => 'nullable|boolean',
            'banheira'          => 'nullable|boolean',
            'geradoreletrico'   => 'nullable|boolean',
            'interfone'         => 'nullable|boolean',
            'jardim'            => 'nullable|boolean',
            'lareira'           => 'nullable|boolean',
            'lavabo'            => 'nullable|boolean',
            'lavanderia'        => 'nullable|boolean',
            'elevador'          => 'nullable|boolean',
            'mobiliado'         => 'nullable|boolean',
            'vista_para_mar'    => 'nullable|boolean',
            'piscina'               => 'nullable|boolean',
            'quadrapoliesportiva'   => 'nullable|boolean',
            'sauna'                 => 'nullable|boolean',
            'salaodejogos'          => 'nullable|boolean',
            'salaodefestas'         => 'nullable|boolean',
            'sistemadealarme'       => 'nullable|boolean',
            'saladetv'              => 'nullable|boolean',
            'ventilador_teto'       => 'nullable|boolean',
            'armarionautico'        => 'nullable|boolean',
            'fornodepizza'          => 'nullable|boolean',
            'portaria24hs'          => 'nullable|boolean',
            'permiteanimais'        => 'nullable|boolean',
            'pertodeescolas'        => 'nullable|boolean',
            'quintal'               => 'nullable|boolean',
            'zeladoria'             => 'nullable|boolean',
            'varandagourmet'        => 'nullable|boolean',
            'internet'              => 'nullable|boolean',
            'geladeira'             => 'nullable|boolean',

            'display_marked_water'  => 'nullable|boolean',
            'youtube_video'         => 'nullable|string',                            
            'publication_type'      => 'nullable|integer',                            
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sale     = $this->input('sale', false);
            $location = $this->input('location', false);

            if (!$sale && !$location) {
                $validator->errors()->add('sale', 'Selecione pelo menos uma finalidade (Venda ou Locação).');
            }
        });
    }
}
