<?php

namespace Lle\EasyAdminPlusBundle\Filter\FilterType;

/**
 * AbstractFilterType
 *
 * Abstract base class for all admin list filters
 */
abstract class AbstractFilterType implements FilterTypeInterface
{
    /**
     * @var null|string
     */
    protected $columnName = null;

    protected $hidden = false;
    /**
     * @var null|string
     */
    protected $alias = null;

    protected $request = null;

    protected $data = null;

    /**
     * @param string $columnName The column name
     * @param string $alias The alias
     */
    public function __construct($columnName, $config = array(), $alias = 'b')
    {
        $this->columnName = $columnName;
        $this->alias = $alias;
        $this->hidden = (isset($config['hidden'])) ? $config['hidden'] : false;
        $this->data = [];
    }

    /**
     * Returns empty string if no alias, otherwise make sure the alias has just one '.' after it.
     *
     * @return string
     */
    protected function getAlias()
    {
        if (empty($this->alias)) {
            return '';
        }

        if (strpos($this->alias, '.') !== false) {
            return $this->alias;
        }

        return $this->alias . '.';
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getValueSession($id)
    {
        $session = $this->request->getSession();
        if ($this->request->request->has('reset') && $this->request->request->get('reset') === 'reset') {
            $session->remove($id);
            return null;
        }
        $new_val = $this->request->request->get($id, null);
        if ($new_val) {
            $session->set($id, $new_val);
            return $new_val;
        } else {
            if (!($this->request->request->has('filter') && $this->request->request->get('filter') === 'filter')) {
                return $session->get($id, null);
            } else {
                $session->remove($id);
                return null;
            }
        }

    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __sleep()
    {
        return array('columnName', 'alias', 'data');
    }
}
