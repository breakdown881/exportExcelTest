<?php

namespace App\Http\Controllers\Api;

use App\Exports\UsersExport;
use App\Exports\VatAddressExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class ExportAddressController extends Controller
{
    public function ExportAddressByFile()
    {
        $contentFile = file_get_contents(public_path() . '/vat.json');
        $dataList = json_decode($contentFile, true);
        $dataArr = [];
        $VAT = [];
        foreach ($dataList as $data) {
            if (str_contains($data, '/')) {
                $dataExplode = explode('/', $data);
                $dataArr[] = $dataExplode;
            } else {
                $dataArr[] = $data;
            }
        }
        foreach ($dataArr as $data) {
            if (is_array($data)) {
                foreach ($data as $item) {
                    $response = Http::get('https://api.vietqr.io/v2/business/' . $item);
                    $VAT[] = ['VAT' => $item, 'Address' => json_decode($response->body(), true)['data']['name'] ?? 'Not found'];
                    sleep(10);
                }
            } else {
                $response = Http::get('https://api.vietqr.io/v2/business/' . $data);
                $VAT[] = ['VAT' => $data, 'Address' => json_decode($response->body(), true)['data']['name'] ?? 'Not found'];
                sleep(10);
            }
        }
        return Excel::download(new VatAddressExport($VAT), 'vat-addresses.xlsx');
    }

    public function ExportAddressFromDatabase()
    {
        $listEmployers = User::typeEmployer()->get();
        $listAddresses = [];
        foreach ($listEmployers as $employer) {
            foreach ($employer->addresses as $address) {
                $listAddresses[] = ['name' => $employer->username, 'address' => $address->full_address];
            }
        }
        return Excel::download(new UsersExport($listAddresses), 'addresses.xlsx');
    }
}