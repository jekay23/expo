<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Photo
{
    private array $data;
    private Like $like;
    private array $wrapper;

    public function __construct(array $photo)
    {
        foreach ($photo as $key => $value) {
            $this->data[$key] = $value;
        }
        $this->like = new Like($photo['liked']);
    }

    public function addClass(string $class)
    {
        if (!isset($this->data['class'])) {
            $this->data['class'] = '';
        } else {
            $this->data['class'] .= ' ';
        }
        $this->data['class'] .= $class;
    }

    public function setWrapper(array $wrapper)
    {
        if (!isset($this->wrapper)) {
            $this->wrapper = [];
        }
        foreach ($wrapper as $key => $value) {
            $this->wrapper[$key] = $value;
        }
    }

    public function getWrapper(string $key)
    {
        return $this->wrapper[$key] ?? '';
    }

    public function render()
    {
        ob_start();
        $photo = $this->data;
        $like = $this->like;
        View::requireTemplate('photo', 'Component', compact('photo', 'like'));
        return ob_get_clean();
    }
}
