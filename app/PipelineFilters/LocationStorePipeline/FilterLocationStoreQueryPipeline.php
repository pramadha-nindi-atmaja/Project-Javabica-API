<?php

namespace App\PipelineFilters\LocationStorePipeline;

use App\Models\Location_stores;
use App\Models\Order;
use Closure;

class FilterLocationStoreQueryPipeline
{

    public function handle($params, Closure $next)
    {
        $getData =  Location_stores::query();

        $this->getByKey($params, $getData);
        $this->getByWord($params, $getData);
        $this->getBySort($params, $getData);

        return $next($getData);
    }


    private function getByKey($params, $getData)
    {
        if (!empty($params['by_id']) && $params['by_id']) {
            $getData->where('id', $params['by_id']);
        }

        if (!empty($params['by_province']) && $params['by_province']) {
            $getData->where('fk_province', $params['by_province']);
        }
       
        return $getData;
    }



    private function getByWord($params, $getData)
    {
        if (!empty($params['keyword'])) {
            $keyword = $params['keyword'];
            $getData->where(function ($getData) use ($keyword) {
                $getData->where('name', 'like', '%' . $keyword . '%');
             
            });
        }
        return $getData;
    }



    private function getBySort($params, $getData)
    {
        if (!empty($params['sort_by'])) {

            if (!empty($params['sort_type'])) {

                $sort_type = $params['sort_type'];
            } else {
                $sort_type = 'desc';
            }

            $getData->orderBy('' . $params['sort_by'] . '', $sort_type);

        } else {

            $getData->orderBy('created_at', 'desc');
        }

        return $getData;
    }
}
