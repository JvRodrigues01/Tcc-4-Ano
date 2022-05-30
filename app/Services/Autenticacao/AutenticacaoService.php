<?php

namespace App\Services\Autenticacao;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Interfaces\Admin\UsuarioInterface;
use App\Models\Admin\Usuario;
use App\Models\Admin\Token;
use DateTime;

use App\Functions\Log;
use App\Functions\Crypt;
use App\Helpers\Helpers;
use App\Mail\GenericMail;
use App\Repositories\Interfaces\Admin\LogInterface;

class AutenticacaoService
{
    protected $interface;
    protected $logInterface;

    public function __construct(UsuarioInterface $usuarioInterface, LogInterface $logInterface)
    {
        $this->interface = $usuarioInterface;
        $this->logInterface = $logInterface;
    }


    public function RenewUserToken($token)
    {

        $data = new DateTime();

        $token->ExpiraEm = $data->modify('+ 4 hour')->format("Y-m-d H:i:s");
        $this->interface->SaveToken($token);

        return $token;

    }

    private function SetUserToken($IdUsuario)
    {

        $data = new DateTime();

        $token = $this->interface->GetTokenByUser($IdUsuario);

        if(empty($token)) {

            $newToken = $this->generateToken(64);

            $token = new Token();
            $token->IdUsuario = $IdUsuario;
            $token->Tipo = "Autenticacao";
            $token->Token = $newToken;
            $token->CriadoEm = $data->format("Y-m-d H:i:s");
            $token->UltimoLogin = $data->format("Y-m-d H:i:s");
            $token->ExpiraEm = $data->modify('+ 4 hour')->format("Y-m-d H:i:s");
            $token->Inativo = false;
            $this->interface->SaveToken($token);

        }

        return $token;
    }

    public function Login(Request $request)
    {
        try {
            $data = new DateTime();

            $defaultMessage = "Usuário ou senha não correspondem.";

            if(!$this->validateLogin($request)) {
                $result = [
                    "Message" => $defaultMessage
                ];

                $this->logInterface->SaveLogs("Login", null, $data->format("Y-m-d H:i:s"), null, "Authenticacao", false, $defaultMessage);

                return response()->json($result, Response::HTTP_OK);
            }

            $login = $request->Login;
            $senha = $request->Senha;

            $user = $this->interface->GetUserByLogin($login);

            if(empty($user)) {
                $result = [
                    "Message" => $defaultMessage
                ];
                
                $this->logInterface->SaveLogs("Login", null, $data->format("Y-m-d H:i:s"), null, "Authenticacao", false, $defaultMessage);

                return response()->json($result, Response::HTTP_OK);
            }

            if($user->Inativo == true){
                $result = [
                    "Message" => "Este usuário se encontra inativado, favor entrar em contato o administrador."
                ];
                
                $this->logInterface->SaveLogs("Login", null, $data->format("Y-m-d H:i:s"), $user->IdUsuario, "Authenticacao", false, "Este usuário se encontra inativado, favor entrar em contato o administrador.");

                return response()->json($result, Response::HTTP_OK);
            }

            if(!Crypt::check($senha, $user->Senha)){
                $result = [
                    "Message" => $defaultMessage
                ];
                
                $this->logInterface->SaveLogs("Login", null, $data->format("Y-m-d H:i:s"), null, "Authenticacao", false, $defaultMessage);

                return response()->json($result, Response::HTTP_OK);
            }

            $token = $this->SetUserToken($user->IdUsuario);

            $result = [
                "Token" => $token->Token,
                "Usuario" => $user
            ];
            
            $this->logInterface->SaveLogs("Login", null, $data->format("Y-m-d H:i:s"), $user->IdUsuario, "Authenticacao", true, null);

            return response()->json($result, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];

            Log::Log("Sistema", "Service", "Autenticacao/Login", "Exception", $exception);

            $result = [
                "Message" => "Um erro ocorreu ao realizar o login, favor, tratar com o administrador."
            ];

            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function AlterarSenha(Request $request)
    {
        try {
            $user = $this->interface->SearchUsuario($request->IdUsuario);
            if (empty($user))
                return response()->json(["Message" => "Usuário não encontrado"], Response::HTTP_INTERNAL_SERVER_ERROR);

            if (!Crypt::check($request->SenhaAtual, $user->Senha))
                return response()->json(["Message" => "Senha atual não confere"], Response::HTTP_INTERNAL_SERVER_ERROR);

            $user->Senha = Crypt::hash($request->SenhaNova);
            $this->interface->SaveUsuario($user, null);

            return response()->json($user, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];

            Log::Log("Sistema", "Service", "Autenticacao/AlterarSenha", "Exception", $exception);
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function AlterarSenhaAdmin(Request $request)
    {
        try {
            $user = $this->interface->SearchUsuario($request->IdUsuario);
            if (empty($user))
                return response()->json(["Message" => "Usuário não encontrado"], Response::HTTP_INTERNAL_SERVER_ERROR);

            $user->Senha = Crypt::hash($request->SenhaNova);
            $this->interface->SaveUsuario($user, null);

            return response()->json($user, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];

            Log::Log("Sistema", "Service", "Autenticacao/AlterarSenhaAdmin", "Exception", $exception);
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function RecuperarSenha(Request $request)
    {
        try {
            $email = $request->email;

            $user = $this->interface->GetUserByEmail($email);

            if(empty($user)) {
                $result = [
                    "Message" => "Usuário não encontrado"
                ];

                return response()->json($result, Response::HTTP_OK);
            }

            $senhaNova = $this->generateToken(8);
            $user->Senha = Crypt::hash($senhaNova);
            $this->interface->SaveUsuario($user, null);
            
            $dataEmail = [
                'logo' => env('LOGO_BIZ', 'https://api.bizseller.com.br/images/bizseller_logo_horizontal.png'),
                'cor' => $request->corLoja,
                'nome' => $request->nomeLoja,
                'nova-senha' => $senhaNova ,
                'e-mail' => $email ,
                'subtitulo' => 'Pedido de uma nova senha de acesso.',
                'mensagem' => 'Nova senha de acesso do email: '.$email
            ];

            Mail::to($email)->send(new GenericMail("Recuperação de senha", $dataEmail, 'email.newpassword'));
            $result = [
                "Sucesso" => "Uma nova senha foi enviada para seu e-mail"
            ];

            return response()->json($result, Response::HTTP_OK);
        } catch (\Exception $ex) {
            $exception = [
                'Message' => $ex->getMessage(),
                'Code' => $ex->getCode(),
                'Exception' => $ex->__toString()
            ];

            Log::Log("Sistema", "Service", "Autenticacao/RecuperarSenha", "Exception", $exception);
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function validateLogin(Request $request)
    {
        $errors = [];
        if (!$request->has('Login'))
            array_push($errors, 'Informe o email');

        if (!$request->has('Senha'))
            array_push($errors, 'Informe a senha');

        if (!empty($errors))
            return false;

        return true;
    }

    protected function generateToken(int $length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++)
            $token .= $codeAlphabet[random_int(0, $max-1)];

        return $token;
    }
}
