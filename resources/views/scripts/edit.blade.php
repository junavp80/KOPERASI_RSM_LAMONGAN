<script>
    $('#metode_pembayaran').on('click', function(e) {
        const metode_pembayaran = e.target.value;
        let id_pelanggan = $('#id_pelanggan').val();

        if (!id_pelanggan) {
            $('#option_kredit').attr('hidden', true);
            $('#btn_save').attr('hidden', false);
        } else {
            $('#option_kredit').attr('hidden', false);
            $('#btn_save').attr('hidden', false);
        }
    })

    $('#id_pelanggan').on('change', function(e) {
        var id_pelanggan = e.target.value;
        $.ajax({
            url: `/penjualan/get-id-anggota/${id_pelanggan}`,
            method: 'GET',
            success: function(data) {
                $('#id_anggota').val(data.id);
                $('#nama_anggota').val(data.name);
                $('#poin').val(data.poin);
                $('#credit').val(data.credit);
            },
            error: function() {

                $('#id_anggota').val('');
                $('#nama_anggota').val('');
                $('#poin').val('');
                $('#credit').val('');
            }
        })

    });

    $('#tukar_poin').on('click', function() {
        $('#jumlah_poin').val($('#poin').val())
        // alert($('#jumlah_poin').val())
    })

    $('#hitung_sub_total').on('click', function(e) {
        e.stopPropagation();

        var harga_total = [];
        var id_anggota = $('#id_pelanggan').val();
        var poin = $('#jumlah_poin').val() * 3000;
        var metode_pembayaran = $('#metode_pembayaran').val();


        $('#table_kasir tbody tr').each(function() {
            var harga_akhir = $(this).find('td:eq(6)')[0]['innerText'];
            harga_total.push({
                harga_akhir: harga_akhir
            });
        });
        let sum = 0;

        harga_total.forEach(value => {
            sum += parseInt(value['harga_akhir']);
        });

        if (metode_pembayaran) {
            if (metode_pembayaran == 'Pilih Metode Pembayaran') {
                $('#sub_total').val(sum - poin);
            } else if (metode_pembayaran == 'kredit') {
                $('#sub_total').val((sum - poin) + ((sum - poin) * 0.05));
            } else {
                $('#sub_total').val(sum - poin);
            }
        } else {
            $('#sub_total').val(sum - poin);
        }

        if (id_anggota) {
            $('#diskon').val(10);
            $('#hasil_diskon').val(sum * 0.1);

            if ($('#sub_total').val() >= 100000) {
                $('#tambahan_poin').val(1);
            }
            $('#nominal_bayar').val($('#sub_total').val() - $('#hasil_diskon').val())
        } else {
            $('#diskon').val('');
            $('#hasil_diskon').val('');
            $('#nominal_bayar').val(sum - poin)
        }


        // console.log(harga_total, `sum: ${sum}`);
        $('#uang_bayar').attr('readonly', false);


    })


    $('#uang_bayar').on('change', function() {

        var uang_bayar = $('#uang_bayar').val();
        var nominal_bayar = $('#nominal_bayar').val();
        // console.log(`UANG BAYAR: ${uang_bayar} NOMINAL BAYAR ${nominal_bayar}`)

        if (uang_bayar < nominal_bayar) {
            alert('Uang kurang ' + (nominal_bayar - uang_bayar))
            $('#kembalian').val('');
        } else {
            $('#kembalian').val(uang_bayar - nominal_bayar);
        }
    })

    $('#btn_save').on('click', function() {
        let data = [];
        let data_detail = []

        $('#table_kasir tbody tr').each(function() {
            const id_barang = $(this).find('td:eq(1)')[0]['innerText'];
            const kategori = $(this).find('td:eq(2)')[0]['innerText'];
            const nama_barang = $(this).find('td:eq(3)')[0]['innerText'];
            const jumlah_barang = $(this).find('td:eq(4)')[0]['innerText'];
            const harga_jual = $(this).find('td:eq(5)')[0]['innerText'];
            const harga_akhir = $(this).find('td:eq(6)')[0]['innerText'];
            // console.log(id_barang, kategori, nama_barang, jumlah_barang, harga_jual, harga_akhir)
            data_detail.push({
                id_barang,
                kategori,
                nama_barang,
                jumlah_barang,
                harga_jual,
                harga_akhir
            });

            const {
                id_pelanggan,
                tanggal,
                id_anggota,
                nama_anggota,
                poin,
                tukar_poin,
                credit,
                jumlah_poin,
                sub_total,
                diskon,
                hasil_diskon,
                nominal_bayar,
                uang_bayar,
                kembalian,
                metode_pembayaran,
                tambahan_poin
            } = $('#form_kasir').serializeArray().reduce((obj, item) => {
                obj[item.name] = item.value;
                return obj;
            }, {});

            data.push({
                id_pelanggan,
                tanggal,
                id_anggota,
                nama_anggota,
                poin,
                tukar_poin,
                credit,
                jumlah_poin,
                sub_total,
                diskon,
                hasil_diskon,
                nominal_bayar,
                uang_bayar,
                kembalian,
                metode_pembayaran,
                tambahan_poin
            });
        });

        // console.log(data);

        $.ajax({
            url: '/penjualan',
            type: 'POST',
            dataType: "json",
            data: {
                data: data,
                data_detail: data_detail,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(error, xhr, status);
            }
        })

        location.reload();
    })

    function tambahBarisEdit() {
        var stok = $('#stok_edit').val();
        var harga_jual = $('#harga_jual_edit').val();
        var jumlah_barang = $('#jumlah_barang_edit').val();
        var harga_akhir = $('#harga_akhir_edit').val();
        var id_barang = $('#id_barang_edit').val();
        var for_id_product = $('#edit_id_product').val();
        // console.log(stok, harga_jual, harga_akhir, jumlah_barang, id_barang);
        let data_bs_value = [];
        $('#table_kasir_edit tbody tr').each(function() {
            const data_bs = $(this).find('td:eq(0)')[0]['firstElementChild']['attributes'][4]['nodeValue'];

            // console.log(data_bs);
            data_bs_value.push({
                data_bs: data_bs
            });

        });
        // console.log(data_bs_value.length);
        let data_bs_value_fix = data_bs_value.length + 1;
        // console.log(data_bs_value_fix)
        if (harga_akhir) {
            $.ajax({
                url: `/penjualan/get-id-product/${id_barang}`,
                method: 'GET',
                success: function(data) {
                    // var lastDataId = $('table_kasir_edit tbody tr:last-child')
                    // console.log(lastDataId)
                    var buttons = document.querySelectorAll("a.btn");

                    // Tambahkan event listener ke setiap elemen <a>
                    buttons.forEach(function(button) {
                        button.addEventListener("click", function(event) {
                            var dataId = this.getAttribute("data-id");

                            // Lakukan operasi apa pun dengan data-id yang diperoleh
                            // console.log(dataId);
                        });
                    });

                    var newRow = `
                            <tr>
                                <td class="text-bold-500">
                                    <a href="#" class="btn btn-outline-warning"
                                        name="edit_row" onclick="editRowEdit(this)"
                                        data-id="${data_bs_value_fix}"
                                        data-value="${for_id_product}"
                                        id="edit_id_product">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-danger"
                                        name="delete_row"
                                        onclick="deleteRowEdit(this)">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                                <td class="text-bold-500">
                                    ${id_barang}
                                </td>
                                <td class="text-bold-500">
                                    ${data.kategori}
                                </td>
                                <td class="text-bold-500">
                                    ${data.nama}
                                </td>
                                <td class="text-bold-500">
                                    ${jumlah_barang}
                                </td>
                                <td class="text-bold-500">
                                    ${data.harga}
                                </td>
                                <td class="text-bold-500">
                                    ${harga_akhir}
                                </td>
                            </tr>
                        `;
                    $('#table_kasir_edit tbody').append(newRow);
                }
            })
        }
    }


    function deleteRowEdit(btn) {
        var row = btn.closest('tr');
        row.remove();
    }

    function editRowEdit(btn) {
        var row = btn.getAttribute('data-id');
        var for_id_barang = btn.getAttribute('data-value');
        console.log(for_id_barang);
        // console.log(row);
        let data = [];
        let stok = [];

        $('#table_kasir_edit tbody tr').each(function() {
            let id_barang = $(this).find('td:eq(1)')[0]['innerText'];
            let kategori = $(this).find('td:eq(2)')[0]['innerText'];
            let nama_barang = $(this).find('td:eq(3)')[0]['innerText'];
            let jumlah_barang = $(this).find('td:eq(4)')[0]['innerText'];
            let harga_jual = $(this).find('td:eq(5)')[0]['innerText'];
            let harga_akhir = $(this).find('td:eq(6)')[0]['innerText'];
            // console.log('iki: ' + $(this).find('td:eq(4)')[0]['innerText'])
            // console.log(id_barang, kategori, nama_barang, jumlah_barang, harga_jual, harga_akhir)
            if (id_barang == for_id_barang) {
                data.push({
                    id_barang,
                    kategori,
                    nama_barang,
                    jumlah_barang,
                    harga_jual,
                    harga_akhir
                });
                stok.push({
                    jumlah_barang
                })
            }
        });
        let total_array = data.length;
        // console.log(data, `IKI: ${data.length == 0 ? 0 : data.length - 1}`);
        let data_final = [];
        let sum_stok = 0;
        stok.forEach(value => {
            sum_stok += parseInt(value['jumlah_barang']);
        })
        console.log(sum_stok);


        // console.log(data);



        // var stok = $('#stok_edit').val(sum_stok - data[0]['jumlah_barang']);
        // var harga_jual = $('#harga_jual_edit').val(data[0]['harga_jual']);
        // var jumlah_barang = $('#jumlah_barang_edit').val(data[0]['jumlah_barang']);
        // var harga_akhir = $('#harga_akhir_edit').val(data[0]['harga_akhir']);
        // var id_barang = $('#id_barang_edit').val(data[0]['id_barang']);
        $.ajax({
            url: `penjualan/get-id-product/${for_id_barang}`,
            method: 'GET',
            success: function(get_data) {
                data.forEach(value => {
                    // console.log(value['id_barang'], for_id_barang);
                    // sum_stok += parseInt(value['jumlah_barang']);
                    if (value['id_barang'] == for_id_barang) {
                        let sum_stok = 0;
                        stok.forEach(value => {
                            sum_stok += parseInt(value['jumlah_barang']);
                        })
                        $('#edit_id_product').val(value['id_barang']);
                        // console.log(data, value['jumlah_barang'])
                        $('#stok_edit').val(get_data.stok - sum_stok);
                        $('#harga_jual_edit').val(value['harga_jual']);
                        $('#jumlah_barang_edit').val(value['jumlah_barang']);
                        $('#harga_akhir_edit').val(value['harga_akhir']);
                        $('#id_barang_edit').val(value['id_barang']);
                    }
                });
            }
        })
        // console.log(data);

    }


    $('#id_barang').on('change', function(e) {
        var id_barang = e.target.value;

        $.ajax({
            url: `penjualan/get-id-product/${id_barang}`,
            method: 'GET',
            success: function(data) {
                var checkDataDouble = [];
                if ($('#table_kasir tbody tr').val() != null) {
                    $('#table_kasir tbody tr').each(function() {
                        var jumlah_barang = $(this).find('td:eq(4)')[0][
                            'innerText'
                        ];
                        var id_barang = $(this).find('td:eq(1)')[0][
                            'innerText'
                        ];
                        checkDataDouble.push({
                            jumlah_barang: jumlah_barang,
                            id_barang: id_barang
                        });
                    });
                    // console.log(checkDataDouble);
                    let sum = 0;
                    let data_id_barang = []
                    checkDataDouble.forEach(value => {
                        // console.log(`VALUE: ${value['jumlah_barang']}`)
                        if (parseInt(value['id_barang']) == id_barang) {
                            sum += parseInt(value['jumlah_barang']);
                        }
                        data_id_barang.push({
                            id_barang: value['id_barang'],
                            jumlah_barang: sum
                        });
                    });
                    // console.log(sum)
                    var stok_awal = data.stok - sum;
                    $('#stok').val(stok_awal);
                } else {
                    var stok_awal = data.stok;
                    $('#stok').val(stok_awal);
                }

                $('#harga_jual').val(data.harga);

                $('#jumlah_barang').on('change', function(e) {
                    var checkDataDouble = [];
                    if ($('#table_kasir tbody tr').val() != null) {
                        $('#table_kasir tbody tr').each(function() {
                            var jumlah_barang = $(this).find('td:eq(4)')[0][
                                'innerText'
                            ];
                            var id_barang = $(this).find('td:eq(1)')[0][
                                'innerText'
                            ];
                            checkDataDouble.push({
                                jumlah_barang: jumlah_barang,
                                id_barang: id_barang
                            });
                        });
                        console.log(checkDataDouble);
                        let sum = 0;
                        let data_id_barang = []
                        checkDataDouble.forEach(value => {
                            console.log(`VALUE: ${value['jumlah_barang']}`)
                            if (parseInt(value['id_barang']) == id_barang) {
                                sum += parseInt(value['jumlah_barang']);
                            }
                            data_id_barang.push({
                                id_barang: value['id_barang'],
                                jumlah_barang: sum
                            });
                        });
                        // console.log(sum)
                        var stok_awal = data.stok - sum;
                        var jumlah_barang = e.target.value;
                        var stok_final = stok_awal - jumlah_barang;
                        var harga_akhir = data.harga * jumlah_barang;
                        $('#stok').val(stok_final);
                        $('#harga_akhir').val(harga_akhir);
                    } else {
                        var stok_awal = data.stok;
                        var jumlah_barang = e.target.value;
                        var stok_final = stok_awal - jumlah_barang;
                        var harga_akhir = data.harga * jumlah_barang;
                        $('#stok').val(stok_final);
                        $('#harga_akhir').val(harga_akhir);
                    }
                })
            }
        })
    })
</script>