<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <title>Data Barang!</title>
  </head>
  <body>
    <div class="col-md-12 mt-2">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#myModal">Tambah Data</button>
        <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data Barang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="form_tambah_barang">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama Barang</label>
                                        <input type="text" id="name" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>kode barang</label>
                                        <input type="text" id="kode" name="kode" class="form-control" value="<?= $kode ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>banyak</label>
                                        <input type="number" id="banyak" min="0" name="banyak" class="form-control">
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-save" class="btn btn-primary">Save</button>
                    </div>
                    </form>

                    </div>
                </div>
            </div>
    </div>
    <div class="container mt-4">
        <table class="display table-striped" id="tbl_barang" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Banyak</th>
                        <th>Tanggal Buat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
        </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function(){
            var myTable


            $('#btn-save').on('click', function(){
                form = $('#form_tambah_barang').serialize()
            
                $.ajax({
                    url: "index.php/Welcome/save",
                    type: "POST",
                    dataType: "JSON",
                    data: form,
                    error : function(){
                      alert('data tidak lengkap');
                    },
                    success: function(response){
                        if(response =="sukses"){
                            alert('sukses');
                            setTimeout(function(){ 
                              location.reload()
                            }, 500)
                        }else{
                            alert('gagal simpan');
                                myTable.ajax.reload()
                        }
                    }
                })
            })
            
            $(document).on('click', '.btn-edit-aksi',function(){
                id = this.value
                form = $('#form_edit_barang'+id).serialize()

                $.ajax({
                    url: "index.php/welcome/edit",
                    type: "POST",
                    dataType: "JSON",
                    data: form,
                    error : function(){
                      alert('data tidak lengkap');
                    },
                    success: function(response){
                        if(response =="sukses"){
                            alert('sukses');
                            setTimeout(function(){ 
                                $('#Modaledit'+id).modal('hide')
                                myTable.ajax.reload()
                              }, 500)
                            }else{
                            alert('gagal simpan');
                            setTimeout(function(){ 
                              $('#Modaledit'+id).modal('hide')
                                myTable.ajax.reload()
                            }, 500)
                        }
                    }
                })
            })
            
            $(document).on('click', '.btn-hapus-aksi',function(){
                id = this.value

                $.ajax({
                    url: "index.php/welcome/hapus",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id:id
                    },
                    success: function(response){
                        if(response =="sukses"){
                            alert('sukses');
                            setTimeout(function(){ 
                                $('#Modalhapus'+id).modal('hide')
                                myTable.ajax.reload()
                            }, 500)
                        }else{
                            alert('gagal simpan');
                            setTimeout(function(){ 
                                myTable.ajax.reload()
                            }, 500)
                        }
                    }
                })
            })


            myTable = $('#tbl_barang').DataTable({
                    "responsive": true,
                    "scrollX"	: true,
                    "processing": true, //Feature control the processing indicator.
                    "serverSide": true, //Feature control DataTables' server-side processing mode.
                    "order": [], //Initial no order.
            
                    // Load data for the table's content from an Ajax source
                    "ajax": {
                        "url": "index.php/Welcome/data_barang",
                        "type": "POST",
                        "data" : function(d)
                            {
                            }
                    },
            
                    //Set column definition initialisation properties.
                    "columnDefs": [
                    { 
                        "targets": [ 0 ], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                    ],
                });	

        })

    </script>

  </body>
</html>