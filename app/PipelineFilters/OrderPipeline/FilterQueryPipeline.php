<?php

namespace App\PipelineFilters\OrderPipeline;

use App\Models\Order;
use Closure;

class FilterQueryPipeline
{

    public function handle($params, Closure $next)
    {
        $getOrder =  Order::query();

        $this->getByKey($params, $getOrder);
        $this->getByWord($params, $getOrder);
        $this->getBySort($params, $getOrder);

        return $next($getOrder);
    }
    private function getByKey($params, $getOrder)
    {
        if (!empty($params['by_id']) && $params['by_id']) {
            $getOrder->where('id', $params['by_id']);
        }
        if (!empty($params['by_email']) && $params['by_email']) {
            $getOrder->where('contact_email', $params['by_email']);
        }
        if (!empty($params['by_user'])  && $params['by_user']) {
            $getOrder->where('fk_user_id', $params['by_user']);
        }
        if (!empty($params['by_order_number']) && $params['by_order_number']) {
            $getOrder->where('order_number', $params['by_order_number']);
        }

        return $getOrder;
    }

    private function getByWord($params, $getOrder)
    {
        if (!empty($params['keyword'])) {
            $keyword = $params['keyword'];
            $getOrder->where(function ($getOrder) use ($keyword) {
                $getOrder->where('shipping_first_name', 'like', '%' . $keyword . '%');
                $getOrder->orWhere('shipping_last_name', 'like', '%' . $keyword . '%');
                $getOrder->orWhere('order_number', 'like', '%' . $keyword . '%');
                $getOrder->orWhere('contact_email', 'like', '%' . $keyword . '%');
            });
        }
        return $getOrder;
    }

    private function getBySort($params, $getOrder)
    {
        if (!empty($params['sort_by'])) {

            if (!empty($params['sort_type'])) {

                $sort_type = $params['sort_type'];
            } else {
                $sort_type = 'desc';
            }

            $getOrder->orderBy('' . $params['sort_by'] . '', $sort_type);
        } else {

            $getOrder->orderBy('created_at', 'desc');
        }

        return $getOrder;
    }
}
