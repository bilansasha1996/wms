<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchasesProduct extends Model
{
    protected $table = 'purchases_products';
    protected $fillable = [
        'id_purchases',
        'id_product'
    ];
    public $timestamps = false;

    /**
     * @param positions
     * @param $id_purchases
     *
     * @return bool
     */
    public function savePurchases($positions, $id_purchases)
    {
        DB::beginTransaction();
        try {
            foreach ($positions as $position){
                DB::table($this->table)->insert(
                    ['id_purchases' => $id_purchases, 'id_product' => $position['id']]
                );
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
        }
        return false;
    }
}