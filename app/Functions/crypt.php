<?php

namespace App\Functions;

//Classe que contem funções para uso de criptografia utilizando método de bcrypt
class Crypt
{
    /**
     *  Salto padrão
     *  O salt precisa ser uma string de 22 caracteres que respeite a expressão regular ./0-9A-Za-z.
     * 
     * @var string
     */    
    protected static $saltPrefix = '2a';

    /**
     *  Custo Padrão
     *  O custo deve ser um número inteiro entre 4 e 31,
     *  outro detalhe é que o custo precisa ter dois dígitos,
     *  então números menores que 10 precisam ter zero à esquerda.
     * 
     * @var integer
     */
    protected static $defaultCost = 8;

    /**
     *  Limite de Saltos
     * 
     * @var integer
     */
    protected static $saltLength = 22;
    

    /**
     *  Método que criptografa a senha
     * 
     *  @param string $password Senha a ser criptografada
     * 
     *  @return string
     */
    public static function hash($password) {
        // Custo padrão
        $cost = self::$defaultCost;       

        // Salto
        $salt = self::generateRandomSalt();

        // Hash string
        $hashString = self::generateHashString((int)$cost, $salt);

        return crypt($password, $hashString);
    }

    /**
     *  Método que valida a senha
     *  
     *  @param string $password Senha sem criptografia
     *  @param string $hash Parte da criptografia que foi salva no db
     * 
     *  @return bool
     */
    public static function check($password, $hash) {
        return (crypt($password, $hash) === $hash);
    }

    /**
     *  Método que gera o salto aleatório
     * 
     *  @return string
     */
    private static function generateRandomSalt() {
        // Salt seed
        $seed = uniqid(mt_rand(), true);

        // Gera o salto
        $salt = base64_encode($seed);
        $salt = str_replace('+', '.', $salt);

        return substr($salt, 0, self::$saltLength);
    }

    /**
     *  Método que gera o hash para o ::crypt()
     * 
     *  @param integer $cost Custo padrão
     *  @param string $salt Salto gerado aleatóriamente
     * 
     *  @return string
     */
    private static function generateHashString($cost, $salt) {
        return sprintf('$%s$%02d$%s$', self::$saltPrefix, $cost, $salt);
    }

    /**
     *  Método encripta uma string usando AES-256
     * 
     *  @param string $input Entrada a ser encriptada
     * 
     *  @return string
     */
    public static function EncryptAES256($input)
    {
        try {
            $algoritmo = "AES-256-CBC";
            $chave = env('ENCRYPT_KEY');
            $salt = env('ENCRYPT_SALT');

            $output = openssl_encrypt($input, $algoritmo, $chave, OPENSSL_RAW_DATA, $salt);
            return base64_encode($output);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     *  Método decripta uma string usando AES-256
     * 
     *  @param string $input Entrada a ser decriptada
     * 
     *  @return string
     */
    public static function DecryptAES256($input)
    {
        try {
            $algoritmo = "AES-256-CBC";
            $chave = env('ENCRYPT_KEY');
            $salt = env('ENCRYPT_SALT');

            $output = openssl_decrypt(base64_decode($input), $algoritmo, $chave, OPENSSL_RAW_DATA, $salt);
            return $output;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}