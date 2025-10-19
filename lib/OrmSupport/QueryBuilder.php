<?php

declare(strict_types=1);

namespace Libra\BxApiHelper\OrmSupport;

use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;

class QueryBuilder extends Query
{
    /**
     * @param $method
     * @param $arguments
     * @return QueryBuilder
     * @throws SystemException
     */
    public function __call($method, $arguments)
    {
        if (str_starts_with($method, 'scope'))
        {
            $dataClass = $this->entity->getDataClass();

            if (method_exists($dataClass, $method))
            {
                // set query as first element
                array_unshift($arguments, $this);

                call_user_func_array(
                    [$dataClass, $method],
                    $arguments
                );

                return $this;
            }
        }

        return parent::__call($method, $arguments);
    }
}