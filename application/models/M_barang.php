<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_barang extends CI_Model{
    
    
    
    /**
	* Constructor - Sets up the object properties.
	*/
    public function __construct()
    {
        parent::__construct();
    }

    function get_kode(){
        $query = $this->db->query("SELECT MAX(RIGHT(kode_barang,4)) AS kd_max FROM tbl_barang WHERE DATE(tanggal)=CURDATE()");
        $kd = "";
        if($query->num_rows()>0){
            foreach($query->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }

        return 'BRG'.$kd;
    }

    public function save($table, $data){
        $this->db->trans_start(); # Starting Transaction
        $this->db->insert($table, $data);
        
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function edit($table, $data, $id){
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    
        $this->db->trans_complete(); # Completing transaction

        /*Optional*/
        
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            return TRUE;
        }
    }
    
    function delete($table ,$id){
        $this->db->where('id', $id);
        $this->db->delete($table);
    
        $this->db->trans_complete(); # Completing transaction

        /*Optional*/
        
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function get()
    {

        return $this->db->get('tbl_barang');
    }

    var $column_order = array(null, 'nama_barang','kode_barang', 'jumlah_barang' ,'tanggal',null,null,null,null); //set column field database for datatable orderable
    var $column_search = array('nama_barang','kode_barang', 'jumlah_barang' ,'tanggal'); //set column field database for datatable searchable 
    var $order = array('id' => 'asc');
	
	private function _get_datatables_query(){
         
        $this->db->select('*');//, b.kode_soal');
		$this->db->from('tbl_barang'); 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables(){
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
    }
 
    function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all(){
		$this->db->select('*');
        $this->db->from('tbl_barang');		
        return $this->db->count_all_results();
    }




}