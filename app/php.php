<?php
final class PHP
{
    private $data = array();

    public function set($data)
    {
        foreach ($data as $key => $value) {
			$this->data[$key] = $value;
        }
	}

    public function render($template)
    {
        $file = SERVER . '/app/view/' . $template;

        if (is_file($file)) {
			extract($this->data);
            ob_start();

            require $file;

            return ob_get_clean();
        }

        trigger_error('Error: Could not load template ' . $file . '!');
        exit();
    }
}
