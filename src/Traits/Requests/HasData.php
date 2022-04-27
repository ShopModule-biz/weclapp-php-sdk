<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Traits\Requests;

trait HasData
{
    protected $data;
    
    public function getData()
    {
        return $this->data;
    }
    
    public function setData($key, $value = null)
    {
        if (null !== $value) {
          $data = [];
          $this->buildData($data, $key, $value);
        } else {
            $data = $key;
        }
        
        $this->assignData($data, $this->data);
        
        return $this;
    }
    
    protected function buildData(&$data, $key, $value)
    {
        $keys = explode('.', $key);
        
        // extract the last key
        $last = array_pop($keys);
        
        // walk/build the array to the specified key
        while ($aKey = array_shift($keys)) {
            if ( ! array_key_exists($aKey, $data)) {
                $data[$aKey] = [];
            }
            $data = &$data[$aKey];
        }

        // set the final key
        $data[$last] = $value;
      }
    
    protected function assignData($data, &$holder)
    {
        foreach ($data as $k => $v) {
            if (is_array($v) && isset($holder[$k]) && is_array($holder[$k])) {
                $this->assignData($v, $holder[$k]);
            } else {
                $holder[$k] = $v;
            }
        }
    }
  
}
