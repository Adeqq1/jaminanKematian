<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePengajuanKlaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'peserta';
    }

    public function rules(): array
    {
        $banks = collect(config('banks'))->flatten()->all();

        return [
            'nama_ahli_waris' => ['required', 'string', 'max:255'],
            'no_hp_peserta' => ['required', 'string', 'max:30'],
            'no_hp_ahli_waris' => ['required', 'string', 'max:30'],
            'nik_peserta' => ['required', 'digits:16'],
            'akta_kematian' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'kartu_bpjs' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'keterangan_ahli_waris' => ['required', 'string', 'max:5000'],
            'nomor_rekening_ahli_waris' => ['required', 'string', 'max:50'],
            'bank_choice' => ['required', 'string', Rule::in([...$banks, '__other__'])],
            'nama_bank_lainnya' => ['nullable', 'string', 'max:100', 'required_if:bank_choice,__other__'],
            'buku_rekening' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
