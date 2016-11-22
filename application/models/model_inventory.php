<?php
class Model_inventory extends Model_template
{
   function __construct()
   {
       parent::__construct();
       $this->load->helper('my_dates_helper');       
   }
   #############################################################################
   public function update_inventory($properties, $conditions)
   {
      $this->db->update("inventory", $properties, $conditions);
   }
   #############################################################################
   public function get_inventory_by_id($inventory_id)
   {
      $sql = "SELECT inv.*, cin.name AS category
              FROM inventory inv,
                   category_inventory cin
              WHERE inv.category_inventory_id=cin.id
                AND inv.id=".$inventory_id;
      
      $query = $this->db->query($sql);
      return $query->row_array();
   }
   #############################################################################
   public function delete_inventory($inventory_id)
   {
      $this->db->where('id', $inventory_id);
      $this->db->delete('inventory');
   }
   #############################################################################
   public function get_list_inventory($company_id)
   {
      $sql="SELECT * FROM inventory WHERE company_id=".$company_id.";";      
      $query = $this->db->query($sql);     
      return $query->result_array();
   }
   #############################################################################
   public function add_inventory($properties)
   {
      $this->db->insert('inventory', $properties);
      return $this->db->insert_id();
   }
   
   #############################################################################
   public function get_list_category_inventory()
   {
      $sql="SELECT * FROM category_inventory;";
      
      $query = $this->db->query($sql);
     
      return $query->result_array();
   }
   
   #############################################################################
   public function add_category_inventory($properties)
   {
      $this->db->insert('category_inventory', $properties);
   }
}
?>
