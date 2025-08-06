<?php

namespace App\Http\Requests\TaxonomyRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Interfaces\TaxonomyInterface;
use App\Models\Taxo_list;
use App\Models\Product_categories;
use App\Models\Product_collection;
use  Illuminate\Validation\ValidationException;

class TaxonomyDestroyRequestValidation extends FormRequest
{
    private $taxonomyInterface;

    public function __construct(TaxonomyInterface $taxonomyInterface)
    {
        $this->taxonomyInterface            = $taxonomyInterface;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->hasRole('super_admin')) 
        {
             return true;
        }
        
        if(Auth::user()->can('taxonomy_destroy')) {
            return true;
        }
      
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
  
    public function rules()
    {
        return [
            'by_id'                 =>   'required|exists:taxo_lists,id'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'taxonomy id perlu diisi',
        'by_id.exists'                 => 'taxonomy id tidak tersedia',

       ]; 
    }
    protected function passedValidation() {

        //check if id registered as parent
        $checkdata =  Taxo_list::where('parent',$this->by_id)->first();
        if(!$checkdata){
            $remove =  Taxo_list::where('id',$this->by_id)->first();
            $productCategory1 = Product_categories::where('fk_category_id', $remove->id)->exists();
            $productCollection = Product_collection::where('fk_collection_id', $remove->id)->exists();
    
            if($remove->parent != '' || $remove->parent != NULL){
                $productCategory2 = Product_categories::where('fk_category_id', $remove->parent)->exists();
                if($productCategory1 && $productCategory2){
                    throw ValidationException::withMessages([
                        'destroy repository' => ['Failed to delete subcategory because it is still used in the product'],
                    ]);
                }
            }
    
            if($productCategory1 && !$productCollection){
                throw ValidationException::withMessages([
                    'destroy repository' => ['Failed to delete category because it is still used in the product'],
                ]);
            }
            elseif(!$productCategory1 && $productCollection){
                throw ValidationException::withMessages([
                    'destroy repository' => ['Failed to delete collection because it is still used in the product'],
                ]);
            }
        }
        else{
            throw ValidationException::withMessages([
                'destroy repository' => ['this name contain child data'],
            ]);
        }
    }
}
