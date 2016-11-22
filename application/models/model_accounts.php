<?php
class Model_accounts extends Model_template
{ 
   function __construct()
   {
       parent::__construct();       
       $this->db->query("SET SESSION time_zone='-4:00'");
   }
   #############################################################################################
   function delete_budget_range($budget_range_id)
   {
      $this->db->delete("budget_items", array("budget_range_id"=>$budget_range_id));      
      $this->db->delete("budget_ranges", array("id_budget_range"=>$budget_range_id));
   }
   #############################################################################################
   function add_budget_range($data)
   {
      $this->db->insert("budget_ranges", $data);
   }
   #############################################################################################
   function update_budget_item($data, $account_category_id, $budget_range_id)
   {
      $this->db->cache_on();
      $this->db->select("COUNT(*) AS count_budget_item");
      $this->db->from("budget_items");
      $this->db->where("account_category_id", $account_category_id);
      $this->db->where("budget_range_id", $budget_range_id);
      $query = $this->db->get();
      $row = $query->row_array();      
      $this->db->cache_off();
      
      $this->db->cache_on();      
      if($row['count_budget_item']<=0)
      {
         $this->db->insert('budget_items', $data);         
      }
      else
      {
         $conditions = array('account_category_id'=>$account_category_id,
                             'budget_range_id'=>$budget_range_id,
                             );
         $this->db->update('budget_items', $data, $conditions);         
      }
      $this->db->cache_off();
   }
   #############################################################################################
   function get_list_budget_items($budget_range_id)
   {
      $this->db->select("*");
      $this->db->from("budget_items");
      $this->db->where("budget_range_id", $budget_range_id);
      
      $query = $this->db->get();            
      return $query->result_array();
   }
   #############################################################################################
   function get_list_budget_ranges($company_id)
   {
      $this->db->select("id_budget_range,
                         title,
                         CAST(date_from AS DATE) AS date_from,
                         CAST(date_to AS DATE) AS date_to");
      $this->db->from("budget_ranges");
      $this->db->where("company_id", $company_id);
      
      $this->db->order_by("id_budget_range DESC ");
      
      $query = $this->db->get();            
      return $query->result_array();
   }
   ###############################################################
   function is_account_exists($name_item, $company_id)
   {
      $this->db->select("COUNT(*) AS count_accounts");
      $this->db->from("account_categories");
      $this->db->where("account_categories.name",$name_item);
      $this->db->where("account_categories.company_id",$company_id);
      
      $query = $this->db->get();
      
      echo $this->db->last_query();
      
      
      $row = $query->row_array();  
      return ($row['count_accounts']>0);
   }
   ###############################################################
   function min_max_date_item_for_company($company_id,$value)
   {
      if($value=="min")
      {
         $this->db->select("min(account_items.register_date) as register_date_item");
      }
      else {
         $this->db->select("max(account_items.register_date) as register_date_item");
      }
            
      $this->db->from("account_items");      
      $this->db->where("account_items.company_id",$company_id);
      
      $query = $this->db->get();
      $row = $query->row_array();
      
      return $row['register_date_item'];
   }
   
   ###############################################################
  
   ###############################################################
   function delete_account($id_account_category, $company_id)
   {
      $this->db->cache_on();
      $this->db->select("id_account_category");        
      $this->db->from("account_categories");
      $this->db->where("account_categories.parent_id",$id_account_category);
      
      $query = $this->db->get();
      $list_result = $query->result_array();
      if(count($list_result)>0)
      {
         for($i=0;$i<count($list_result);$i++)
         {
            $this->delete_account($list_result[$i]['id_account_category'],$company_id);
         }
      }
      $this->db->cache_off();
      /*--------------------------------------------------------*/
      $this->db->cache_on();
      $this->db->select("id_account_category");        
      $this->db->from("account_categories");
      $this->db->where("account_categories.deletable","no");
      $this->db->where("account_categories.company_id",$company_id);
      $query = $this->db->get();
      $row = $query->row_array();        
      $this->db->cache_off();
      /*--------------------------------------------------------*/
      $data =  array("account_category_id" => $row['id_account_category']);
      $conditions = array("account_category_id" => $id_account_category);
      /*--------------------------------------------------------*/
      $this->db->cache_on();      
      $this->db->update('account_items',$data, $conditions);
      $this->db->cache_off();
      /*--------------------------------------------------------*/
      $this->db->cache_on();      
      $this->db->update('budget_items',$data, $conditions);
      $this->db->cache_off();
      /*--------------------------------------------------------*/
      $this->db->delete('account_categories', array("id_account_category"=>$id_account_category)); 
   }
   ###############################################################
   function get_count_account_items_by_account($id_account_category)
   {
      $this->db->select("count(*) as count_items");        
      $this->db->from("account_items");
      $this->db->where("account_items.account_category_id",$id_account_category);
      
      $query = $this->db->get();      
      
      $row = $query->row_array();  
      return $row['count_items'];
   }
     
   ###############################################################
   function get_account_item($id_account_item)
   {
      $this->db->select("*");        
      $this->db->from("account_items");     
      $this->db->where("id_account_item", $id_account_item);     
      $query = $this->db->get();
            
      return $query->row_array();
   }
   ###############################################################
   function update_account_item($data, $conditions)
   {
      $this->db->update('account_items', $data, $conditions);
   }
   
   ###############################################################
   function get_balance($company_id)
   {
      $this->db->select("(sum(account_in) - sum(account_out)) AS balance",false);        
      $this->db->from("account_items");     
      $this->db->where("company_id", $company_id);     
      $query = $this->db->get();
      
      $row= $query->row_array();
      return $row['balance'];
   }
   ###############################################################
   function generate_list_accounts_tree2(&$list_tree, $company_id, $parent_id=null, $level=0)
   {
      $this->db->select("*");        
      $this->db->from("account_categories");     
      $this->db->where("parent_id", $parent_id);            
      $this->db->where("company_id", $company_id);            
      $query = $this->db->get();
      
      $list_result = $query->result_array();
            
      if(count($list_result)>0)
      {
         for($i=0;$i<count($list_result);$i++)
         {
            $list_result[$i]["level"] = $level;
            
            $row["account_category"] = $list_result[$i];            
            
            $list_tree[] = $row;

            $this->generate_list_accounts_tree2($list_tree, $company_id, $list_result[$i]['id_account_category'], $level+1);
         }
      }
   }
   ###############################################################
   function generate_list_accounts_tree(&$list_tree, $company_id, $parent_id=null, $level=0)
   {
      $this->db->select("*");        
      $this->db->from("account_categories");     
      $this->db->where("parent_id", $parent_id);            
      $this->db->where("company_id", $company_id);            
      $query = $this->db->get();
      
      $list_result = $query->result_array();
            
      if(count($list_result)>0)
      {
         for($i=0;$i<count($list_result);$i++)
         {
            $row = $list_result[$i];
            $row['level']=$level;
            $list_tree[] = $row;

            $this->generate_list_accounts_tree($list_tree, $company_id, $list_result[$i]['id_account_category'], $level+1);
         }
      }
   }
   ###############################################################
   function delete_account_item($id_account_item)
   {
      $this->db->delete('account_items', array("id_account_item"=>$id_account_item)); 
   }
   ###############################################################
   function add_account_item($data)
   {
      $this->db->insert('account_items', $data); 
   }
   ###############################################################
   function add_account($data)
   {
      $this->db->insert('account_categories', $data); 
   }
   function get_list_account_items_limited($company_id, $limit=10)
   {        
      $this->db->select("account_items.*,account_categories.name AS account");        
      $this->db->from("account_items");
      $this->db->join("account_categories","account_categories.id_account_category = account_items.account_category_id");  
      
      $this->db->where("account_categories.company_id",$company_id);
      $this->db->where("account_items.company_id",$company_id);
            
      
      $this->db->order_by("account_items.register_date DESC, account_items.id_account_item DESC") ;
      
      if(isset($limit))
      {
         $this->db->limit($limit) ;
      }
      
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result_array();
   }
   ###############################################################
   function get_list_account_items($company_id, $date_time_from=null, $date_time_to=null, $account_category_id=null)
   {        
      $this->db->select("account_items.*,account_categories.name AS account");        
      $this->db->from("account_items");
      $this->db->join("account_categories","account_categories.id_account_category = account_items.account_category_id");  
      
      $this->db->where("account_categories.company_id",$company_id);
      $this->db->where("account_items.company_id",$company_id);
      
      if(isset($date_time_from) AND isset($date_time_to))
      {
         $this->db->where("CAST(account_items.register_date AS DATETIME)>= CAST('".$date_time_from."' AS DATETIME) ", null);
         $this->db->where("CAST(account_items.register_date AS DATETIME)<= CAST('".$date_time_to."' AS DATETIME) ", null);
      }
      if(isset($account_category_id) AND strcasecmp( $account_category_id,"")!=0)
      {
         $this->db->where("account_items.account_category_id",$account_category_id);
      }
      $this->db->order_by("account_items.register_date ASC") ;
      $query = $this->db->get();      
      
      return $query->result_array();
   }
   ###############################################################
   function get_list_accounts($company_id)
   {
      $this->db->select("*");        
      $this->db->from("account_categories");     
      $this->db->order_by("id_account_category,parent_id");     
      $this->db->where("account_categories.company_id",$company_id);
            
      $query = $this->db->get();
      
      return $query->result_array();
   }
   ###############################################################
}