<?php

namespace Inc;

/**
 * Class View
 * @package Inc
 */
class View
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param $view
     * @param array $vars
     */
    function render($view, array $vars = [])
    {
        ob_start();
        foreach ( $vars as $key => $value) {
            ${$key} = $value;
        }
        $errorsHtml = $this->renderErrors();
        require_once ('views/' . $view);
        echo ob_get_clean();
    }

    /**
     * @param array $array
     */
    public function setErrors(array $array)
    {
        $this->errors = array_merge($this->errors, $array);
    }

    /**
     * @return string
     */
    public function renderErrors(){
        $html = '';
        foreach ($this->errors as $error){
            $html .= '<b style="color: red">'. $error .'</b><br>';
        }
        return $html;
    }
}