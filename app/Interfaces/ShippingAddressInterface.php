<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ShippingAddressInterface {

    public function show($request,array $getOnlyColumn);
    public function store(array $data,$returnCollection);
    public function update($id,array $data,$returnCollection);
    public function destroy($id);
}
