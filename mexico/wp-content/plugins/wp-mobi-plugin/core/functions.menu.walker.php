<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MobiWPMenutoArray
{
    private $flat_menu;
    public $items;

    function __construct($id)
    {
        $this->flat_menu = wp_get_nav_menu_items($id);
        $this->items = array();
        foreach ($this->flat_menu as $item) {
            if (!$item->menu_item_parent) {
                array_push($this->items, $item);
            }
        }
    }

    public function get_submenu($item)
    {
        $submenu = array();
        foreach ($this->flat_menu as $subitem) {
            if ($subitem->menu_item_parent == $item->ID) {
                array_push($submenu, $subitem);
            }
        }
        return $submenu;
    }
}
?>
