<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data['kode'] = $this->m_barang->get_kode();
		$this->load->view('v_barang', $data);
	}


	public function save(){
		$name = $this->input->post('name');
		$kode = $this->input->post('kode');
		$banyak = $this->input->post('banyak');

		if($name =="" || $kode == "" || $banyak == ""){
                    return 'eror';
		}

		$data = array(
			'nama_barang' => $name,
			'kode_barang' => $kode,
			'jumlah_barang' => $banyak,
			'tanggal' => date('Y-m-d H:i:s')
		);

		$save = $this->m_barang->save('tbl_barang', $data);
	
		$output ="";
		
		if($save == TRUE){
			$output .="sukses";
		}else{
			$output .="gagal";
		}

		echo json_encode($output);
	}


	public function edit(){
		$id = $this->input->post('edit_id');
		$name = $this->input->post('edit_name');
		$kode = $this->input->post('edit_kode');
		$banyak = $this->input->post('edit_banyak');

		if($name =="" || $kode == "" || $banyak == ""){
			return 'eror';
		}

		$data = array(
			'nama_barang' => $name,
			'kode_barang' => $kode,
			'jumlah_barang' => $banyak,
			'tanggal' => date('Y-m-d H:i:s')
		);

		$edit = $this->m_barang->edit('tbl_barang', $data, $id);
	
		$output ="";
		
		if($edit == TRUE){
			$output .="sukses";
		}else{
			$output .="gagal";
		}

		echo json_encode($output);
	}



	public function hapus(){
		$id = $this->input->post('id');

		$delete = $this->m_barang->delete('tbl_barang', $id);
	
		$output ="";
		
		if($delete == TRUE){
			$output .="sukses";
		}else{
			$output .="gagal";
		}

		echo json_encode($output);
	}


	public function data_barang(){
		
		$list = $this->m_barang->get_datatables();

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $lst) {
			
			$no++;
            $row = array();
			$row[] = $no;//1		
            $row[] = $lst->kode_barang; //3
			$row[] = $lst->nama_barang; //2
			$row[] = $lst->jumlah_barang; //2
			$row[] = $lst->tanggal; //4

			$aksi = "<button class='btn btn-primary btn-sm btn-edit' value='$lst->id' data-toggle='modal' data-target='#Modaledit".$lst->id."'>Edit</button>
					<button class='btn btn-danger btn-sm btn-hapus' value='$lst->id' data-toggle='modal' data-target='#Modalhapus".$lst->id."'>Delete</button>
					
					<div class='modal' id='Modaledit".$lst->id."'>
						<div class='modal-dialog'>
							<div class='modal-content'>

							<!-- Modal Header -->
							<div class='modal-header'>
								<h4 class='modal-title'>Edit Barang</h4>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
							</div>

							<!-- Modal body -->
							<div class='modal-body'>
								<form id='form_edit_barang".$lst->id."'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='form-group'>
												<label>Nama Barang</label>
												<input type='text' name='edit_name' id='edit_name".$lst->id."' value='$lst->nama_barang' class='form-control'>
												<input type='hidden' name='edit_id' id='edit_id".$lst->id."' value='$lst->id' class='form-control'>
											</div>
										</div>
										<div class='col-md-12'>
											<div class='form-group'>
												<label>Nama Barang</label>
												<input type='text' name='edit_kode' id='edit_kode".$lst->id."' value='$lst->kode_barang' class='form-control' readonly>
											</div>
										</div>
										<div class='col-md-12'>
											<div class='form-group'>
												<label> Deskripsi</label>
												<input type='number' name='edit_banyak' id='edit_banyak".$lst->id."' value='$lst->jumlah_barang' class='form-control'>
											</div>
										</div>
									</div>
							</div>

							<!-- Modal footer -->
							<div class='modal-footer'>
								<button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
								<button type='button' value='$lst->id' class='btn btn-primary btn-edit-aksi'>edit</button>
							</div>
							</form>

							</div>
						</div>
					</div>

					<!-- Modal Hapus -->
					<div class='modal' id='Modalhapus".$lst->id."'>
						<div class='modal-dialog'>
							<div class='modal-content'>

							<!-- Modal Header -->
							<div class='modal-header'>
								<h4 class='modal-title'>Hapus Barang</h4>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
							</div>

							<!-- Modal body -->
							<div class='modal-body'>
								<form id='form_edit_barang".$lst->id."'>
									<div class='row'>
										<div class='col-md-12'>
											Yakin Ingin Menghapus Data Ini $lst->kode_barang ?
										</div>
									</div>
							</div>

							<!-- Modal footer -->
							<div class='modal-footer'>
								<button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
								<button type='button' value='$lst->id' class='btn btn-primary btn-hapus-aksi'>Hapus</button>
							</div>
							</form>

							</div>
						</div>
					</div>
					"; 
			$row[] = $aksi; //4

			$data[] = $row;
		}


		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_barang->count_all(),
			"recordsFiltered" => $this->m_barang->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
}
