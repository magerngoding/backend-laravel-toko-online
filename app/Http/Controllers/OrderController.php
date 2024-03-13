<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderController extends Controller
{
    // index
    public function index(Request $request)
    {
        // get orders pagimate 10
        $orders = DB::table('orders')->paginate(10);

        // ngelink ke folder tersebut lgsg ke page->next
        return view('pages.order.index', compact('orders'));
    }

    // show
    public function show($id)
    {
        return view('orders.show');
    }

    // edit
    public function edit($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        return view('pages.order.edit', compact('order'));
    }

    //update
    public function update(Request $request, $id)
    {
        //update status order
        $order = DB::table('orders')->where('id', $id);
        $order->update([
            //column pada edit yang akan dikrim
            'status' => $request->status,
            'shipping_resi' => $request->shipping_resi,
        ]);

        // send notification to user
        if($request->status == 'on_delivery')
        {
            $this->sendNotificationToUser($order->first()->user_id, 'Paket dikirim dengan nomor resi ' . $request->shipping_resi);
        }

        //redirect to index
        return redirect()->route('order.index');
    }

    public function sendNotificationToUser($userId, $message)
    {
        // Dapatkan FCM token dari user table 'users'
        $user = User::find($userId);
        $token = $user->fcm_id;

        // Kirim notifikasi ke perankat Android
        $messaging  = app('firebase.messaging');
        $notification = Notification::create('Paket Dikirim!', $message);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }
}

