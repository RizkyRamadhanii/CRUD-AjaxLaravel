
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready( function () {
      $('#myTable').DataTable({
        processing:true,
        serverside:true,
        ajax: "{{url('pegawaiAjax')}}",
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable:false
        }, {
            data: 'nama',
            name: 'Nama'
        }, {
            data: 'email',
            name: 'Email'
        },{
            data: 'aksi',
            name: 'Aksi',
        }]
      });
  });

// Global Setup
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

//   Proses Create
  $('body').on('click', '.tombol-tambah', function(e){
    e.preventDefault();
    $('#exampleModal').modal('show');

    $('.tombol-simpan').click(function() {
        simpan();
    });
  });

//   Proses Edit
  $('body').on('click', '.tombol-edit', function(e) {
    let id = $(this).data('id');

    $.ajax({
        url: 'pegawaiAjax/'+ id +'/edit',
        type: 'GET',
        success: function(response) {
            $('#exampleModal').modal('show');
            $('#nama').val(response.result.nama);
            $('#email').val(response.result.email);
            console.log(response.result);

            $('.tombol-simpan').click(function() {
            simpan(id);
    });
        }
    })
  });


//   fungsi create and update
  function simpan(id = '')
  {
    if (id == '') {
            var var_url = 'pegawaiAjax';
            var var_type = 'POST';
        } else {
            var var_url = 'pegawaiAjax/' + id;
            var var_type = 'PUT';
        }
    $.ajax({
            url: var_url,
            type: var_type,
            data: {
                nama : $('#nama').val(),
                email : $('#email').val()
            },
            success: function (response) {
    if (response.errors) {
        // Menyimpan referensi ke elemen .alert-danger dalam variabel untuk efisiensi
        var $alertDanger = $('.alert-danger');
        $alertDanger.removeClass('d-none');

        // Membuat ul element sekali saja
        var $errorList = $alertDanger.find('ul');
        if ($errorList.length === 0) {
            $errorList = $('<ul>');
            $alertDanger.append($errorList);
        } else {
            $errorList.empty();
        }

        // Menambahkan pesan kesalahan ke dalam ul element
        $.each(response.errors, function (key, value) {
            $errorList.append("<li>" + value + "</li>");
        });
    } else {
        // Menampilkan pesan kesuksesan
        $('.alert-success').removeClass('d-none').html(response.success);
    }

    // Menggunakan DataTable API untuk memuat ulang data tabel
    $('#myTable').DataTable().ajax.reload();
}


        });
  }

//   proses Delete
$('body').on('click', '.tombol-delete', function(e) {
  if(confirm('Yakin mau hapus data ini?') == true)
  {
    let id = $(this).data('id');
    $.ajax({
        url: 'pegawaiAjax/'+ id,
        type: 'DELETE',
        success: function(response) {
            $('#myTable').DataTable().ajax.reload();
            alert('Data berhasil dihapus');
        }
    })
  }
});

  $('#exampleModal').on('hidden.bs.modal', function(){
   $('#nama').val('');
   $('#email').val('');

   $('.alert-danger').addClass('d-none');
   $('.alert-danger').html('')

   $('.alert-success').addClass('d-none');
   $('.alert-success').html('')
  })
</script>
