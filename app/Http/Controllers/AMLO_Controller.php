<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;
use stdClass;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\Formats\Keys\PKCS1;
use OpenPGP_Message;
use OpenPGP;
use OpenPGP_SecretKeyPacket;
use OpenPGP_Crypt_Symmetric;
use OpenPGP_Crypt_RSA;

class AMLO_Controller extends BaseController
{
    public function New_AMLO(Request $request)
    {

        $data = file_get_contents(public_path('amlo/hr02.json.pgp'));
        $pathToPrivateKey = file_get_contents(public_path('amlo/Dummy_Key.key'));

        // $keyASCII = file_get_contents($argv[1]);
        // $msgASCII = file_get_contents($argv[3]);
        
        $keyEncrypted = OpenPGP_Message::parse(OpenPGP::unarmor($pathToPrivateKey, 'PGP PRIVATE KEY BLOCK'));

        // Try each secret key packet
        foreach($keyEncrypted as $p) {
            if(!($p instanceof OpenPGP_SecretKeyPacket)) continue;
        
            $key = OpenPGP_Crypt_Symmetric::decryptSecretKey('passphrase', $p);
            dd($p);
            $msg = OpenPGP_Message::parse(OpenPGP::unarmor($data, 'PGP MESSAGE'));
        
            $decryptor = new OpenPGP_Crypt_RSA($key);
            $decrypted = $decryptor->decrypt($msg);
        
            var_dump($decrypted);
        }
    }
}
