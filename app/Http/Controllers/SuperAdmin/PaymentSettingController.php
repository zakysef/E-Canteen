<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $settings = PaymentSetting::orderBy('sort_order')->orderBy('id')->get();
        return view('superadmin.payment-settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'            => ['required', 'string', 'max:50', 'unique:payment_settings,key', 'regex:/^[a-z0-9_]+$/'],
            'type'           => ['required', 'in:bank,ewallet'],
            'label'          => ['required', 'string', 'max:100'],
            'account_name'   => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'qr_code'        => ['nullable', 'image', 'max:2048'],
            'is_active'      => ['boolean'],
            'sort_order'     => ['integer', 'min:0'],
        ]);

        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('payment-qr', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        PaymentSetting::create($data);

        return redirect()->route('superadmin.payment-settings.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentSetting $paymentSetting)
    {
        $data = $request->validate([
            'type'           => ['required', 'in:bank,ewallet'],
            'label'          => ['required', 'string', 'max:100'],
            'account_name'   => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'qr_code'        => ['nullable', 'image', 'max:2048'],
            'is_active'      => ['boolean'],
            'sort_order'     => ['integer', 'min:0'],
        ]);

        if ($request->hasFile('qr_code')) {
            // Delete old file if exists
            if ($paymentSetting->qr_code) {
                Storage::disk('public')->delete($paymentSetting->qr_code);
            }
            $data['qr_code'] = $request->file('qr_code')->store('payment-qr', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        $paymentSetting->update($data);

        return redirect()->route('superadmin.payment-settings.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentSetting $paymentSetting)
    {
        if ($paymentSetting->qr_code) {
            Storage::disk('public')->delete($paymentSetting->qr_code);
        }

        $paymentSetting->delete();

        return redirect()->route('superadmin.payment-settings.index')
            ->with('success', 'Metode pembayaran dihapus.');
    }

    public function removeQr(PaymentSetting $paymentSetting)
    {
        if ($paymentSetting->qr_code) {
            Storage::disk('public')->delete($paymentSetting->qr_code);
            $paymentSetting->update(['qr_code' => null]);
        }

        return redirect()->route('superadmin.payment-settings.index')
            ->with('success', 'QR Code berhasil dihapus.');
    }
}
