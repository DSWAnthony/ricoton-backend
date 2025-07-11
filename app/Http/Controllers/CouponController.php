<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{
    // Obtener todos los cupones
    public function index()
    {
        $coupons = Coupon::select()->with('products')->get();

        return response()->json($coupons);
    }

    // Generar código de cupón único
    private function generateUniqueCouponCode($length = 9)
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            $code = Str::upper(Str::random($length));
            
            // Verificar si ya existe en la base de datos
            $exists = Coupon::where('code', $code)->exists();
            
            if (!$exists) {
                return $code;
            }
            
            $attempt++;
        } while ($attempt < $maxAttempts);
        
        return Str::upper(Str::random($length - 4)) . now()->format('is');
    }

    // Crear un nuevo cupón
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:9',
            'description' => 'nullable|string',
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Generar código único si no se proporcionó
        $validatedData = $validator->validated();

        if (!isset($validatedData['code'])) {
            $validatedData['code'] = $this->generateUniqueCouponCode();
        } else {
            
            $codeValidator = Validator::make(['code' => $validatedData['code']], [
                'code' => [
                    'required', 
                    'max:9', 
                    'unique:coupons', 
                    'regex:/^[A-Z0-9]+$/'
                ]
            ]);
            
            if ($codeValidator->fails()) {
                return response()->json([
                    'message' => 'Código inválido',
                    'errors' => $codeValidator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $coupon = Coupon::create($validatedData);

        if (!empty($request->product_ids)) {
            $coupon->products()->attach($request->product_ids);
        }

        return response()->json([
            'message' => 'Coupon created successfully',
            'data' => $coupon->load('products')
        ], Response::HTTP_CREATED);
    }

    // Obtener un cupón específico
    public function show(Coupon $coupon)
    {
        return response()->json($coupon->load('products'));
    }

    // Actualizar un cupón
    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required', 
                'max:9', 
                Rule::unique('coupons')->ignore($coupon->id),
                'regex:/^[A-Z0-9]+$/'
            ],
            'description' => 'nullable|string',
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupon->update($validator->validated());

        // Sincronizar productos
        $productIds = $request->product_ids ?? [];
        $coupon->products()->sync($productIds);

        return response()->json([
            'message' => 'Coupon updated successfully',
            'data' => $coupon->load('products')
        ]);
    }

    // Eliminar un cupón
    public function destroy(Coupon $coupon)
    {
        $coupon->products()->detach();
        $coupon->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    // Aplicar cupón a un producto
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:coupons,code',
            'product_id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupon = Coupon::where('code', $request->code)->first();
        $product = Product::find($request->product_id);

        if (!$coupon->isValidForProduct($product)) {
            return response()->json([
                'message' => 'Coupon is not valid for this product',
                'errors' => ['code' => 'invalid_coupon']
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $discountedPrice = $coupon->calculateDiscount($product->price);
        $coupon->increment('used_count');

        return response()->json([
            'message' => 'Coupon applied successfully',
            'data' => [
                'original_price' => $product->price,
                'discounted_price' => $discountedPrice,
                'coupon_id' => $coupon->id,
                'product_id' => $product->id,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value
            ]
        ]);
    }
}