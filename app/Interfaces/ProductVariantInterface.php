<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProductVariantInterface {

    public function show(Request $request,$getOnlyColumn);
    public function upsert($data);
    public function destroy($data);
   

}
