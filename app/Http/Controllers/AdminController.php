<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Transfer;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'showRegistrationForm',
            'register',
            'receiveSMS'
        ]);
    }

    // 🔹 REGISTRATION
    public function showRegistrationForm() 
    { 
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        $rules = [
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
            'role'=>'required|in:admin,owner,customer',
        ];

        if($request->role==='owner'){
            $rules['cnic']='required|string|max:20';
            $rules['cnic_front']='required|file|mimes:jpg,jpeg,png,pdf';
            $rules['cnic_back']='required|file|mimes:jpg,jpeg,png,pdf';
            $rules['property_document']='required|file|mimes:jpg,jpeg,png,pdf';
        }

        $request->validate($rules);

        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            'agreement_accepted'=>$request->role==='owner'?1:0,
            'is_verified'=>$request->role==='owner'?1:0,
        ];

        if($request->hasFile('profile_photo') && $request->role!=='admin'){
            $data['profile_photo']=$request->file('profile_photo')->store('profile_photos','public');
        }

        if($request->role==='owner'){
            $data['cnic']=$request->cnic;
            $data['cnic_front']=$request->file('cnic_front')->store('owners/cnic','public');
            $data['cnic_back']=$request->file('cnic_back')->store('owners/cnic','public');
            $data['property_document']=$request->file('property_document')->store('owners/documents','public');
        }

        $user = User::create($data);
        auth()->login($user);

        session()->flash('success','Account created successfully!');

        return match($user->role){
            'admin'=>redirect()->route('admin.dashboard'),
            'owner'=>redirect()->route('owner.dashboard'),
            default=>redirect()->route('customer.dashboard'),
        };
    }

    // 🔹 DASHBOARD
    public function dashboard()
    {
        $bookings = Booking::with(['customer','owner','warehouse','payment'])
            ->orderBy('created_at','desc')
            ->get();

        $activeWarehouses = Warehouse::where('status','approved')->get();
        $flaggedWarehouses = Warehouse::where('is_flagged',1)->count();
        $pendingWarehouses = Warehouse::where('status','pending')->get();

        return view('admin.dashboard', compact(
            'bookings','activeWarehouses','pendingWarehouses','flaggedWarehouses'
        ));
    }

    // 🔹 BOOKINGS PAGE (Admin)
    public function bookings(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Booking::with(['customer','owner','warehouse','payment'])->orderBy('created_at','desc');

        if(in_array($status,['pending','active','expired'])){
            $query->where('status',$status);
        }

        $bookings = $query->paginate(25)->withQueryString();

        return view('admin.bookings.index', compact('bookings','status'));
    }

    // 🔹 RELEASE PAYMENT TO OWNER (Stripe)
    public function releasePayment($id)
    {
        $booking = Booking::with('owner')->findOrFail($id);

        if($booking->payment_status !== 'paid'){
            return redirect()->back()->with('error', 'Booking not paid yet.');
        }

        if(!$booking->owner || !$booking->owner->stripe_account_id){
            return redirect()->back()->with('error', 'Owner Stripe account not connected.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            \Stripe\Transfer::create([
                'amount' => $booking->owner_amount * 100, // cents
                'currency' => 'usd',
                'destination' => $booking->owner->stripe_account_id,
            ]);

            $booking->payment_status = 'released';
            $booking->save();

            return redirect()->back()->with('success', 'Payment released to owner successfully!');
        } catch (\Exception $e){
            return redirect()->back()->with('error', 'Stripe transfer failed: ' . $e->getMessage());
        }
    }

    // 🔹 ORDERS PAGE
    public function orders()
    {
        $bookings = Booking::with(['customer','owner','warehouse','payment'])->orderBy('created_at','desc')->get();
        return view('admin.orders.index', compact('bookings'));
    }

    // 🔹 ESCROW SMS PAYMENT VERIFICATION
    public function receiveSMS(Request $request)
    {
        $validated=$request->validate([
            'sender'=>'required|string',
            'message'=>'required|string',
            'received_at'=>'required|date'
        ]);

        $transactionData=$this->parseBankSMS($validated['message']);
        if($transactionData){
            $payment=Payment::where('status','pending')->where('amount',$transactionData['amount'])->first();
            if($payment){
                $payment->update([
                    'transaction_id'=>$transactionData['transaction_id'],
                    'payment_method'=>$transactionData['method'],
                    'sms_content'=>$validated['message'],
                    'status'=>'verified',
                    'payment_date'=>$validated['received_at']
                ]);
                if($payment->booking){
                    $payment->booking->update(['payment_status'=>'paid']);
                }
                return response()->json(['success'=>true,'message'=>'Payment verified & held in escrow']);
            }
        }
        \Log::info('Unmatched SMS received',$validated);
        return response()->json(['success'=>false,'message'=>'No matching payment found']);
    }

    // 🔹 PARSE BANK SMS
    private function parseBankSMS($message)
    {
        if(stripos($message,'jazzcash')!==false){
            preg_match('/Rs\.?\s*([\d,]+)/i',$message,$amount);
            preg_match('/TID[:\s]*([\w\d]+)/i',$message,$tid);
            if(isset($amount[1],$tid[1])) return ['method'=>'JazzCash','amount'=>floatval(str_replace(',','',$amount[1])),'transaction_id'=>$tid[1]];
        }
        if(stripos($message,'easypaisa')!==false){
            preg_match('/Rs\.?\s*([\d,]+)/i',$message,$amount);
            preg_match('/ref[:\s]*([\w\d]+)/i',$message,$ref);
            if(isset($amount[1],$ref[1])) return ['method'=>'EasyPaisa','amount'=>floatval(str_replace(',','',$amount[1])),'transaction_id'=>$ref[1]];
        }
        return null;
    }
}
