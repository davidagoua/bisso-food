<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Cuisine;
use App\Models\Submenu;
use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubmenuImport implements ToCollection,ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    public function __construct($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }

    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        if ($row['menu_id'] != null && $row['name'] != null && $row['type'] && $row['item_reset_value'] && $row['price'] && $row['description'])
        {
            $item_reset_value = $row['item_reset_value'] == 'yes' ? 1 : 0;
            $qty_reset = 0;
            if($item_reset_value == 1){
                if($qty_reset == null){
                    $qty_reset = 10;
                }
                else{
                    $qty_reset = $row['qty_reset'];
                }
            }
            $vendor_id = $this->vendor_id;
            return new Submenu([
                'name'     => $row['name'],
                'status'    => 1,
                'image' => 'product_default.jpg',
                'menu_id' => $row['menu_id'],
                'type' => $row['type'],
                'item_reset_value' => $item_reset_value,
                'qty_reset' => $qty_reset,
                'price' => $row['price'],
                'description' => $row['description'],
                'vendor_id' => $vendor_id,
                'is_excel' => 1,
            ]);
        }
    }
}
