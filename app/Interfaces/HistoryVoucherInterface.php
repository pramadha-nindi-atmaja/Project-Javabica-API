<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HistoryVoucherInterface {

    public function show($request,$getOnlyColumn);
    public function store(array $data,$returnCollection);
    public function update($id,array $data,$return_collection);
    public function destroy(int $id);

}
