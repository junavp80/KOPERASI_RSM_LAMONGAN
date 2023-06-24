<script>
    // $('#harga_beli_edit').on('change', function() {
    //     alert('1212');
    // })
    function changeKoma(string) {
        // Menggunakan metode replace() dengan ekspresi reguler untuk mengganti koma dengan titik
        var hasil = string.replace(/,/g, '.');

        return hasil;
    }

    function tambahBarisEdit() {
        let nama_barang = $('#nama_barang_edit').val();
        let jumlah_barang = $('#jumlah_barang_edit').val();
        let kategori = $('#kategori_edit').val();
        let keterangan = $('#keterangan_edit').val();
        let harga_beli = $('#harga_beli_edit').val();
        let harga_jual = $('#harga_jual_edit').val();
        let total_harga = $('#total_harga_edit').val();
        // console.log(nama_barang, jumlah_barang, kategori, keterangan, harga_beli, harga_jual, total_harga)

        const newRow = `
                <tr>
                    <td class="text-bold-500">
                        <a href="#" class="btn btn-outline-warning" name="edit_row"
                            onclick="editRowEdit(this)" data-id=""
                            data-value="${nama_barang}" id="edit_id_product">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger" name="delete_row"
                            onclick="deleteRowEdit(this)">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                    <td class="text-bold-500">
                        ${nama_barang}
                    </td>
                    <td class="text-bold-500">
                        ${kategori}
                    </td>
                    <td class="text-bold-500">
                        ${harga_beli}
                    </td>
                    <td class="text-bold-500">
                        ${harga_jual}
                    </td>
                    <td class="text-bold-500">
                        ${jumlah_barang}
                    </td>
                    <td class="text-bold-500">
                        ${total_harga}
                    </td>
                </tr>
        `;

        $('#table_edit tbody tr').each(function() {
            const nama_barang_table = $(this).find('td:eq(1)')[0]['innerText'];
            const ini = $(this)[0];

            if (nama_barang == nama_barang_table) {
                ini.remove();
                $('#table_edit tbody').append(newRow);

            }
        });
    }

    function editRowEdit(btn) {
        var row = btn.getAttribute('data-id');
        var for_nama_barang = btn.getAttribute('data-value');
        // console.log(for_id_barang);
        // console.log(row);
        let data = [];


        $('#table_edit tbody tr').each(function() {
            const nama_barang = $(this).find('td:eq(1)')[0]['innerText'];
            const kategori = $(this).find('td:eq(2)')[0]['innerText'];
            const harga_beli = $(this).find('td:eq(3)')[0]['innerText'];
            const harga_jual = $(this).find('td:eq(4)')[0]['innerText'];
            const jumlah_barang = $(this).find('td:eq(5)')[0]['innerText'];
            const total_harga = $(this).find('td:eq(6)')[0]['innerText'];
            // console.log('iki: ' + $(this).find('td:eq(4)')[0]['innerText'])
            // console.log(id_barang, kategori, nama_barang, jumlah_barang, harga_jual, harga_akhir)
            if (nama_barang == for_nama_barang) {
                data.push({
                    nama_barang,
                    kategori,
                    jumlah_barang,
                    harga_beli,
                    harga_jual,
                    total_harga,
                });
            }
        });

        // console.log(data, `IKI: ${data.length == 0 ? 0 : data.length - 1}`);
        data.forEach(value => {
            // console.log(value['harga_beli'])
            let nama_barang = $('#nama_barang_edit').val(value['nama_barang']);
            let jumlah_barang = $('#jumlah_barang_edit').val(value['jumlah_barang']);
            let kategori = $('#kategori_edit').val(value['kategori']);
            // let keterangan = $('#keterangan_edit').val(value['keterangan']);
            let harga_beli = $('#harga_beli_edit').val(value['harga_beli']);
            let harga_jual = $('#harga_jual_edit').val(value['harga_jual']);
            let total_harga = $('#total_harga_edit').val(value['total_harga']);
        })
    }

    $('#btn_save_edit').on('click', function() {
        let data = [];

        $('#table_edit tbody tr').each(function() {
            const nama = $(this).find('td:eq(1)')[0]['innerText'];
            const kategori = $(this).find('td:eq(2)')[0]['innerText'];
            const harga_beli = $(this).find('td:eq(3)')[0]['innerText'];
            const harga_jual = $(this).find('td:eq(4)')[0]['innerText'];
            const jumlah_barang = $(this).find('td:eq(5)')[0]['innerText'];
            const total_harga = $(this).find('td:eq(6)')[0]['innerText'];
            const keterangan = $('#keterangan_edit').val();
            // const id_pembelian = $('#edit_id_product').attr('data-id');
            const id_pembelian = $('#id_pembelian').val();
            // $("input[placeholder]").val($("input[placeholder]").attr("placeholder"));
            let nama_barang = nama.toUpperCase();

            data.push({
                nama_barang,
                jumlah_barang,
                kategori,
                harga_beli,
                harga_jual,
                total_harga,
                keterangan,
                id_pembelian
            });
        });
        // console.log(data);
        $.ajax({
            url: '/pembelian/update',
            type: 'POST',
            dataType: "json",
            data: {
                data: data,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(error, xhr, status);
            }
        })
    })

    function deleteRowEdit(btn) {
        var row = btn.closest('tr');
        row.remove();
    }
</script>
