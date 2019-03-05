<?php

namespace AtlasVG\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Return the svg map file path.
     * @param string $path
     * @return string
     */
    public function getMap($path)
    {
        $path = $this->getMapPath($path);
        if (is_readable($path)) {
            return file_get_contents($path);
        }
        return '';
    }

    /**
     * Return the svg file content
     * @param string $path
     * @return string
     */
    public function getMapPath($path)
    {
        return base_path('resources/maps/' . $path);
    }
}
