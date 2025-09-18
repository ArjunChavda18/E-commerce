<?php

class Modal {
    public $id = null;
    public $limit = 4; // default limit for pagination
    public $page = 1; // default page number

    public function __construct($id) {
        // Initialize with product ID
        $this->id = $id;
    }
}
    
?>