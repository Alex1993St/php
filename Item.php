<?php

class Item
{
    use TraitDatabase;

    public $defaultPerPage = 30;

    public function __construct()
    {
        $this->connect();
    }

    public function getInfoAndProperty($param)
    {
        $where = $this->filter($param);

        $page = isset($param['page']) && $param['page'] > 1 ? $param['page'] : 1;
        $perPage = $this->perPage($param);
        $limit = ' LIMIT ' . ($page - 1) * $perPage . ', ' . $perPage;

        return $this->getInfo($where, $limit);
    }

    public function total($param)
    {
        $where = $this->filter($param);

        return $this->getTotal($where);
    }

    public function pagination($param)
    {
        $total = $this->total($param);
        return $total ? ceil($total / $this->perPage($param)) : 0;
    }

    private function perPage($param)
    {
        return isset($param['per_page']) && $param['per_page'] ? $param['per_page'] : $this->defaultPerPage;
    }

    private function filter($param)
    {
        $where = " where 1 = 1";
        if ($param) {
            if (isset($param['town']) && $param['town']) {
                $where .= " and places.id = '" . trim($param['town']) . "'";
            }
            if (isset($param['number']) && $param['number']) {
                $where .= " and places.num_bedrooms = '" . trim($param['number']) . "'";
            }
            if (isset($param['price_from']) && $param['price_from']) {
                $where .= " and informations.price >= '" . trim($param['price_from']) . "'";
            }
            if (isset($param['price_to']) && $param['price_to']) {
                $where .= " and informations.price <= '" . trim($param['price_to']) . "'";
            }
            if (isset($param['property_type']) && $param['property_type']) {
                $where .= " and properties.title like '%" . trim($param['property_type']) . "%'";
            }
            if (isset($param['type']) && $param['type']) {
                $where .= " and types.id = '" . trim($param['type']) . "'";
            }
        }

        return $where;
    }

    public function getHref()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $href = '';
        if ($uri) {
            parse_str($uri, $items);
            $j = 1;
            unset($items['page']);
            foreach ($items as $key => $item) {
                $href .= $j == 1 ? '?' : '&';
                $href .= $key . '=' . $item;
                $j++;
            }
        }

        $href .= $href ? '&' : '?';

        return $href;
    }
}