<?php
namespace Brs;

class FunctionPkg
{
    // No se puede mandar en las rutas
    // Ya si lo haces es cuestión tuya.
    protected function NeoEncrypt( int $valueA, int $valueB, string $valueC )
    {
        try{

            $json = json_encode(  array( 'act' => $valueA, 'id' => $valueB, 'ini' => $valueC ) );

            $ciphering = "AES-256-CBC";

            $iv_length = openssl_cipher_iv_length( $ciphering );

            $options = 0;

            $encryption_iv = '8AC7230489E80000';

            $encryption_key = "El sebas es mi pastor y lo chapulin no ha de faltar";

            $open_ssl = openssl_encrypt( $json, $ciphering, $encryption_key, $options, $encryption_iv );

        }
        catch ( Exception $error )
        {

            $open_ssl = null;

        }

        return $open_ssl;

    }

    protected function NeoDecrypt( string $value )
    {
        try{

            $ciphering = "AES-256-CBC"; 

            $iv_length = openssl_cipher_iv_length( $ciphering );

            $options = 0;

            $encryption_iv = '8AC7230489E80000';

            $encryption_key = "El sebas es mi pastor y lo chapulin no ha de faltar";

            $json = openssl_decrypt( $value, $ciphering, $encryption_key, $options, $encryption_iv );

            $decription = json_decode( $json );

        } 
        catch ( Exception $error )
        {

            $decription = null;

        }

        return ( object ) $decription;

    }

    public function Encrypt( int $act, int $id, string $ini )
    {
        $value = $this->NeoEncrypt( $act, $id, substr( $ini, 0, 3 ) );

        return $value;
    }

    public function Decrypt( string $value )
    {
        $value = $this->NeoDecrypt( $value );

        return $value;
    }

}
