@extends('layouts.app')

@section('content')
<h3>Data Mahasiswa</h3>
<button class="btn btn-primary mb-3" id="btn-tambah">Tambah Mahasiswa</button>

<table class="table table-bordered" id="table-mahasiswa">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal lahir</th>
            <th>Jurusan</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
</table>

<!-- Modal -->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="ModalAddLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formMahasiswa" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAddLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label for="">NIM</label>
                    <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukan NIM">
                </div>

                <div class="mb-2">
                    <label for="">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama">
                </div>

                <div class="mb-2">
                    <label for="">Jenis Kelamin</label>
                    <select name="jk" id="jk" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                </div>

                <div class="mb-2">
                    <label for="">Jurusan</label>
                    <select name="jurusan" id="jurusan" class="form-control">
                        <option value="">Pilih Jurusan</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen Informatika">Manajemen Informatika</option>
                        <option value="Ilmu Komunikasi">Ilmu Komunikasi</option>
                        <option value="Administrasi Publik">Administrasi Publik</option>
                        <option value="Teknik Mesin">Teknik Mesin</option>
                        <option value="Teknik Elektro">Teknik Elektro</option>
                        <option value="Teknik Sipil">Teknik Sipil</option>
                        <option value="Akuntansi">Akuntansi</option>
                        <option value="Manajemen">Manajemen</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukan Alamat"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-simpan">Save Data</button>
                <button type="button" class="btn btn-primary" id="btn-update">Edit Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var table;
    let selectedId = null;

    $(document).ready(function () {
        table = $('#table-mahasiswa').DataTable({
            ajax: "/api/mahasiswa",
            columns: [
                { data: 'nim' },
                { data: 'nama' },
                { data: 'jk' },
                { data: 'tgl_lahir' },
                { data: 'jurusan' },
                { data: 'alamat' },
                {
                    data: 'id',
                    render: function (id) {
                        return `
                            <button class="btn btn-warning btn-sm btn-edit" data-id="${id}">Edit</button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${id}">Hapus</button>
                        `;
                    }
                }
            ]
        });

        function ambilDataForm() {
            return {
                nim: $('#nim').val(),
                nama: $('#nama').val(),
                jk: $('#jk').val(),
                tgl_lahir: $('#tgl_lahir').val(),
                jurusan: $('#jurusan').val(),
                alamat: $('#alamat').val()
            };
        }

        $('#btn-tambah').click(function () {
            selectedId = null;
            $('#formMahasiswa')[0].reset();
            $('#ModalAddLabel').text('Tambah Mahasiswa');
            $('#nim').prop('readonly', false);
            $('#btn-simpan').show();
            $('#btn-update').hide();
            $('#ModalAdd').modal('show');
        });

        $('#btn-simpan').click(function () {
            var data = ambilDataForm();
            $.ajax({
                url: '/api/mahasiswa',
                type: 'POST',
                data: data,
                success: function (response) {
                    $('#ModalAdd').modal('hide');
                    table.ajax.reload();
                    alert('Data berhasil disimpan');
                },
                error: function (xhr) {
                    alert('Gagal menyimpan: ' + xhr.responseText);
                }
            });
        });

        $('#table-mahasiswa').on('click', '.btn-edit', function () {
            selectedId = $(this).data('id');
            $.ajax({
                url: '/api/mahasiswa/' + selectedId,
                type: 'GET',
                success: function (data) {
                    $('#ModalAddLabel').text('Edit Mahasiswa');
                    $('#formMahasiswa')[0].reset();

                    $('#nim').val(data.nim).prop('readonly', true);
                    $('#nama').val(data.nama);
                    $('#jk').val(data.jk);
                    $('#tgl_lahir').val(data.tgl_lahir);
                    $('#jurusan').val(data.jurusan);
                    $('#alamat').val(data.alamat);

                    $('#btn-simpan').hide();
                    $('#btn-update').show();
                    $('#ModalAdd').modal('show');
                },
                error: function (xhr) {
                    alert('Gagal mengambil data: ' + xhr.responseText);
                }
            });
        });

        $('#btn-update').click(function () {
            var data = ambilDataForm();
            data._method = 'PUT';

            $.ajax({
                url: '/api/mahasiswa/' + selectedId,
                type: 'POST',
                data: data,
                success: function (response) {
                    $('#ModalAdd').modal('hide');
                    table.ajax.reload();
                    alert('Data berhasil diperbarui');
                },
                error: function (xhr) {
                    alert('Gagal memperbarui: ' + xhr.responseText);
                }
            });
        });

        $('#table-mahasiswa').on('click', '.btn-delete', function () {
            let id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '/api/mahasiswa/' + id,
                    type: 'DELETE',
                    success: function (response) {
                        table.ajax.reload();
                        alert('Data berhasil dihapus');
                    },
                    error: function (xhr) {
                        alert('Gagal menghapus: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection
