<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    public function generateBarcode($itemCode)
    {
        $item = Item::where('item_code', $itemCode)->first();
        
        if (!$item) {
            abort(404, 'Item not found');
        }
        
        $generator = new BarcodeGeneratorPNG();
        
        try {
            $barcode = $generator->getBarcode($item->item_code, $generator::TYPE_CODE_128);
            
            return response($barcode)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'inline; filename="barcode-' . $item->item_code . '.png"');
        } catch (\Exception $e) {
            abort(500, 'Error generating barcode');
        }
    }
    
    public function showBarcode($itemCode)
    {
        $item = Item::where('item_code', $itemCode)->first();
        
        if (!$item) {
            abort(404, 'Item not found');
        }
        
        return view('items.barcode', compact('item'));
    }
} 