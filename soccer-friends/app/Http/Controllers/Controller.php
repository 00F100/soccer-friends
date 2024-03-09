<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Method for get query pagination/sort
     * 
     * @param Request HTTP Request instance
     * @param string Default sort
     * @param string Default order
     * @param int Default per page
     * @param int Default current page 
     */
    protected function getQueryParams(
      Request $request,
      string $defaultSoft,
      string $defaultOrder = 'asc',
      int $defaultPerPage = 10,
      int $defaultPage = 1
    )
    {
      $perPage = $request->get('perPage', $defaultPerPage);
      $page = $request->get('page', $defaultPage);
      $sort = $request->get('sort', $defaultSoft);
      $order = $request->get('order', $defaultOrder);

      return compact('perPage', 'page', 'sort', 'order');
    }
    
}
