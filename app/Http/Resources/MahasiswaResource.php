<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, // ← wajib untuk update/delete
            'nim' => $this->nim,
            'nama' => $this->nama,
            'jk' => $this->jk,
            'tgl_lahir' => $this->tgl_lahir,
            'jurusan' => $this->jurusan,
            'alamat' => $this->alamat,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Data Mahasiswa retrieved successfully',
        ];
    }
}
