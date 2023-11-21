<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseStorageService;

class ImgController extends Controller
{
    protected $firebaseStorageService;
    public function __construct(FirebaseStorageService $firebaseStorageService)
    {
        $this->firebaseStorageService = $firebaseStorageService;
    }
    public function showUploadForm()
    {
        return view('image-upload');
    }

    public function upload(Request $request)

    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Giới hạn 2MB cho ảnh
        ]);

        $file = $request->file('image');
        $destinationPath = 'images'; // Thay đổi thư mục theo yêu cầu của bạn

        $objectName = $this->firebaseStorageService->uploadImage($file, $destinationPath);
        dd($objectName);

        // Làm gì đó với $objectName sau khi tải lên, ví dụ: lưu objectName vào cơ sở dữ liệu

        return redirect()->route('upload')->with('success', 'Ảnh đã được tải lên thành công.');
    }

    public function deleteImg()
    {
        $objectName = 'images/image.png';
        $objectName = $this->firebaseStorageService->deleteImage($objectName);
        return 'xong';
    }

    public function showVnpayForm()
    {
        return view('vnpay');
    }
    public function vnpayCheckout(Request $request)
    {
        $pro =[1,2];
        $a = ["prod"=>$pro,"toanf"=>"tyest"];
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('done',$a);
        $vnp_TmnCode = "N0KTZXQZ"; //Mã website tại VNPAY 
        $vnp_HashSecret = "GSUHLGEINQESDRWPSVDRBLZITGBMDYKA"; //Chuỗi bí mật

        $vnp_TxnRef = time() . ""; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán toanf";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = 22000 * 100;
        $vnp_Locale = 'vi';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        // $vnp_ExpireDate = $_POST['txtexpire'];
        //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
        // $vnp_Bill_City = $_POST['txt_bill_city'];
        // $vnp_Bill_Country = $_POST['txt_bill_country'];
        // $vnp_Bill_State = $_POST['txt_bill_state'];
        // // Invoice
        // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
        // $vnp_Inv_Email = $_POST['txt_inv_email'];
        // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
        // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
        // $vnp_Inv_Company = $_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type = $_POST['cbo_inv_type'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_ExpireDate" => $vnp_ExpireDate,
            // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            // "vnp_Bill_Email" => $vnp_Bill_Email,
            // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            // "vnp_Bill_LastName" => $vnp_Bill_LastName,
            // "vnp_Bill_Address" => $vnp_Bill_Address,
            // "vnp_Bill_City" => $vnp_Bill_City,
            // "vnp_Bill_Country" => $vnp_Bill_Country,
            // "vnp_Inv_Phone" => $vnp_Inv_Phone,
            // "vnp_Inv_Email" => $vnp_Inv_Email,
            // "vnp_Inv_Customer" => $vnp_Inv_Customer,
            "vnp_Inv_Address" => "777_zone",
            "vnp_Inv_Company" => "777_zone",
            // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            // "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );

        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
    }
    public function vnpayCheckoutDone(Request $request)
    {
        dd($request->all());
        return 'xong';
    }

    //momo

    public function execPostRequest($url, $data)
    {

        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt(
        //     $ch,
        //     CURLOPT_HTTPHEADER,
        //     array(
        //         'Content-Type: application/json',
        //         'Content-Length: ' . strlen($data)
        //     )
        // );
        // curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        // //execute post
        // $result = curl_exec($ch);
        // //close connection
        // curl_close($ch);
        // // dd($result);
        // return $result;


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function showMomoForm()
    {
        return view('vnpay');
    }
    public function momoCheckout()
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "kkkk";
        $amount = "10000";
        $orderId = time() . "-777zone";
        $redirectUrl = route('momoDone');
        $ipnUrl = route('momoDone');;
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";
        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        //Just a example, please check more in there
        dd($jsonResult);
        return redirect()->to($jsonResult['payUrl']);
        // header('Location: ' . $jsonResult['payUrl']);
    }
    public function momoCheckoutDone(Request $request)
    {
        // Lấy tất cả các biến trong request
        $requestData = $request->all();

        // Sử dụng dd() để in ra và kết thúc request
        dd($requestData);
    }

    public function liretest()
    {
        return view('lirewire-test');
    }

    public function loginGoogleForm()
    {
        return view('loginGoogleForm');
    }
    public function zaloPay()
    {


        // PHP Version 7.3.3

        $config = [
            "appid" => 553,
            "key1" => "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q",
            "key2" => "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3",
            "endpoint" => "https://sandbox.zalopay.com.vn/v001/tpe/createorder"
        ];

        $embeddata = [
            "merchantinfo" => "embeddata123"
        ];
        $items = [
            ["itemid" => "knb", "itemname" => "kim nguyen bao", "itemprice" => 198400, "itemquantity" => 1]
        ];
        $order = [
            "appid" => $config["appid"],
            "apptime" => round(microtime(true) * 1000), // miliseconds
            "apptransid" => date("ymd") . "_" . uniqid(), // mã giao dich có định dạng yyMMdd_xxxx
            "appuser" => "demo",
            "item" => json_encode($items, JSON_UNESCAPED_UNICODE),
            "embeddata" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
            "amount" => 50000,
            "description" => "ZaloPay Intergration Demo",
            "bankcode" => "zalopayapp"
        ];

        // appid|apptransid|appuser|amount|apptime|embeddata|item
        $data = $order["appid"] . "|" . $order["apptransid"] . "|" . $order["appuser"] . "|" . $order["amount"]
            . "|" . $order["apptime"] . "|" . $order["embeddata"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);
        dd($result);
        foreach ($result as $key => $value) {
            echo "$key: $value<br>";
        }
    }
    public function momoTest2()
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


        $partnerCode = 'MOMO';
        $accessKey = 'F8BBA842ECF85';
        $secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = "10000";
        $orderId = time() . "";
        $redirectUrl = route('momoDone');
        $ipnUrl = route('momoDone');
        $extraData = "";
        $lang = 'vi';
        $paymentCode = 'L/U2a6KeeeBBU/pQAa+g8LilOVzWfvLf/P4XOnAQFmnkrKHICj51qrOTUQ+YrX8/Xs1YD4IOdyiGSkCfV6Je9PeRzl3sO+mDzXNG4enhigU3VGPFh67a37dSwItMJXRDuK64DCqv35YPQtiAOVVZV35/1XBw1rWopmRP03YMNgQWedGLHwmPSkRGoT6XtDSeypJtgbLZ5KIOJsdcynBdFEnHAuIjvo4stADmRL8GqdgsZ0jJCx/oq5JGr8wY+a4g9KolEOSTLBTih48RrGZq3LDBbT4QGBjtW+0W+/95n8W0Aot6kzdG4rWg1NB7EltY6/A8RWAHJav4kWQoFcxgfA==';



        $partnerCode = $partnerCode;
        $accessKey = $accessKey;
        $serectkey = $secretKey;
        $orderId = $orderId; // Mã đơn hàng
        $orderInfo =  $orderInfo ;
        $amount = $amount;
        $ipnUrl = $ipnUrl;
        $redirectUrl = $redirectUrl;
        $extraData = $extraData;
        $userInfo = [
            "email"=> "email_add@domain.com"
        ];
        $requestId = time() . "";
        $requestType = "captureWallet";
        $extraData = ($extraData ? $extraData : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $serectkey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'userInfo'=>$userInfo,
            'lang'=>$lang,
            'paymentCode' => $paymentCode,
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        //Just a example, please check more in there
        // dd($jsonResult);
        return redirect()->to($jsonResult['payUrl']);
        return header('Location: ' . $jsonResult['payUrl']);
    }
}
