<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\OrderProductResource\OrderProductShowAllResource;
use App\Interfaces\OrderProductInterface;
use App\Models\Order_product;
use App\PipelineFilters\OrderProductPipeline\GetByKey;
use App\PipelineFilters\OrderProductPipeline\GetByWord;
use App\PipelineFilters\OrderProductPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;




class OrderProductRepository extends BaseController implements OrderProductInterface
{
    public function show($request, $getOnlyColumn)
    {

        try {
            $getData =  app(Pipeline::class)
                ->send(Order_product::query())
                ->through([
                    GetByKey::class,
                    GetByWord::class,
                    UseSort::class,
                ])
                ->thenReturn()
                ->select($getOnlyColumn);

            if (request()->get('paginate') == true) {
                $outputData            =  $getData->paginate(request()->get('per_page'), $getOnlyColumn, 'page', request()->get('page'));
                $getCollection         =  $outputData->getCollection();
            } else {
                $getCollection  =   $getData->limit(250)->get();
            }
            $itemsTransformed = $getCollection
                ->map(function ($item) {

                    return $this->resourceFormat('show_all', $item);
                });


            if (count($getCollection) > 1 || request()->get('paginate') == true) {

                $itemsTransformed =  $itemsTransformed->toArray();
            } else {
                $itemsTransformed =  $itemsTransformed->toArray();
            }


            if (request()->get('paginate') == true) {
                $outputData = new \Illuminate\Pagination\LengthAwarePaginator(
                    $itemsTransformed,
                    $outputData->total(),
                    $outputData->perPage(),
                    $outputData->currentPage(),
                    [
                        'path' => \Request::url(),
                        'query' => request()->all()
                    ]
                );

                $message =   'get order product with paginate success';
            } else {

                $outputData =  $itemsTransformed;
                if (count($getCollection) > 1) {
                    $message =   'get order product data success without pagination max 250 data';
                } else {
                    $message =   'get order product data success';
                }
            }


            return $this->handleQueryArrayResponse($outputData, $message);
        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(), 'error when get order');
        }
    }
    public function store($data, $returnCollection)
    {

        try {
            $create = Order_product::create($data);

            if ($create) {

                $reformatUpdate =  $this->resourceFormat($returnCollection, $create);
                return $this->handleQueryArrayResponse($reformatUpdate, 'insert order product Success');
            } else {

                return $this->handleQueryErrorArrayResponse($create, 'insert order product fail');
            }
        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(), 'error when store order product');
        }
    }
    public function update($id, $data, $returnCollection)
    {

        try {
            $update  =  Order_product::find($id);

            if ($update) {

                $update->update($data);

                $refotmatData =  $this->resourceFormat($returnCollection, $update);

                return $this->handleQueryArrayResponse($refotmatData, 'update order product success');
            } else {

                return $this->handleQueryErrorArrayResponse($update, 'updates fail - order product id not found');
            }
        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(), 'error when update order product');
        }
    }
    public function destroy($id)
    {

        try {
            $remove =  Order_product::where('id', $id)->delete();

            if ($remove == true) {
                return $this->handleQueryArrayResponse($remove, 'destroy order product success');
            } else {
                return $this->handleQueryErrorArrayResponse($remove, 'destroy order product fail');
            }
        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(), 'error when destory order product');
        }
    }
    private  function resourceFormat($returnCollection, $data)
    {

        if ($returnCollection == 'show_all') //faq service & experience
        {
            return new OrderProductShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
    }
}
