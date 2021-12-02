<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Traits\Requests;

trait HasUrlParameter
{
    private function getUrlParameter(): ?string
    {
        $parameter = [];
        foreach ($this->collectUrlParameter() as $k => $v) {
            $parameter[] = $k . '=' . $v;
        }
        return 0 === count($parameter) ? null : implode('&', $parameter);
    }


    private function collectUrlParameter(): array
    {
        $parameter = [];
        foreach ($this->getUrlParameterMethods() as $method) {
            $parameter = array_merge($parameter, $this->$method());
        }
        return $parameter;
    }


    private function getUrlParameterMethods(): array
    {
        $methods = [];
        foreach (class_uses($this) as $uses) {
            $method = $this->buildGetUrlParameterMethodName($uses);
            if (method_exists($this, $method)) {
                $methods[] = $method;
            }
        }
        return $methods;
    }


    private function buildGetUrlParameterMethodName(string $trait): string
    {
        return 'get' . $this->classBasename($trait) . 'UrlParameter';
    }

    /**
     * Returns the base name of a class.
     *
     * @param string $classname
     * @return string
     */
    private function classBasename(string $classname): string
    {
        $pieces = explode('\\', $classname);
        return array_pop($pieces);
    }

}