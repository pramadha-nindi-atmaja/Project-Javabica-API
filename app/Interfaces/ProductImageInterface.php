<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProductImageInterface {

    public function show(Request $request,$getOnlyColumn);
    public function upsert($data);
    public function destroy(int $id);

}
