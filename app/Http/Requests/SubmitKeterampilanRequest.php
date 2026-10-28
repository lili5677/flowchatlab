<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitKeterampilanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jawaban' => ['nullable', 'array'],
            'jawaban.*.teks' => ['required', 'string'],
            'jawaban.*.simbol' => ['nullable', 'string', 'in:T,P,IO,D,t,p,io,d'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jawaban.array' => 'Data jawaban harus berupa daftar langkah.',
            'jawaban.*.teks.required' => 'Teks langkah tidak boleh kosong.',
            'jawaban.*.simbol.in' => 'Bentuk simbol tidak valid.',
        ];
    }
}
