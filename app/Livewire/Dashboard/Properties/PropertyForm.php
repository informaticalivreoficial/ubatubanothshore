<?php

namespace App\Livewire\Dashboard\Properties;

use App\Http\Requests\Admin\StoreUpdatePropertyRequest;
use App\Models\Config;
use App\Models\Property;
use App\Models\PropertyGb;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PropertyForm extends Component
{
    use WithFileUploads;

    public ?Property $property = null;

    //public array $types = ['venda', 'locacao'];

    public array $images = [];
    public $savedImages = [];

    public string $currentTab = 'dados'; 
    public ?string $expired_at = null;

    public $category, $type,
       $rental_value, $location_period, $iptu, $construction_year, $min_nights,
       $cleaning_fee, $aditional_person, $value_aditional,
       $reference, $condominium, $description, $additional_notes, $politica_cancelamento,
       $dormitories, $capacity, $suites, $bathrooms, $rooms, $garage, $covered_garage,
       $total_area, $useful_area, $measures,
       $latitude, $longitude, 
       // Address
       $zipcode, $street, $number, $complement,
       $neighborhood, $state, $city,
       
       // Acessórios
        $acesso_praia,
        $adequado_criancas,
        $adequado_idosos,
        $agua_quente,
        $aquecedor,
        $ar_condicionado,
        $areadelazer,
        $banheira,
        $banheiro_privativo,
        $cafeteira,
        $cama_casal,
        $cameras,
        $churrasqueira,
        $condominiofechado,
        $cozinha,
        $elevador,
        $espaco_fitness,
        $estacionamento,
        $fechadura_eletronica,
        $ferro_passar,
        $fornopizza,
        $frigobar,
        $garagem,
        $geladeira,
        $interfone,
        $jardim,
        $lareira,
        $lavabo,
        $maquina_lavar,
        $mesa_refeicao,
        $mesa_trabalho,
        $microondas,
        $mobiliado,
        $permiteanimais,
        $piscina,
        $portaria24hs,
        $pratos_talheres,
        $produtos_limpeza,
        $roupa_cama,
        $sauna,
        $salaodejogos,
        $secador_cabelo,
        $secadora,
        $tv,
        $tv_netflix,
        $ventilador_teto,
        $vista_para_mar,
        $wifi,

       $title, $slug, $status, $views,
       $headline, $youtube_video, $caption_img_cover,
       $google_map, $experience, $highlight, $publication_type;

    public array $metatags = [];

    public ?int $display_address = 0; // 0 = Não, 1 = Sim
    public int $display_values = 0; // 0 = Não, 1 = Sim
    public ?int $display_marked_water = 0; // 0 = Não, 1 = Sim

    protected $booleanFields = [
        'acesso_praia',
        'adequado_criancas',
        'adequado_idosos',
        'agua_quente',
        'aquecedor',
        'ar_condicionado',
        'areadelazer',
        'banheira',
        'banheiro_privativo',
        'cafeteira',
        'cama_casal',
        'cameras',
        'churrasqueira',
        'condominiofechado',
        'cozinha',
        'elevador',
        'espaco_fitness',
        'estacionamento',
        'fechadura_eletronica',
        'ferro_passar',
        'fornopizza',
        'frigobar',
        'garagem',
        'geladeira',
        'interfone',
        'jardim',
        'lareira',
        'lavabo',
        'maquina_lavar',
        'mesa_refeicao',
        'mesa_trabalho',
        'microondas',
        'mobiliado',
        'permiteanimais',
        'piscina',
        'portaria24hs',
        'pratos_talheres',
        'produtos_limpeza',
        'roupa_cama',
        'sauna',
        'salaodejogos',
        'secador_cabelo',
        'secadora',
        'tv',
        'tv_netflix',
        'ventilador_teto',
        'vista_para_mar',
        'wifi',
    ];

    public function render()
    {
        $titlee = $this->property->exists ? 'Editar Imóvel' : 'Cadastrar Imóvel';
        return view('livewire.dashboard.properties.property-form')->with([
            'titlee' => $titlee,
        ]);
    }

    public function mount(Property $property)
    {
        if ($property->exists) {
            $this->property = $property;

            $this->display_address = $property->exists ? (int) $property->display_address : 0;
            $this->display_values = $property->exists ? (int) $property->display_values : 0;
            $this->display_marked_water = $property->exists ? (int) $property->display_marked_water : 0;

            // Preenche todos os campos exceto metatags
            $data = collect($property->toArray())
                ->except(['metatags', 'display_marked_water'])
                ->toArray();
            $this->fill($data);

            // Converte booleanos
            foreach ($this->booleanFields as $field) {
                $this->{$field} = (bool) $property->{$field};
            }

            // Metatags como array
            $this->metatags = is_string($property->metatags)
                ? explode(',', $property->metatags)
                : [];
        } else {
            $this->property = new Property();
        }
    }

    // Salvar (create ou update)
    public function save(string $mode = 'draft')
    {
        
        try {       
            //dd('entrou no save');     
            // Validação principal            
            $validated = $this->validate((new StoreUpdatePropertyRequest())->rules()); 

            // Campos de moeda
            $validatedData['rental_value'] = $this->rental_value ? str_replace([',','R$',' '], ['', '.', ''], $this->rental_value) : 0;
            $validatedData['value_aditional'] = $this->value_aditional ? str_replace([',','R$',' '], ['', '.', ''], $this->value_aditional) : 0;
            $validatedData['cleaning_fee'] = $this->cleaning_fee ? str_replace([',','R$',' '], ['', '.', ''], $this->cleaning_fee) : 0;

            // Converte array de metatags em string para o banco
            $validated['metatags'] = implode(',', $this->metatags ?? []);
            // status depende do botão
            $validated['status'] = $mode === 'published' ? 1 : 0;  
                      
            
            foreach ($this->booleanFields as $field) {
                $validated[$field] = (bool) $this->{$field};
            }            

            if($this->property->exists){
                //Atualizar

                $this->property->update($validated);

                // Validação das imagens
                $this->validate([
                    'images.*' => 'image|mimes:jpeg,jpg,png,webp,heic|max:2048',
                ]);                

                $maxImages = env('MAX_PROPERTY_IMAGES', 35);
                $existingImages = $this->property->images()->count();
                $allowed = $maxImages - $existingImages;
                if (count($this->images ?? []) > $allowed) {
                    $this->dispatch('swal:warning', [
                        'title' => 'Atenção!',
                        'text' => "Este imóvel tem um limite de {$maxImages} imagens.",
                        //'timer' => 2000,
                        'icon' => 'warning',
                        'showConfirmButton' => false
                    ]);                    
                    return;
                }

                $manager = new ImageManager(new Driver());

                foreach ($this->images as $index => $image) {

                    if ($index >= $allowed) break;

                    $filename = uniqid() . '.webp';
                    $path = 'properties/' . $this->property->id . '/' . $filename;

                    // abrir imagem
                    $img = $manager->read($image->getRealPath());

                    // redimensionar (opcional mas recomendado)
                    $img->scaleDown(width: 1920);

                    // converter para webp
                    $encoded = $img->toWebp(85);

                    // salvar no storage
                    Storage::disk('public')->put($path, $encoded);

                    $maxOrder = PropertyGb::where('property', $this->property->id)->max('order_img') ?? 0;

                    PropertyGb::create([
                        'property' => $this->property->id,
                        'path' => $path,
                        'cover' => $this->cover ?? null,
                        'order_img' => $maxOrder + $index + 1,
                        'watermark' => false
                    ]);
                }  
    
                // Limpar imagens temporárias
                $this->reset('images');

                $this->dispatch('swal:success', [
                    'title' => 'Sucesso!',
                    'text' => 'Imóvel atualizado com sucesso!',
                    'timer' => 2000,
                    'showConfirmButton' => false
                ]);
            }else{               
                
                $property = Property::create($validated);
                $this->reset('images');

                $this->dispatch('swal:success', [
                    'title' => 'Sucesso!',
                    'text' => 'Imóvel cadastrado com sucesso!',
                    'timer' => 2000,
                    'showConfirmButton' => false,
                    'redirectUrl' => route('property.edit', ['property' => $property->id]),
                ]);
                
                $this->property = $property; // Atualiza a propriedade para o novo registro
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            //dd($e->errors());
            // Muda para a aba "dados" se houver erro
            $this->currentTab = 'dados';
            throw $e; // Deixa Livewire lidar com os erros e mostrar mensagens
        }        
    }

    //Remover imagem temporária
    public function removeTempImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    //Remover imagem do Bd
    public function removeSavedImage($id)
    {
        $image = PropertyGb::find($id);
        if ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
            $this->savedImages = collect($this->savedImages)->filter(fn ($img) => $img->id !== $id);
            $this->property->refresh(); // Para garantir que os dados estejam atualizados
        }
    }

    public function toggleCover($imageId)
    {
        $image = PropertyGb::where('id', $imageId)->where('property', $this->property->id)->first();

        if ($image) {
            if ($image->cover) {
                // Se já é capa, remove
                $image->update(['cover' => 0]);
            } else {
                // Remove capa das outras e define esta
                PropertyGb::where('property', $this->property->id)->update(['cover' => 0]);
                $image->update(['cover' => 1]);
            }

            // Atualiza a relação para refletir na view
            $this->property->refresh();
        }
    }

    public function updatedZipcode(string $value)
    {        
        $this->zipcode = preg_replace('/[^0-9]/', '', $value);

        if(strlen($this->zipcode) === 8){
            $response = Http::get("https://viacep.com.br/ws/{$this->zipcode}/json/")->json();            
            if(!isset($response['erro'])){                
                $this->street = $response['logradouro'] ?? '';
                $this->neighborhood = $response['bairro'] ?? '';
                $this->state = $response['uf'] ?? '';
                $this->city = $response['localidade'] ?? '';
                $this->complement = $response['complemento'] ?? '';      
            }else{                
                $this->addError('zipcode', 'CEP não encontrado.'); 
            }
        }
    }

    #[On('updateDescription')]
    public function updateDescription($value)
    {
        $this->description = $value;
    }

    public function updateImageOrder($order)
    {
        try {
            foreach ($order as $item) {
                PropertyGb::where('id', $item['id'])
                    ->where('property', $this->property->id)
                    ->update(['order_img' => $item['position']]);
            }
            
            // Atualiza a propriedade para refletir a nova ordem
            $this->property->refresh();
            
        } catch (\Exception $e) {
            $this->toastError('Erro ao atualizar ordem das imagens: ' . $e->getMessage());
        }
    }

    public function applyWatermarkImage($imageId)
    {
        $image = PropertyGb::find($imageId);

        if ($image->watermarked) {
            return;
        }

        $config = Config::first();

        $manager = new ImageManager(new Driver());

        $img = $manager->read(storage_path('app/public/'.$image->path));
        $watermark = $manager->read(storage_path('app/public/'.$config->watermark));

        $img->place($watermark, 'bottom-right', 30, 30);
        $img->save();

        $image->update([
            'watermark' => true
        ]);

        $this->dispatch('swal:success', [
            'title' => false,
            'text' => 'Marca d’água aplicada!',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);
    }
}
